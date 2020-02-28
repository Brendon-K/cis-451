<html>
<head>
	<title>FFXIV Dungeon AoE Database</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<?php
	include('connection_data.txt');
	$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
	or die('Error connecting to MySQL server.');
?>
</head>
<body>
<p>A list of source files for all the pages in this project can be found <a href="src/">here</a>.</p>
<!-- search for actions of certain categories -->
<h4>Action Search - Category</h4>
<p>Enter a category of skill you want to search</p>
<form action="category_query.php" method="POST">
	<input type="text" name="category">
	<input type="submit" value="submit">
</form>
(examples for categories include Damage, Combo, Heal, Debuff, etc.)

<hr>

<!-- search for actions for a specific job -->
<h4>Action Search - Job</h4>
<p>Select a job to see a list of its actions</p>
<?php
	$query = "SELECT job_name FROM job;";
	$result = mysqli_query($conn, $query)
	or die(mysqli_error($conn));
?>
<form action="job_action_query.php" method="POST">
	<select name="job">
		<?php
			while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
				print "<option value=\"$row[job_name]\">$row[job_name]</option>";
			}

			mysqli_free_result($result);
		?>
	</select>
	<input type="submit" value="submit">
</form>

<hr>

<!-- display some stats from my data set -->
<h4>Data Statistics</h4>
<p>To see the dataset in full, <a href="dataset.php">click here</a></p>

<h5>Server Count</h5>
<p>Number of players I encountered from each server</p>
<canvas id="server_chart"></canvas>
<?php
	$query = "SELECT player_server FROM player;";
	$result = mysqli_query($conn, $query)
	or die(mysqli_error($conn));

	$servers = array();
	$player_counts = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
		// check if the current server is already in the array
		if (in_array($row[player_server], $servers)) {
			// if it is, then increment the number of players in that server
			++$player_counts[$row[player_server]];
		} else {
			// if not, add it to server list, and set player count to 1
			$servers[] = $row[player_server];
			$player_counts[$row[player_server]] = 1;
		}

	}

	mysqli_free_result($result);
?>
<script>
	var server_canvas = document.getElementById('server_chart').getContext('2d');
	var servers = <?php echo json_encode($servers); ?>;
	var player_counts_ = <?php echo json_encode($player_counts); ?>;
	var player_counts = [];
	for (var i = 0; i < servers.length; ++i) {
	    player_counts.push(player_counts_[servers[i]]);
	}
	
	var server_chart = new Chart(server_canvas, {
		type: 'bar',
		data:{
			labels: servers,
			datasets: [{
				label: 'Player Count',
				data: player_counts,
				backgroundColor: '#7775bf'
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
</script>

<br>
<h5>Player Job Count</h5>
<p>Count of the number of each job I recorded from other players</p>
<canvas id="job_chart"></canvas>
<?php
	$query = "SELECT player_job_name FROM player;";
	$result = mysqli_query($conn, $query)
	or die(mysqli_error($conn));

	$jobs = array();
	$player_counts = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
		// check if the current job is already in the array
		if (in_array($row[player_job_name], $jobs)) {
			// if it is, then increment the number of players of that job
			++$player_counts[$row[player_job_name]];
		} else {
			// if not, add it to job list, and set player count to 1
			$jobs[] = $row[player_job_name];
			$player_counts[$row[player_job_name]] = 1;
		}
	}

	mysqli_free_result($result);
?>
<script>
	var job_canvas = document.getElementById('job_chart').getContext('2d');
	var jobs = <?php echo json_encode($jobs); ?>;
	var player_counts_ = <?php echo json_encode($player_counts); ?>;
	var player_counts = [];
	for (var i = 0; i < jobs.length; ++i) {
	    player_counts.push(player_counts_[jobs[i]]);
	}
	
	var job_chart = new Chart(job_canvas, {
		type: 'bar',
		data:{
			labels: jobs,
			datasets: [{
				label: 'Player Count',
				data: player_counts,
				backgroundColor: '#6fbf87'
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
</script>

<br>
<h5>AoE Frequency Count</h5>
<p>Counts how often people used AoE actions throughout each dungeon</p>
<canvas id="aoe_chart"></canvas>
<?php
	$query = "SELECT player_aoe FROM player;";
	$result = mysqli_query($conn, $query)
	or die(mysqli_error($conn));

	$aoes = array();
	$player_counts = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
		// check if the current job is already in the array
		if (in_array($row[player_aoe], $aoes)) {
			// if it is, then increment the number of players of that job
			++$player_counts[$row[player_aoe]];
		} else {
			// if not, add it to job list, and set player count to 1
			$aoes[] = $row[player_aoe];
			$player_counts[$row[player_aoe]] = 1;
		}
	}

	mysqli_free_result($result);
?>
<script>
	var aoe_canvas = document.getElementById('aoe_chart').getContext('2d');
	var aoes = <?php echo json_encode($aoes); ?>;
	var player_counts_ = <?php echo json_encode($player_counts); ?>;
	var player_counts = [];
	for (var i = 0; i < aoes.length; ++i) {
		player_counts.push(player_counts_[aoes[i]]);
	}
	
	var aoe_chart = new Chart(aoe_canvas, {
		type: 'bar',
		data:{
			labels: aoes,
			datasets: [{
				label: 'Player Count',
				data: player_counts,
				backgroundColor: '#bf6f6f'
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
</script>

<br>
<h5>Goad Frequency Count</h5>
<p>Counts how often people used goad actions throughout each dungeon</p>
<canvas id="goad_chart"></canvas>
<?php
	$query = "SELECT player_goad FROM player;";
	$result = mysqli_query($conn, $query)
	or die(mysqli_error($conn));

	$goads = array();
	$player_counts = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
		// check if the current job is already in the array
		if (in_array($row[player_goad], $goads)) {
			// if it is, then increment the number of players of that job
			++$player_counts[$row[player_goad]];
		} else {
			// if not, add it to job list, and set player count to 1
			$goads[] = $row[player_goad];
			$player_counts[$row[player_goad]] = 1;
		}
	}

	mysqli_free_result($result);
?>
<script>
	var goad_canvas = document.getElementById('goad_chart').getContext('2d');
	var goads = <?php echo json_encode($goads); ?>;
	var player_counts_ = <?php echo json_encode($player_counts); ?>;
	var player_counts = [];
	for (var i = 0; i < goads.length; ++i) {
	    player_counts.push(player_counts_[goads[i]]);
	}
	
	var goad_chart = new Chart(goad_canvas, {
		type: 'bar',
		data:{
			labels: goads,
			datasets: [{
				label: 'Player Count',
				data: player_counts,
				backgroundColor: '#bfae75'
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
</script>

<br>
<h5>Player Level Distribution</h5>
<table width='100%' border='1px'>
	<tr>
		<td width='50%'>
			<p>Shows the distribution of the levels of the damage dealers I played with</p>
			<canvas id="level_chart"></canvas>
		</td>
		<td width='50%'>
			<p>Shows the distribution of level requirements for the dungeons I completed</p>
			<canvas id="dungeon_chart"></canvas>
		</td>
	</tr>
</table>
<?php
	$min_level = 15;
	$max_level = 70;
	$player_counts = array();
	for ($i = $min_level; $i <= $max_level; ++$i) {
		$query = "SELECT COUNT(*) as total FROM player
							WHERE player_level = ";
		$query = $query.$i.";";

		$result = mysqli_query($conn, $query)
		or die(mysqli_error($conn));

		$data = mysqli_fetch_assoc($result);
		$player_counts[] = $data[total];
	}

	mysqli_free_result($result);

	$query = "SELECT d.dungeon_req_level, COUNT(d.dungeon_req_level) as total
						FROM player p
							JOIN dungeon d USING(dungeon_name)
						GROUP BY d.dungeon_req_level;";

	$result = mysqli_query($conn, $query)
	or die(mysqli_error($conn));

	$req_levels = array();
	$req_level_counts = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
		$req_levels[] = $row[dungeon_req_level];
		$req_level_counts[] = $row[total];
	}

	mysqli_free_result($result);
	mysqli_close($conn);
?>
<script>
	var level_canvas = document.getElementById('level_chart').getContext('2d');
	var levels = [15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 
								25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 
								35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 
								45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 
								55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 
								65, 66, 67, 68, 69, 70];
	var player_counts = <?php echo json_encode($player_counts); ?>;
	
	var level_chart = new Chart(level_canvas, {
		type: 'bar',
		data:{
			labels: levels,
			datasets: [{
				label: 'Level Count',
				data: player_counts,
				backgroundColor: "#626262"
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});

	var dungeon_canvas = document.getElementById('dungeon_chart').getContext('2d');
	var req_levels = <?php echo json_encode($req_levels); ?>;
	var req_level_counts = <?php echo json_encode($req_level_counts); ?>;

	var dungeon_chart = new Chart(dungeon_canvas, {
		type:'bar',
		data: {
			labels: req_levels,
			datasets: [{
				label: 'Dungeon Required Level Count',
				data: req_level_counts,
				backgroundColor: "#424242"
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
</script>

</body>
</html>