<?php 
// http://simpop.org/ecg/savedata.php?sessnum=123456&sessdata=123,343,454,565,678,987

error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
header('Content-type: text/html'); 

;
$dataFolder = dirname($_SERVER['DOCUMENT_ROOT']).'/smpporg/ecg/data/'; 

function logError($errorText){ 
	global $dataFolder; 
	$errorFile = $dataFolder.'errors.txt'; 
	file_put_contents($errorFile, "\n".$errorText, FILE_APPEND); 
}

$sessionNum  = $_REQUEST['sessnum'];
$sessionData = $_REQUEST['sessdata'];

if($sessionNum==''){
	logError("SAVEDATA\t" . date('j-M-y H:i:s') . "\tSession Number Missing");
	print('ERROR: Session Number Missing'); 
	exit();
}

$sessionFile = $dataFolder . date('md') . $sessionNum . '.dat';

//modify sessionData, if needed, here

file_put_contents($sessionFile, $sessionData, FILE_APPEND);  // creates file if new session, else appends

print('SUCCESS'); 

?>