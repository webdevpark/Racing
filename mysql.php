<?php
	
	
class mysql {
	
	var $hostname = "alton.snhosting.dk";
	var $username = "goosypetscom";
	var $password = "wRRnu8Gk";
	var $database = "goosypetscom";
	var $conn_id = false;
	
	var $result_id = false;
	
	function connect() {
		$this->conn_id = @mysql_connect($this->hostname, $this->username, $this->password, TRUE) or die("mysql connect error");
	}
	
	
	function selectDb($database) {
		$this->database = $database;
		return @mysql_select_db($this->database, $this->conn_id);
	}
	function simpleQuery($query) {
		$this->result_id = @mysql_query($query, $this->conn_id);
	}
	
	function insert($table, $keys, $values) {
		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}
	
	function limit($query, $limit, $offset)
	{	
		if ($offset == 0)
		{
			$offset = '';
		}
		else
		{
			$offset .= ", ";
		}
		
		return $query."LIMIT ".$offset.$limit;
	}
	
	function close()
	{
		@mysql_close($this->conn_id);
	}
	
	function result_array()
	{
		$this->result_array = array();
		while ($row = @mysql_fetch_object($this->result_id))
		{
			$this->result_array[] = $row;
		}
		return $this->result_array;
	}

}