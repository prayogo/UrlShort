<?php
/*
	Created by Prayogo
	Descr: 
		This page was the index file from the simple shorten url program.
*/

//include UrlData class
require_once('UrlData.php');

$msg = "";
if (isset($_GET['key'])){
	
	//GET Method

	$key = $_GET['key'];

	$data = new UrlData();
	$url = $data->get($key);

	header('Location: ' . $url);
} else if (isset($_POST["txtUrl"])){

	//POST Method

	if ($_POST["txtUrl"] == null || $_POST["txtUrl"] == ""){
		$msg = "Url must be filled.";
	}else{
		
		$data = new UrlData();
		//function generateKey return the new key, increment from the last key that store in database.
		$data->key = $data->generateKey();
		$data->url = $_POST["txtUrl"];

		//function validateUrl return the url if the url is valid, and return empty string if url is not a valid url.
		if ($data->validateUrl() == ""){
			$msg = "Url that your input is not a valid url.";
		}else {
			$data->save();
			$msg = "Your url has been shorten to " . "URLShort/index.php?key=" . $data->key;	
		}
	}
}
?>
<form method="POST" action="index.php">
	<input type="text" name="txtUrl">
	<input type="submit" value="Shorten Url">
	<label><?=$msg?></label>
</form>
