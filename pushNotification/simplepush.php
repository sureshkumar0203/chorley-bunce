<?php

// Put your device token here (without spaces):

$deviceToken = '04b7c2c90d62989609a4f03eca09708ee48650eb3385ff61eaaaaf57b078f74a';
// $deviceToken= '57c3e27a88d08020cfafb537ba975db6c3eb0f4ed5d75489b7b0755334beacf5';
// Put your private key's passphrase here:
$passphrase = 'bunce';

// Put your alert message here:
$message = 'This is test Chorley Bunce';

////////////////////////////////////////////////////////////////////////////////
$arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false));  
$ctx = stream_context_create($arrContextOptions);

stream_context_set_option($ctx, 'ssl', 'local_cert', 'CBL.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
	'ssl://gateway.push.apple.com:2195', $err,
	// 'ssl://api.development.push.apple.com:443', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
	'alert' => $message,
	'sound' => 'default',
	'badge' => 1
	);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
