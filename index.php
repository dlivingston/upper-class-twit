<?php include 'head.php' ?>
<?php include 'nav.php' ?>
<?php include 'sql-vars.php' ?>
<?php include 'sql-queries.php' ?>

<div class="container">
	<div class="jumbotron">
		<h1>Project: Upper Class Twit Of The Year</h1>
		<p>In case it escaped your attention, I have a prediliction for nameing my projects after lines or titles from Monty Python sketches. The how and why tends to be random and based on my own sense of whimsy.</p>
		<p>At any rate, this particular project started sometime around July 2014. I recieved a list of test criteria for seeing what I could do in PHP. Unfortunately I don't have the original requirements document, but the requirements were roughly as follows...</p>
		<ul>
			<li>Take a provided .csv file and import it into an array</li>
			<li>Take the array, and check for missing or incorrect data (ie. invalid email address, sql injections, etc.)</li>
			<li>Import the array into a MySQL table</li>
			<li>Display the MySQL data with a timestamp</li>
		</ul>
	
	</div>
	<style>
		.people {
			margin: 20px 0;
		}
		.people, td {
			border: 1px solid #ffffff;
		}
		.people td {
			padding: 5px 10px;
		}
		.newpeople {
			margin: 20px 0;
			text-decoration: underline;
			font-size: 1.5em;
		}
	</style>
	<h3>Reading the CSV file</h3>
	<table class="people">
	<?php 
		if (($handle = fopen("people_csv.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				echo "<tr>\n";
				for ($c=0; $c < $num; $c++) {
					echo "<td>" . $data[$c] . "</td>\n";
				}
				echo "</tr>\n";
			}	
			fclose($handle);
		}
	?>
	</table>
	<h3>CSV Data Imported into Array and Scrubed</h3>

	<?php 
		$peopleArr = array();
		if (($handle = fopen("people_csv.csv", "r")) !== FALSE) {
			$coltitles = array('person_id', 'person_fname', 'person_lname', 'person_email');
			$key = 0;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$count = count($data);
				for ($i=0; $i <  $count; $i++) { 
					$peopleArr[$key][$i] = $data[$i];
				}
				$peopleArr[$key] = array_combine($coltitles, $peopleArr[$key]);
				$key++;
			}
			fclose($handle);
		}
		$firstline = array_shift($peopleArr);
		// print_r($peopleArr);
		// echo "<br>";
		// print_r(count($peopleArr));
		// echo "<br>";

		foreach ($peopleArr as $key => $values) {
			$peopleArr[$key]['person_lname'] = ucfirst($peopleArr[$key]['person_lname']);
			$peopleArr[$key]['person_fname'] = ucfirst($peopleArr[$key]['person_fname']);
			if(!filter_var($values['person_email'], FILTER_VALIDATE_EMAIL)){
				unset($peopleArr[$key]);
			}
			if(in_array('', $values)){
				unset($peopleArr[$key]);
			}
		}
		$peopleArr = array_values($peopleArr);
		
		// print_r($peopleArr);
		// echo "<br>";
		// print_r(count($peopleArr));
		// echo "<br>";
	?>

	<table class="people">
	<?php 
		foreach ($peopleArr as $row){
			echo "<tr>\n";
			foreach ($row as $item) {
				echo "<td>" . $item . "</td>\n";
			}
			echo "</tr>\n";
		}
	 ?>
	</table>
	<?php 

		foreach ($peopleArr as $row) {
			$query_values = "VALUES ({$row['person_id']}, '{$row['person_fname']}', '{$row['person_lname']}', '{$row['person_email']}')";
			mysqli_query($connection, $query_insert.$query_values);
		}
		$newPeople = array();
		$npcsv = fopen('new_people.csv', 'w');
		while($row = mysqli_fetch_assoc($result)){
			$newPeople[] = $row;
		}
		$peopleReordered = array();
		foreach ($newPeople as $key => $value) {
			$peopleReordered[$key]['datetime_added'] 	= $value['datetime_added'];
			$peopleReordered[$key]['person_email'] 		= $value['person_email'];
			$peopleReordered[$key]['person_lname'] 		= $value['person_lname'];
			$peopleReordered[$key]['person_fname']		= $value['person_fname'];
			$peopleReordered[$key]['person_id']			= $value['person_id'];
		}
	?>
	<h3>MySQL Data imported into Array</h3>
	<table class="people">
	<?php 
		foreach ($peopleReordered as $row){
			echo "<tr>\n";
			foreach ($row as $item) {
				echo "<td>" . $item . "</td>\n";
			}
			echo "</tr>\n";
		}
	 ?>
	</table>
	<?php 
		foreach ($peopleReordered as $row){
			fputcsv($npcsv, $row);
		}
		fclose($npcsv);
	 ?>
	 <a href="new_people.csv" class="newpeople">Link to new_people.csv file</a>
</div>

<?php include 'footer.php' ?>
<?php mysqli_close($connection); ?>