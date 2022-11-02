<?php
/*
	Class to send push notifications using Firebase Cloud Messaging for Android

	Example usage
	-----------------------
	$an = new FCMPushMessage($apiKey);
	$an->setDevices($devices);
	$response = $an->send($message);
	-----------------------
	
	$apiKey Your GCM api key
	$devices An array or string of registered device tokens
	$message The mesasge you want to push out

	@author Matt Grundy

	Adapted from the code available at:
	http://stackoverflow.com/questions/11242743/gcm-with-php-google-cloud-messaging

*/
class FCMPushMessage {

	//var $url = 'https://android.googleapis.com/gcm/send';
	//var $url = 'https://fcm.googleapis.com/fcm/send';
	var $serverApiKey = "";
	var $devices = array();
	
	/*
		Constructor
		@param $apiKeyIn the server API key
	*/
	function __construct($apiKeyIn,$deviceIds){
		
		$this->serverApiKey = $apiKeyIn;
		
		if(is_array($deviceIds)){
			$this->devices = $deviceIds;
		} else {
			$this->devices = array($deviceIds);
		}
	}
	
	/*
		Set the devices to send to
		@param $deviceIds array of device tokens to send to
	*/
	/*function setDevices($deviceIds){
	
		if(is_array($deviceIds)){
			$this->devices = $deviceIds;
		} else {
			$this->devices = array($deviceIds);
		}
	
	}*/
	
	/*
		Send the message to the device
		@param $message The message to send
		@param $data Array of data to accompany the message
	*/
	function sendFCM($message, $registration_key){
		
		if(!is_array($this->devices) || count($this->devices) == 0){
			$this->error("No devices set");
		}
		
		if(strlen($this->serverApiKey) < 8){
			$this->error("Server API Key not set");
		}
		
		$path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
		
		$fields = array(
			'to' => $registration_key,
			'notification' => array('title' => 'ABC', 'body'=>$message, 'sound'=>'default', 'icon'=>'icon_silhouette','color'=>'#f28117')
		);
		
		$headers = array(
			'Authorization:key='.$this->serverApiKey.'',
			'Content-Type:application/json'
		);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	
		$result = curl_exec($ch);
	   
		curl_close($ch);
		
		return $result;
		
		/*$ch = curl_init("https://fcm.googleapis.com/fcm/send");
		$header=array('Content-Type: application/json',	"Authorization: key=$this->serverApiKey");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": {    \"title\": \"Bdriver\",    \"text\": \"$message\",    \"sound\": \"ringtone.mp3\"  },    \"to\" : \"$registration_key\"}");
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;*/
	}
	
	function error($msg){
		echo "Android send notification failed with error:";
		echo "\t" . $msg;
		exit(1);
	}
}