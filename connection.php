<!DOCTYPE html>
<html>
<head>
	<title>Connection</title>
</head>
<body>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$db = "sampledb";
		$conn = mysqli_connect($servername, $username, $password, $db);
		if (!$conn) {
			die("Connection Failed: " . mysqli_connect_error());
		}
		//echo "Connected succesfully"
	?>
</body>
</html>
