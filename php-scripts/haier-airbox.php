	<?php


class HaierAirBoxReader
{
	var $username = "";
	var $password = "";

	function __construct( $username, $password )
	{
		$this->username = $username;
		$this->password = $password;
	}

	function getData()
	{
		$credentials = $this->getCredentials();
		if ($credentials==null) return null;

		print("Token is ".$credentials["token"].", userId:".$credentials["userId"]."<br>");
		$deviceID = $this->getDeviceID($credentials);
		if ($deviceID==null) return null;
		
		print("Device ID is $deviceID<br>");
		$data = $this->getDeviceData($credentials,$deviceID);
		return $data;
	}

	function getDeviceData($credentials,$deviceID)
	{
		$url = "http://uhome.haier.net:7080/smartair/data/quality/instant/".$deviceID;
		$data = $this->load($url,'{"sequenceId" : "1"}',$credentials["token"]);

		$json = json_decode($data["d"]);
		if ($json!=null && isset($json->data)) return $json->data;

		print("Can not get the device Data!<br>");
		print("<pre>".str_replace("<","&lt;",print_r($data,true))."</pre>");
		return null;
	}

	function getDeviceID($credentials)
	{
		/* Query the devices */
		$url = "http://uhome.haier.net:7080/smartair/users/".$credentials["userId"]."/devices";
		$data = $this->load($url,json_encode(array("sequenceId"=>"1")),$credentials["token"]);

		$json = json_decode($data["d"]);
		if (isset($json->devices[0]->mac)) 
		{
			return $json->devices[0]->mac;
		}

		print("Can not get the device ID!<br>");
		print("<pre>".str_replace("<","&lt;",print_r($data,true))."</pre>");
		return null;

	}


	function getCredentials()
	{
		$post= json_encode(array(
			"loginId"=>$this->username,
			"password"=>$this->password,
			"loginType"=>1,
			"accType"=>0,
			"sequenceId"=>"0"
			));

		$data = $this->load("http://uhome.haier.net:9080/security/userlogin",$post);

		preg_match_all('|accessToken: (.*)|', $data["h"], $content);   
		if (!isset($content[1][0]))
		{
			print("Can not get the token!<br>");
			print("<pre>".str_replace("<","&lt;",print_r($data,true))."</pre>");
			return null;

		}
		$token = $content[1][0];

		$json = json_decode($data["d"]);
		if (!isset($json->userId))
		{
			print("Can not get the user ID!<br>");
			print("<pre>".str_replace("<","&lt;",print_r($data,true))."</pre>");
			return null;
		}

		return array("token"=>$token,"userId"=>$json->userId);

	}

	function load( $url, $postargs, $token= "", $inheaders=null )
	{

		$ch = curl_init();

		$clientId = "00000000 00000000 00000000 00000000 00000000 00000000 00000000 00000000";

		$headers = array(
		    "Accept-Language: en-us,en;q=0.5",
		    "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
		    "Keep-Alive: 300",
		    "Connection: keep-alive",
			"Content-Length: ".($postargs?strlen($postargs):0),
			"appVersion: 2014061301",
			"appKey: ", /* You will need to add your own key here  */
			"appId: MB-SMARTAIR1-0000",
			"Content-Type: application/json",
			"accessToken: $token",
			"clientId: $clientId"
			);
	    
	    if ($inheaders!=null) $headers = array_merge($headers,$inheaders);

	    if ($postargs!=null)
		{
			curl_setopt($ch,CURLOPT_POST, true);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $postargs);
		}
		
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url ); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 120); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 		  120); //timeout in seconds
		
		$data = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($data, 0, $header_size);
		$data = substr($data, $header_size);

		curl_close($ch);

		return array("d"=>$data,"h"=>$header);

	}



}

?>