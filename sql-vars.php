<?php 
	$dbhost = "localhost";
	$dbuser = "DBUSER"; // Insert DB user name here
	$dbpass = "DBPASS"; // Insert DB password here
	$dbname = "DBNAME"; // Insert DB Name here 
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	if(mysqli_connect_errno()){
		die("Database connection failed: " . 
			mysqli_connect_error() . 
			" (" . mysqli_connect_errno() . ")"
		);
	}
 ?>