<?php
// http://simpop.org/ecg/viewdata.php?sessnum=123456

error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
header('Content-type: text/html');

$dataFolder = dirname($_SERVER['DOCUMENT_ROOT']) . '/smpporg/ecg/data/';

function logError($errorText)
{
    global $dataFolder;
    $errorFile = $dataFolder . 'errors.txt';
    file_put_contents($errorFile, "\n" . $errorText, FILE_APPEND);
}

$sessionNum = $_REQUEST['sessnum'];

if ($sessionNum == '') {
    logError("VIEWDATA\t" . date('j-M-y H:i:s') . "\tSession Number Missing");
    print('ERROR: Session Number Missing');
    exit();
}

$sessionFile = $dataFolder . $sessionNum . '.dat';

$sessionData = file_get_contents($sessionFile);

if ($sessionData == FALSE) {
    logError("VIEWDATA\t" . date('j-M-y H:i:s') . "\tSession Number Invalid - " . $sessionFile);
    print('ERROR: Session Number Invalid - ' . $sessionFile);
    exit();
}

$x = "";

$comma_count = substr_count($sessionData, ',');

for ($i = 0; $i < $comma_count; $i++) {
	$x .= ($i.",");
}


$modifiedAtDate = date('d-M-y', filemtime($sessionFile));
$modifiedAtTime = date('H:i:s', filemtime($sessionFile));
?>

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
            font-weight: bold; /* Added to make headings bold */
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        canvas {
            display: block;
        }
        .heading {
            display:block;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
            <tr>
                <td><?php print($sessionNum); ?></td>
                <td><?php print($modifiedAtDate); ?></td>
                <td><?php print($modifiedAtTime); ?></td>
            </tr>
        </table>
    </div>
	<br><br><br><br>
	<div style="overflow-x: scroll">
		<div style="position: relative; width:<?php print(4*$comma_count); ?>px; height400px; ">
			<canvas id="myChart" width="<?php print(4*$comma_count); ?>" height="400"></canvas>
		</div>
	</div>

<script>
	const xValues = [<?php print($x); ?>];
	const yValues = [<?php print($sessionData); ?>];

	new Chart("myChart", {
		type: "line",
		data: {
		labels: xValues,
		datasets: [{
			fill: false,
			lineTension: 0,
			backgroundColor: "rgba(0,0,255,1)",
			borderColor: "rgba(0,0,255,0.3)",
			data: yValues,
			pointRadius: 0,
			pointHoverRadius: 4,
		}]
		},
		options: {
		animation: false,
		events: [],
		legend: {display: false},
        maintainAspectRatio: false,
		scales: {   
			yAxes: [{ticks: {min: 0, max:1000, stepSize: 50}}],
		}
		}
	});
</script>

</body>
</html>