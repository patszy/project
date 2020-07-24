<?php

	class Connection {
		public $db_user;
		public $db_password;
		public $db_name;
		public $db_connect;
		public $return;

		function __construct($user, $password, $db) {
			$this->db_user = $user;
			$this->db_password = $password;
			$this->db_name = $db;
		}

		function ConnectOpen() {
			$this->db_connect = new mysqli("localhost", $this->db_user, $this->db_password, $this->db_name) or die ("Failed connect to MySQL!");

			if ($this->db_connect->connect_errno) {
				$this->return["error"] = "Failed connect to MySQL!";
                echo "Failed connect to MySQL: (" . $this->db_connect->connect_errno . ") " . $this->db_connect->connect_error;
			}

			return $this->return;
		}

		function ConnectClose() { $this->db_connect->close(); }
    }

?>