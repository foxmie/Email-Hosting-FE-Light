<?php

	/*
	
	This file is part of Email Hosting FE Light.

    Email Hosting FE Light is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Email Hosting FE Light is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Email Hosting FE Light.  If not, see <https://www.gnu.org/licenses/>.

	*/
	
	class db{
		
		private $host = DB_HOST;
		private $dbName = DB_NAME;
		private $user = DB_USER;
		private $pass = DB_PASS;
	  
		private $dbh;
		private $error;
		private $qError;
	  
		private $stmt;
	  
		public function __construct(){
			//dsn for mysql
			$dsn = "mysql:host=".$this->host.";dbname=".$this->dbName;
			$options = array(
				PDO::ATTR_PERSISTENT    => true,
				PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
			);
			try{
				$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
			}
			//catch any errors
			catch (PDOException $e){
				$this->error = $e->getMessage();
			}
		}
	  
		public function query($query){
			$this->stmt = $this->dbh->prepare($query);
		}
	  
		public function bind($param, $value, $type = null){
			if(is_null($type)){
				switch (true){
					case is_int($value):
						$type = PDO::PARAM_INT;
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL;
						break;
					default:
						$type = PDO::PARAM_STR;
				}
			}
		$this->stmt->bindValue($param, $value, $type);
		}
	  
		public function execute(){
			return $this->stmt->execute();
		  
			$this->qError = $this->dbh->errorInfo();
			if(!is_null($this->qError[2])){
				echo $this->qError[2];
			}
			echo 'done with query';
		}
	  
		public function resultset(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	  
		public function single(){
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_ASSOC);
		}
	  
		public function rowCount(){
			return $this->stmt->rowCount();
		}
	  
		public function lastInsertId(){
			return $this->dbh->lastInsertId();
		}
	  
		public function beginTransaction(){
			return $this->dbh->beginTransaction();
		}
	  
		public function endTransaction(){
			return $this->dbh->commit();
		}
	  
		public function cancelTransaction(){
			return $this->dbh->rollBack();
		}
	  
		public function debugDumpParams(){
			return $this->stmt->debugDumpParams();
		}
	  
		public function queryError(){
			$this->qError = $this->dbh->errorInfo();
			if(!is_null($qError[2])){
				echo $qError[2];
			}
		}
	  
	}

?>