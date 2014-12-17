<?php
//header("Content-type: text/plain");

	$INSTANCE = 1;
	$stdout = fopen('php://stdout', 'w');
	if(isset($_POST['crawl_url'][0]))
	{
		$crawl_url = $_POST['crawl_url'][0];
		$crawl_depth = $_POST['crawl_url'][1];
		//exec("./crawl $crawl_url 0"." > out".$INSTANCE.".txt &");
		$cmd = "./crawl $crawl_url $crawl_depth > out".$INSTANCE.".txt &";
		exec($cmd);
	}
	else if(isset($_POST['query']))
	{
		$query = $_POST['query'];
		//exec("./crawl -c \"$query\" \"graph\""." > out".$INSTANCE.".txt &");
		$cmd = "./crawl -c \"$query\" \"graph\" > out".$INSTANCE.".txt &";
		exec($cmd);
	}

?>
<html>
<head>
<style type="text/css">
	#container {
		background-color: #212121;
		max-width: 100%;
		height: 80%;
		margin: auto;
	}
	BODY, TD, P {
		font-family: arial,helvetica,sans-serif;
		font-size: 14px;
		color: #ffffff;
	}
</style>	
</head>
<body style="background-color: #171717">
<div id="container"></div>
<script src="jquery-2.1.1.min.js"></script>
<script src="sigma.min.js"></script>
<script src="sigma.parsers.json.min.js"></script>
<script src="sigma.layout.forceAtlas2.min.js"></script>
<script>

	$(document).ready(function()
	{
		$('#refresh_submit').on('vclick', function()
		{
			event.preventDefault();

			$(".ui-loader").show(); 
			$.ajax(
			{
				type: 'POST',
				url: 'url',
				data: 'data',
				dataType: "json",
				success: function(data) 
				{
					alert('Form successfully submitted!');
					$("#content")[0].reset();
					$("#myForm")[0].reset();
					$(".ui-loader").hide();
				}
			});
		});
	});
/*
		$(document).ready(function()
		{
			$.ajaxSetup(
			{
				cache: false,
				beforeSend: function()
				{
					$('#content').hide();
					$('#loading').show();
				},
				complete: function()
				{
					$('#loading').hide();
					$('#content').show();
				},
				success: function()
				{
					$('#loading').hide();
					$('#content').show();
				}
			});
			var $container = $("#content");
			$container.load("console.php");
			//var refreshId = setInterval(function()
			//{
				$container.load('console.php');
			//}, 9000);
		});
	})(jQuery);*/

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
		<td width="30%">
			<form name="refresh" method="POST" id="refresh_form">
				<input id="refresh_submit" type="Submit" value="Refresh">
			</form>
			<form name="crawl_form" method="POST" action="webcrawler.php">
				<input width="60%" type="text" value="" name="crawl_url[]"/>
				<input width="60%" type="text" value="" name="crawl_url[]"/>
				<input width="20%" type="Submit" value="url ->" name="crawl_submit"/>
			</form>
			<form name="query_form" method="POST" action="webcrawler.php">
				<input type="text" value="" name="query"/>
				<input type="Submit" value="query ->" name="query_submit"/>
			</form>
			<select>
				<option value="1">1</option>
			</select>
		</td>
		<td width="70%">
			<div id="content" style="width: 600px; height: 150px; overflow: scroll;">
				<?php include_once('console.php'); ?>
			</div>
			<div id="loading">Loading</div>
		</td>
	
	</tr>
</table>
</body>
<script>
	window.setInterval(function()
	{
		var objDiv = document.getElementById("content");
		objDiv.scrollTop = objDiv.scrollHeight;
	}, 200);
</script>
</html>
