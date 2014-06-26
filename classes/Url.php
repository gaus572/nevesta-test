<?php	
	class Url {

		protected $_query = array();

		function __construct() {
			if (!empty($_GET)) $this->_query = $_GET;
		}

		public function get_query ($param = array()) {
			$query = array_merge($this->_query, $param);
			
			$str = '';
			$sep = '';
			foreach ($query as $key => $value) {
				if ( is_array($value) ) {
					foreach ($value as $value2) {
						$str .= $sep.$key."[]"."=".$value2;
						$sep = '&';
					}
				} else {
					$str .= $sep.$key."=".$value;
					$sep = '&';
				}
			}
			if ($str) $str = '?'.$str;
			return $str;
		}
	}
?>