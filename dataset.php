<?php
	include('connection_data.txt');

	$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
	or die('Error connecting to MySQL server.');
?>

<html>
<head>
	<title>Dungeon AoE/Goad Dataset</title>
</head>
<body>
	<?php
		$query = "SELECT p.player_id, p.player_job_name, p.player_server, p.dungeon_name, d.dungeon_expansion, d.dungeon_sync_level, p.player_level, p.player_aoe, p.player_goad, p.my_job_name, j.role_name
							FROM player p
								JOIN dungeon d USING(dungeon_name)
								JOIN job j ON p.my_job_name = j.job_name
							ORDER BY p.player_id;";
		$result = mysqli_query($conn, $query)
		or die(mysqli_error($conn));
	?>
	<table border="1px">
	<tr>
	<th></th>
	<th>Player Job</th>
	<th>Player Server</th>
	<th>Dungeon</th>	
	<th>Dungeon Expansion</th>
	<th>Player Level</th>
	<th>Player Level Synced?</th>
	<th>AoE?</th>	
	<th>Goad?</th>
	<th>My Job</th>
	<th>My Role</th>
	</tr>
	<?php
			while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
			// check if player is synced
			if ($row[dungeon_sync_level] < $row[player_level]) {
				$synced = "Yes";
			} else {
				$synced = "No";
			}
			print "<tr>";
			print "<td>$row[player_id]</td>";
			print "<td>$row[player_job_name]</td>";
			print "<td>$row[player_server]</td>";
			print "<td>$row[dungeon_name]</td>";
			print "<td>$row[dungeon_expansion]</td>";
			print "<td>$row[player_level]</td>";
			print "<td>$synced</td>";
			print "<td>$row[player_aoe]</td>";
			print "<td>$row[player_goad]</td>";
			print "<td>$row[my_job_name]</td>";
			print "<td>$row[role_name]</td>";
			print "</tr>";
		}


		mysqli_free_result($result);
		mysqli_close($conn);

	?>
	</table>
	</pre>
</body>
</html>
	  