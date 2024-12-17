
<?php

//phpinfo();
$input_utterance = 'exam';

$witRoot = "https://api.wit.ai/message?";
$witVersion = '20200804';


//echo "Post:" . "<br>" . "$input_utterance" . "<br>";
$witURL = "https://api.wit.ai/message?v=20211221&q=" . urlencode($input_utterance);

$ch = curl_init();
$header = array();
$header[] = 'Authorization: Bearer 432K6GI5MIHLCGYWAK6H6XTJDAIPZYR6';

curl_setopt($ch, CURLOPT_URL, $witURL);
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_HTTPHEADER,$header); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
// fix ssl issue?
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// safer fix add certificate
$certificate = "C:\MAMP\bin\php\php7.4.1\cacert.pem";
//C:\MAMP\bin\php\php7.4.1
curl_setopt($ch, CURLOPT_CAINFO, $certificate);
curl_setopt($ch, CURLOPT_CAPATH, $certificate);

$server_output = curl_exec($ch); 

//if a curl error is thrown
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}

curl_close ($ch);  


echo "<br>";
echo "Response:";
$server_decoded_rsp = json_decode($server_output)->entities->{"issues:issues"};

$response = "";

for ($i = 0; $i < count($server_decoded_rsp); $i++){
	$keyword = $server_decoded_rsp[$i]->value;
	$con_db = mysqli_connect("localhost:8889", "root", "root", "hw2_witAI"); //hw2_withAI
  	if (mysqli_connect_errno($con_db)) {
    	echo "Failed to connect  to MYSql:" . mysqli_connect_error();
  	}
  	$sql_command = "SELECT answer FROM response WHERE keyword = '{$keyword}'";
  	$result = mysqli_query($con_db, $sql_command);
  	$num_rows = mysqli_num_rows($result);
  	if ($num_rows > 0) {
    	$row = mysqli_fetch_array($result);
    	$answer = $row[0];
    	echo "<br>" . $answer;
  	} else {
    	echo "failed";
  	}
  	mysqli_close($con_db);
}


?>


