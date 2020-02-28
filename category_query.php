<?php
	include('connection_data.txt');

	$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
	or die('Error connecting to MySQL server.');
?>

<html>
<head>
	<title>Category Query</title>
</head>
<body>
	<?php
	  
	$category = $_POST['category'];

	$query = "SELECT *
			  			FROM job_action ja
			  		WHERE ja.job_action_category REGEXP ?
			  		ORDER BY ja.job_name;";
	?>

	<p>
	The query:
	<p>

	<?php
	print $query;
	print "<br>";
	print "? = ".$category;
	?>

	<hr>
	<p>
	Result of query:
	<p>

	<table border="1px">
	<tr>
	<th>action_name</th>
	<th>job_name</th>
	<th>job_action_category</th>
	</tr>
	<?php
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $query)) {
		print "MYSQL statement failed.";
	} else {
		mysqli_stmt_bind_param($stmt, "s", $category);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
			print "<tr>";
			print "<td>$row[action_name]</td>";
			print "<td>$row[job_name]</td>";
			print "<td>$row[job_action_category]</td>";
			print "</tr>";
		}
	}

	mysqli_free_result($result);

	mysqli_close($conn);

	?>
	</table>
	</pre>
</body>
</html>
	  