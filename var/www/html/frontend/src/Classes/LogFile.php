<?php 

namespace App\Classes;
use App\Classes\LogRecord;

class LogFile {
	private $path;
	private $name;
	private $log_records = [];

	function __construct($path, $name) {
		if ($path != null) {
			$this->path = $path;
			$this->name = $name;
			
		}
		else $this->path = '/var/www/html/frontend/public/logs/logs.json';
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