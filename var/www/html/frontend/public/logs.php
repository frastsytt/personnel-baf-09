<?php



class LogFile {
	private $path;
	private $name;
	private $log_records = [];

	function __construct($path, $name) {
		$this->path = $path;
		$this->name = $name;
	}

	function __destruct() {
		$this->flush();
	}

	function filename() {
		return "{$this->path}/{$this->name}";
	}

	public function load_records() {
		$log_file = file($this->filename(), FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
		foreach($log_file as $line) {
			$record = json_decode($line, true);
			$this->log_records[] = LogRecord::from_dict($record);
		}
	}

	public function records() {
		return $this->log_records;
	}

	public function write($record) {
		if($record instanceof LogRecord) {
			$this->log_records[] = $record;
		} else {
			$record = print_r($record, true);
			error_log("provided log record is not instance of LogRecord: {$record}\n");
		}
	}

	public function write_logrecords($records) {
		foreach($records as $record) {
			$this->write($record);
		}
	}

	public function flush() {
		$file = fopen($this->filename(), "w+");
		foreach($this->log_records as $record) {
			$json_record = json_encode($record->as_map());
			fwrite($file, "{$json_record}\n");
		}
		fclose($file);
	}
}

class LogRecord {
	private $level;
	private $time;
	private $msg;

	function __construct($time, $level, $msg) {
		$this->time = $time;
		$this->level = $level;
		$this->msg = $msg;
	}

	public static function from_dict($dict) {
		return new self($dict['time'], $dict['level'], $dict['msg']);
	}

	function as_map() {
		return [
			"time" => $this->time,
			"level" => $this->level,
			"msg" => $this->msg,
		];
	}

	function get_level() {
		return $this->level;
	}

	function get_time() {
		return $this->time;
	}

	function get_msg() {
		return $this->msg;
	}
}


$logs = new LogFile( __DIR__ ."/logs", "logs.json");
$logs->load_records();

$data = json_decode(file_get_contents('php://input'), true);
if(!empty($data)) {
	if(isset($data['records']) && $data['records']) {
		$logs->write_logrecords(unserialize(base64_decode($data['records'])));
	} else {
		$logs->write(LogRecord::from_dict($data));
	}
}

?>
	<div>
    <style>

        #logs {
            width: 85%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding-left: 20px;;
        }
        th, td {
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: cornflowerblue;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>

    <table id='logs'>
        <thead>
            <tr><th>Time</th><th>Level</th><th>Message</th></tr>
        </thead>
		<tbody>
    <?php
        foreach ($logs->records() as $record) {
            $time = htmlspecialchars($record->get_time(), ENT_QUOTES, 'UTF-8');
            $level = htmlspecialchars($record->get_level(), ENT_QUOTES, 'UTF-8');
            $msg = htmlspecialchars($record->get_msg(), ENT_QUOTES, 'UTF-8');

            echo "<tr>";
            echo "<td>{$time}</td>";
            echo "<td>{$level}</td>";
            echo "<td>{$msg}</td>";
            echo "</tr>";
            echo PHP_EOL;
        }
    ?>
</tbody>

    </table>
	</div>
