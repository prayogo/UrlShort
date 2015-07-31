<?php
/*
	Create by Prayogo
	Descr:
		This class using for initiating the connection to database.
*/
class Connection {
	private $servername = "localhost";
	private $username = "root";	
	private $password = "";	
	private $conn = null;

	public function open(){
		$conn = new mysqli($this->servername, $this->username, $this->password, "appdb");

		if ($conn->connect_error) {
		    die($conn->connect_error);
		} 		

		return $conn;
	}

	public function close(){
		mysqli_close($conn);
	}
}





?>