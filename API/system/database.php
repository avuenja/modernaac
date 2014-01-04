<?php
require("../config.php");
/*Database class for Modern AAC API.*/
class database extends mysqli {
	private $status;
	public function __construct() {
		global $config;
		parent::__construct($config['database']['host'],$config['database']['login'],$config['database']['password'],$config['database']['database']);
		$this->status = 1;
	}
}

?>