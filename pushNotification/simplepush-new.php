<?php
// Put your device token here (without spaces):
$deviceToken ="0d96b85f80163f85b02110c5df6aa8ae4a7a495b90eaa15ca63ab64a5ecb5c64";
// Put your private key's passphrase here:
$passphrase = 'bunce';
// Put your alert message here:
$message = 'Gapa helebi sata!';
$badge=2;

////////////////////////////////////////////////////////////////////////////////
$arrContextOptions=array(
        "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  


$ctx = stream_context_create($arrContextOptions);
stream_context_set_option($ctx, 'ssl', 'local_cert', 'CB.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
//$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

$fp = stream_socket_client('ssl://api.development.push.apple.com:443', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);



if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
	'alert' => $message,
	'sound' => 'default',
	'badge' => $badge
	);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));
//print_r($result);exit;

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
