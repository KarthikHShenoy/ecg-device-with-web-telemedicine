<?php
// http://simpop.org/ecg/sesslist.php

error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
header('Content-type: text/html');

$dataFolder = dirname($_SERVER['DOCUMENT_ROOT']) . '/smpporg/ecg/data/';

$fileList = scandir($dataFolder);
$numFiles = count($fileList);

$sessionList = array();

for ($i = 0; $i < $numFiles; $i++) {
    if (strpos($fileList[$i], '.dat') === false) continue;
    $fullPath = $dataFolder . '/' . $fileList[$i];
    $modifiedAt = filemtime($fullPath);

    $sessionList[$fileList[$i]] = $modifiedAt; //associative array, array[filename] = timestamp
}

arsort($sessionList); //reverse sort the array by value i.e. timestamp

$sessionTable = '';

foreach ($sessionList as $fileName => $modifiedAt) {
    $session = substr($fileName, 0, -4);
    $date = date('d-M-y', $modifiedAt);
    $time = date('H:i:s', $modifiedAt);
    $sessionTable .= '<tr>';
    $sessionTable .= '<td><a href="viewdata.php?sessnum=' . $session . '">' . $session . '</a></td>';
    $sessionTable .= '<td>' . $date . '</td>';
    $sessionTable .= '<td>' . $time . '</td>';
    $sessionTable .= '</tr>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
        .heading {
            display:block;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="heading">Low Cost ECG Device with Web-based Telemedicine</div>
        <br><br>
        <table>
            <tr>
                <th>ECG ID</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php echo $sessionTable; ?>
        </table>
    </div>
</body>
</html>
