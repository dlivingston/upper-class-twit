<?php 
	$query_select = "SELECT * FROM PEOPLE";
	$query_insert = "INSERT INTO PEOPLE (person_id, person_fname, person_lname, person_email) ";
	$sql_truncate = "TRUNCATE TABLE PEOPLE";
	$sql_delete = "DELETE FROM PEOPLE WHERE 1";
	$result = mysqli_query($connection, $query_select);

	//clears the 'PEOPLE' table of any preexisting records
	mysqli_query($connection, $sql_delete);

?>
