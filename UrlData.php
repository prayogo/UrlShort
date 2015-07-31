<?php
require_once('Connection.php');

class UrlData {
	public $id;
	public $key;
	public $url;

	//Method save, saving data into database.
	public function save(){
		$db = new Connection();
		$conn = $db->open();

		$sql = "INSERT INTO urlmapping (`key`, url) VALUES (?, ?)";

		$stmt = $conn->prepare($sql);

		if($stmt === false) {
		  	trigger_error($conn->error, E_USER_ERROR);
		}

		$stmt->bind_param('ss', $this->key, $this->url);
		$stmt->execute();

		$conn->close();
	}

	//Method get, return url wtih specific key
	public function get($key){
		$url = "";
		$db = new Connection();
		$conn = $db->open();

		$sql = "SELECT id, `key`, url FROM urlmapping where `key` = ?";

		$stmt = $conn->prepare($sql);

		if($stmt === false) {
		 	trigger_error($conn->error, E_USER_ERROR);
		}

		$stmt->bind_param('s', $key);
		$stmt->execute();

		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
		        $url = $row["url"];
		    }
		}

		$conn->close();

		return $url;
	}

	//Method generate key, increment from the last key store in the database
	public function generateKey(){
		$key = "00000";

		$db = new Connection();
		$conn = $db->open();

		$sql = "SELECT id, `key` FROM urlmapping ORDER BY id DESC LIMIT 1";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
		        $key = $row["key"];
		    }
		}

		for($i = 4; $i >= 0; $i--){
			$ascii = ord($key[$i]);
			if ($ascii < 122){
				if ($ascii == 57){
					$ascii = 65;
				}else if ($ascii == 90){
					$ascii = 97;
				}else{
					$ascii++;
				}
				$key[$i] = chr($ascii);
				break;
			}

			$key[$i] = '0';
		}

		$conn->close();

		return $key;
	}

	//Method validate url, validation for valid url.
	public function validateUrl(){
		$pos = -1;
		$pos = strpos($this->url, '//');
		$url = "";
		if ($pos != -1){
			$prefix = substr($this->url, 0, $pos);

			//The network protocol is configure in this line. 
			//For the sample, just using http, https, and ftp.
			if ($prefix == "http:" || $prefix == "https:" || $prefix == ":" || $prefix == "ftp:"){
				$url = substr($this->url, $pos + 2, strlen($this->url) - $pos);	
				return $url;
			}else{
				return "";
			}
		}

		return "";
	}
}

?>