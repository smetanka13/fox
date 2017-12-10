<?php

class JSON {

	public static $json_array = [
		"status" => true,
	];

	public static function write($key, $str) {
		self::$json_array[$key] = $str;
		self::$json_array['status'] = false;
	}

	public static function pop($print = false) {
		if($print)
			echo json_encode(self::$json_array, JSON_UNESCAPED_UNICODE);
		else
			return json_encode(self::$json_array, JSON_UNESCAPED_UNICODE);
	}

	public static function ok() {
		return self::$json_array['status'];
	}

	public static function insert($key, $str) {
		self::$json_array[$key] = $str;
	}

}
