<?php
namespace App\Classes;
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
//

