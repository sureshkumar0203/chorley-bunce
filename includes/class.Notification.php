<?php
error_reporting(0);
include_once "FCMPushMessage.php";
//Involves Any User operations************************************************************
class Notification extends FCMPushMessage{	
	function Notification(){
		//$this->parent($api_key);
	}
	#################################################################################################
	################################## SEND IOS NOTIFICATION ########################################
	#################################################################################################
	function send_ios_notification($deviceToken,$message){
	
	  //echo $deviceToken."---".$message;exit;
	  //echo "here===ios function calling";exit;
	  $passphrase = 'bunce';
	  $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false));  
	  $ctx = stream_context_create($arrContextOptions);
	  
	  //Development Environment
	  stream_context_set_option($ctx, 'ssl', 'local_cert', './pushNotification/CBL.pem');
	  
	  //Production Environment
	  //stream_context_set_option($ctx, 'ssl', 'local_cert', './pushnotificationlive/rt.pem');
	  
	  stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	  // Open a connection to the APNS server
	  
	  //Development Environment
	  //$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	  
	  
	  try{
		  //Development Environment
		  $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		   
		   //Production Environment
		  //$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		  
		  //$fp = stream_socket_client('ssl://api.development.push.apple.com:443', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		  
		  if($fp){
			  //Create the payload body
			  $body['aps'] = array('alert' => $message,'sound' => 'default','badge' => 1);
			  //Encode the payload as JSON
			  $payload = json_encode($body);
			  //Build the binary notification
			  $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			  //Send it to the server
			  $result = fwrite($fp, $msg, strlen($msg));
			  
			  //This section shows message is going or not
			  /*if (!$result)
			  echo 'Message not delivered' . PHP_EOL;
			  else
			  echo 'Message successfully delivered' . PHP_EOL;*/
		  }
		  
		  /*if (!$fp)
		  exit("Failed to connect: $err $errstr" . PHP_EOL);
		  echo 'Connected to APNS' . PHP_EOL;
		  // Close the connection to the server
		  fclose($fp);*/
	  }catch(Exception $e){
		  //echo 'Caught exception: ',  $e->getMessage(), "\n";
	  }
	  return true;
	}
	
	#################################################################################################
	################################## SEND ANDROID NOTIFICATION ####################################
	#################################################################################################
	function send_android_notification($registration_key,$message){
		if($registration_key!=''){
			$api_key = "AIzaSyBBymU8axr_jTKfG0bexnOCcpLEknQCX_M";//replace this key according to application
			parent::__construct($api_key,array($registration_key));
			$response = $this->sendFCM($message, $registration_key);
			//print_r($response);exit;
			//$an = GCMPushMessage($api_key);
			//$an->setDevices(array($registration_key));
			//$response = $an->send($message);
		}
		return true;
	}
}
?>