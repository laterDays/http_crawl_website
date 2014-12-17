<html>
<head>
<style type="text/css">
	#container {
		background-color: #212121;
		max-width: 100%;
		height: 80%;
		margin: auto;
	}
	#text_norm {
		color: #ffffff;
	}
</style>	
</head>
<body style="background-color: #171717">
<div id="container"></div>
<script src="sigma.min.js"></script>
<script src="sigma.parsers.json.min.js"></script>
<script src="sigma.layout.forceAtlas2.min.js"></script>
<script>
   // Create new Sigma instance in graph-container div (use your div name here) 
  	sigma.parsers.json(
		'out_graph.json', 
		{
			container: 'container',
			settings: 
			{
				defaultNodeColor: '#3febeb',
				defaultLabelColor: '#ffffff'
			}
		},
		function(s)
		{
			var i,
			nodes = s.graph.nodes(),
			len = nodes.length;

			for (i = 0; i < len; i++)
			{
				nodes[i].x = Math.random();
				nodes[i].y = Math.random();
				nodes[i].size = s.graph.degree(nodes[i].id);
				//nodes[i].color = nodes[i].id;//nodes[i].center ? '#333' : '#666#'
			}
			
			s.refresh();

			s.startForceAtlas2();
		}
	);
</script>
<table>
	<tr>
		<td>
			<form name="crawl_form" method="POST" action="webcrawler.php">
				<input type="text" value="Url" name="crawl_url">
				<input type="Submit" value="Submit" name="crawl_submit">
			</form>
			<form name="query_form" method="POST" action="webcrawler.php">
				<input type="text" value="MATCH (a) RETURN a" name="query">
				<input type="Submit" value="Submit" name="query_submit">
			</form>
			<select>
				<option value="1">1</option>
			</select>
		</td>
	</tr>
</table>
<?php
	$INSTANCE = 1;
	$stdout = fopen('php://stdout', 'w');
	if(isset($_POST['crawl_url']))
	{
		$crawl_url = $_POST['crawl_url'];
		exec("./crawl $crawl_url 0"." > out".$INSTANCE.".txt &");
	}
	else if(isset($_POST['query']))
	{
		$query = $_POST['query'];
		exec("./crawl -c $query \"graph\"");
	}
?>
</body>
</html>
