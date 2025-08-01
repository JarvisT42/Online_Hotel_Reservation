<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Chart.js Offline</title>
</head>

<body>

	<canvas id="myChart" width="400" height="200"></canvas>

	<!-- Load Chart.js -->
	<script src="node_modules/chart.js/dist/chart.umd.js"></script>

	<!-- Create the chart AFTER Chart.js is loaded -->
	<script>
		window.onload = function() {
			const ctx = document.getElementById('myChart').getContext('2d');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Red', 'Blue', 'Yellow'],
					datasets: [{
						label: 'Votes',
						data: [12, 19, 3],
						backgroundColor: ['red', 'blue', 'yellow']
					}]
				}
			});
		};
	</script>

</body>

</html>