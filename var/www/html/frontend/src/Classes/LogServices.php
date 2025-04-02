<?php 
//Class LogServices

namespace App\Classes;
use App\Classes\LogRecord;

/*class LogServices {
	private $path;
	private $name;
	private $log_records = [];

	function __construct($path, $name) {
		if ($path != null) {
			$this->path = $path;
			$this->name = $name;
			
		}
		else $this->path = '/var/www/html/frontend/public/logs/personnel_login.txt';
	}

	function __destruct() {
		$this->flush();
	}
    function filename() {
		return "{$this->path}/{$this->name}";
	}
    public function write($message) {
        $u_agent = array_key_exists('HTTP_USER_AGENT', $_SERVER)? str_replace(';',',', is_null($_SERVER['HTTP_USER_AGENT'])):null;
        $cmd = "echo \"[".time()."] ERR LOGIN; from " .$_SERVER['REMOTE_ADDR'].";".is_null($u_agent)?";":$u_agent.";".$message."\" >> /tmp/backend_log.txt";
		exec("bash -c" .$cmd);
        echo $cmd;
	}

}
    */
  

namespace App\Classes;
use App\Classes\LogRecord;

class LogServices {
	private $path;
	private $name;
	private $log_records = [];

	function __construct($path = "", $name = "") {
		if (!(empty($path))) {
			$this->path = $path;
			$this->name = $name;
			
		}
		else {
            $this->path = '/var/www/html/frontend/public/logs/';
            $this->name = 'personnel_login.txt';
        }
	}

	function __destruct() {
		//$this->flush();
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

	/*public function write($record) {
		if($record instanceof LogRecord) {
			
			$this->log_records[] = $record;
		} else {
			$record = print_r($record, true);
			error_log("provided log record is not instance of LogRecord: {$record}\n");
		}
	}*/
    public function write($message) {
		$output = "";
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $cmd = "echo \"[".time()."] LOGIN from " .$_SERVER['REMOTE_ADDR']." with ".$u_agent.":".$message."\" >> ". sprintf("%s%s", $this->path, $this->name);
        $output = shell_exec($cmd);
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
