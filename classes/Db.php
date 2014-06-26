<?php	
	class Db {
		
		var $connection_mysql;
		var $res;
		
		function __construct() {
			$this->open_connection_mysql();
		}
		
		private function open_connection_mysql() {
			if ($this->connection_mysql) {
				mysql_close($this->connection_mysql);
				unset($this->connection_mysql);
			}
			$this->connection_mysql = mysql_connect(DB_MYSQL_HOST, DB_MYSQL_USER, DB_MYSQL_PASS, TRUE);
			if (!$this->connection_mysql) {
				die("MYSQL DateBase connection is failed: ".mysql_error());
			} else {
				$db_select = mysql_select_db(DB_MYSQL_NAME);
				if (!$db_select) {
					die("DateBase selection is failed: ".mysql_error());
				}
			}
			mysql_query("set names utf8") or die("set names utf8 failed");
		}
		
		public function sql ($query) {
			$result = @mysql_query($query, $this->connection_mysql);
			if (!$result) {
				die("DateBase query failed: ".mysql_error()."<br/>");
			}
			$this->res = $result;
			return $this;
		}

		public function get_all ($key = NULL, $value = NULL) {
			if (!$this->res) return false;
			$result = array();
			while ($row = mysql_fetch_assoc($this->res)) {
				if ($key) $result[$row[$key]] = $value ? $row[$value] : $row;
				else $result[] = $row;
			}
			return $result;
		}

		public function get_row () {
			if (!$this->res) return false;
			return mysql_fetch_assoc($this->res);
		}

		public function get_one () {
			if (!$this->res) return false;
			$row = mysql_fetch_assoc($this->res);
			return reset($row);
		}
	}
?>
