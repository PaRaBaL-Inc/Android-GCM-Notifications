<?php

// API access key from Google API's Console
function sendPushToUsers($userIDs, $message)
{
//connect to db
$con = mysql_connect('localhost', 'user', 'password');
mysql_select_db("database",$con);

//get registration token for each user
foreach($userIDs as $user)
{
$res = mysql_query("SELECT device_id FROM gcm where user_id='$user'");
$row = mysql_fetch_assoc($res);
$registrationIds[] = $row["device_id"];
}

//API key from Google
define( 'API_ACCESS_KEY', 'key' );






// prep the bundle
$msg = array
(
	'message' 	=> $message,
);

$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . API_ACCESS_KEY,
	'Content-Type: application/json'
);
//Send message through gcm
for($x = 0; $x<1; $x++)
{ 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

echo $result;
}
}
?>
