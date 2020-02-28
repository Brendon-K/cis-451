<?php
	include('connection_data.txt');

	$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
	or die('Error connecting to MySQL server.');
?>

<html>
<head>
	<title>Job Query</title>
</head>
<body>
	<?php
	  
	$job = $_POST['job'];

	$query = "SELECT action_name, action_level, action_type, action_cast_time, action_cooldown, action_cost, action_resource, action_range, action_radius, action_category
						FROM action a
							JOIN (
								SELECT action_name, job_action_category as action_category FROM job_action
								WHERE job_name = ?
								UNION ALL
								SELECT action_name, role_action_category as action_category FROM role_action
								WHERE role_name = (
									SELECT role_name 
									FROM job j
									WHERE job_name = ?
								)
    					) as job_action_query USING(action_name)
    				ORDER BY action_level, action_name;";
	?>

	<p>
	The query:
	<p>

	<?php
	print $query;
	print "<br>";
	print "? = ".$job;
	?>

	<hr>
	<p>
	Result of query:
	<p>

	<table border="1px">
	<tr>
	<th>name</th>
	<th>level</th>
	<th>type</th>
	<th>cast time</th>
	<th>cooldown</th>
	<th>cost</th>
	<th>range</th>
	<th>radius</th>
	<th>category</th>
	</tr>
	<?php
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $query)) {
		print "MYSQL statement failed.";
	} else {
		mysqli_stmt_bind_param($stmt, "ss", $job, $job);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
			// process cast time display
			if ($row[action_cast_time] == 0.0) {
				$row[action_cast_time] = "Instant";
			} else {
				$row[action_cast_time] = $row[action_cast_time]."s";
			}
			print "<tr>";
			print "<td>$row[action_name]</td>";
			print "<td>$row[action_level]</td>";
			print "<td>$row[action_type]</td>";
			print "<td>$row[action_cast_time]</td>";
			print "<td>$row[action_cooldown]s</td>";
			print "<td>$row[action_cost] $row[action_resource]</td>";
			print "<td>$row[action_range]y</td>";
			print "<td>$row[action_radius]y</td>";
			print "<td>$row[action_category]</td>";
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
	  