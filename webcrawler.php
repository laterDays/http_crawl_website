<?php
session_start();
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
//header("Content-type: text/plain");
	$BACK_COLOR = '#e9e9e9';
	$CONSOLE_BACK_COLOR = '#ffffff';
	$_SESSION['file_id'] = date('Y-m-d-H-i-s').'rain';
	$OPT=0;
	//$stdout = fopen('php://stdout', 'w');
	if(!empty($_POST['crawl_url'][0]))
	{
		$OPT = 1;
		$crawl_url = $_POST['crawl_url'][0];
		$crawl_depth = $_POST['crawl_url'][1];
		$cmd = "./crawl $crawl_url $crawl_depth > out".$_SESSION['file_id'].".txt 2>&1 &";
		exec("rm out*.txt out_graph*.json; $cmd");
	}
	else if(!empty($_POST['query']))
	{
		$OPT = 2;
		$query = $_POST['query'][0];
		$query_format = $_POST['query'][1];
		if($query_format == "row") 
		{
			$query_format_flag = "-r";
			$query_outfile = "out_row".$_SESSION['file_id'].".json";
			exec("rm out_row*.json;");
		}
		else if ($query_format == "graph")
		{
			$query_format_flag = "-g";
			$query_outfile = "out_graph".$_SESSION['file_id'].".json";
			exec("rm out_graph*.json;");
		}
		else if ($query_format == "row/graph")
		{
			$query_format_flag = "-rg";
			$query_outfile = "out_row".$_SESSION['file_id'].".json out_graph".$_SESSION['file_id'].".json";
			exec("rm out_graph*.json out_row*.json;");
		}
		
		$cmd = "./crawl -q \"$query\" \"$query_format\" $query_format_flag $query_outfile > out".$_SESSION['file_id'].".txt 2>&1 &";
		exec("rm out*.txt; $cmd");
	}
	else if(!empty($_POST['s_values']))
	{
		$OPT = 4;
		$EDGENODE = $_POST['s_values'][0];
		$IDLABELPROPERTIES = $_POST['s_values'][1];
		$PROPERTY = $_POST['s_values'][2];
		$VALUE = $_POST['s_values'][3];
		$query_format = $_POST['s_values'][4];
		if($query_format == "row") 
		{
			$query_format_flag = "-r";
			$query_outfile = "out_row".$_SESSION['file_id'].".json";
			exec("rm out_row*.json;");
		}
		else if ($query_format == "graph")
		{
			$query_format_flag = "-g";
			$query_outfile = "out_graph".$_SESSION['file_id'].".json";
			exec("rm out_graph*.json;");
		}
		else if ($query_format == "row/graph")
		{
			$query_format_flag = "-rg";
			$query_outfile = "out_row".$_SESSION['file_id'].".json out_graph".$_SESSION['file_id'].".json";
			exec("rm out_graph*.json out_row*.json;");
		}

		$cmd = "rm out*.txt out_graph*.json; ./crawl -pq $EDGENODE $IDLABELPROPERTIES '$PROPERTY' '$VALUE' $query_format_flag $query_outfile > out".$_SESSION['file_id'].".txt 2>&1 &";
		exec($cmd);
	}
?>
<html>
<head>
<style type="text/css">
	#container {
		position: absolute;
		left: 330px;
		top: 0px;
		background-color: #ffffff;
		min-width: 700px;
		height: 100%;
		margin: auto;
	}
	BODY, TD, P, DIV {
		font-family: arial,helvetica,sans-serif;
		font-size: 10px;
		color: #333333;
	}
</style>	
</head>
<body style="background-color: <?php echo $BACK_COLOR;?>">
<table width="300px" id="side_panel">
	<tr>
		<div id="session_value"><?php echo $_SESSION['file_id']; ?></div>
		<td width="100%">
			<div id="raw_queries" style="padding: 2px; box-shadow: 0px 2px 5px #888888;">
				<form name="crawl_form" method="post" action="webcrawler.php">
					<input size="20" type="text" value="" name="crawl_url[]"/>
					<input size="4" type="text" value="" name="crawl_url[]"/>
					<input size="10" type="Submit" value="crawl" name="crawl_submit"/>
				</form>
				<form name="query_form" method="post" action="webcrawler.php">
					<!--<input size="33" type="text" value="" name="query[]"/>-->
					<textarea name="query[]" cols="40"><?php echo $query; ?></textarea>
					<select name="query[]">
						<option value="row/graph">row/graph</option>
						<option value="row">row</option>
						<option value="graph">graph</option>
					</select>
					<input size="6" type="Submit" value="run query" name="query_submit"/>
				</form>
			</div>
			<br>
			<div id="pieced_search" style="padding: 2px;box-shadow: 0px 2px 5px #888888;">
				<form name="s_values" method="post" action="webcrawler.php">
					<select name="s_values[]">
						<option value="nodes">nodes</option>
						<option value="edges">edges</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					property &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					value
					<br>
					<select name="s_values[]">
						<option value="''"></option>
						<option value="id">id</option>
						<option value="label">label</option>
						<option value="properties">properties</option>
					</select>
					<input size="10" type="text" value="" name="s_values[]"/>
					<input size="10" type="text" value="" name="s_values[]"/>
					<br>
					<select name="s_values[]">
						<option value="row/graph">row/graph</option>
						<option value="row">row</option>
						<option value="graph">graph</option>
					</select>
					<input size="10" type="Submit" value="run query" name="s_values_submit"/>
				</form>
			</div>
			<br>
			<div style="padding: 2px;border: 1px solid white; width: 300px;box-shadow: 0px 2px 5px #888888;">
				<p style="padding:1px;">
					<?php echo "[$OPT] $cmd"; ?>
				</p>
				<div id="content" style="padding: 2px;border: 1px solid white; background-color: <?php echo $CONSOLE_BACK_COLOR;?>; overflow: scroll; white-space: pre-line">
					
					<!--<?php 
						echo "[]";
						include_once('console.php'); 
					?>-->
				</div>
			</div>

		</td>	
	</tr>
	<tr>
		<td>
 			<button onclick="loadGraph()">REFRESH GRAPH</button> 
		</td>
	</tr>
</table>
<div id="container" style="z-index: 1; box-shadow: 0px 2px 5px #888888;"></div>
<div id="right_panel" style="padding: 2px; position: absolute; left: 1042px; top: 8px;box-shadow: 0px 2px 5px #888888;">
	Selection Information
	<table width="300px">
		<tr>
			<div id="g_info" style="border: 1px solid white; background-color: <?php echo $CONSOLE_BACK_COLOR;?>; width: 300px; height: 70px; overflow: scroll;">

			</div>
		</tr>
		<br>
		<button id="g_detail_graph" style="border: 1px solid white; max-width: 100px;" onclick="refreshDetailGraph ()">
			GRAPH
		</button>
		<button id="g_detail_row" style="border: 1px solid white;max-width: 100px;" onclick="refreshDetailRow ()">
			ROW
		</button>
		<tr>
			<div id="g_detail" style="border: 1px solid white; background-color: <?php echo $CONSOLE_BACK_COLOR;?>; width: 300px; overflow: scroll; white-space: pre-line;">

			</div>
		</tr>
	</table>
</div>
<div id="select_options" class="ui-widget-content" style="z-index: 2; padding: 2px; box-shadow: 0px 2px 5px #888888; position:absolute; background-color: <?php echo $BACK_COLOR;?>">
	<div id="select_options_id"></div>
	<input size="15" id="select_search"></input>
	<div id="select_content"></div>
</div>
<div id="debug_popup"  style="z-index: 3; padding: 2px; box-shadow: 0px 2px 5px #888888; position:absolute; background-color: <?php echo $BACK_COLOR;?>">
	<div id="debug_popup_content"></div>
	<button id="debug_popup_close" onclick="HideMsg()">close</button>
</div>
</body>
<script src="jquery-2.1.1.min.js"></script>
<script src="jquery-ui.js"></script>
<script src="sigma.require.js"></script>
<script src="plugins/sigma.parsers.json.min.js"></script>
<script src="plugins/sigma.layout.forceAtlas2.min.js"></script>
<script src="plugins/sigma.renderers.edgeLabels.min.js"></script>
<script type="text/javascript">

  	/*
		This section is for drawing the graph. It uses the Sigma.js 
		library.
	*/
	$('#select_options').hide();
	$('#debug_popup').hide();
	var sigma_instance;
	var force_on = true;
	function ShowMsg (message)
	{
		var height = $(window).height();
		var width = $(window).width();
		$('#debug_popup').css('left', width / 2);
		$('#debug_popup').css('top', height / 2);
		$('#debug_popup_content').text(message);
		$('#debug_popup').show("fast");
	}
	function HideMsg (message)
	{
		$('#debug_popup').hide();
	}

  	function loadGraph () 
	{
		//http://stackoverflow.com/questions/22543083/remove-all-the-instance-from-sigma-js
		var g = document.querySelector('#container');
		var p = g.parentNode;
		p.removeChild(g);
		var c = document.createElement('div');
		c.setAttribute('id', 'container');
		p.appendChild(c);
		var session_value = $('#session_value').text();
		var graph_file = "out_graph" + session_value + ".json";
	

		//$('#container').empty();
		sigma.parsers.json(
			graph_file, 	// must adjust permissions on this file,
			{			// or it will not load
				renderer: {
					container: document.getElementById('container'),
					type: 'canvas'
				},
				settings: 
				{
					edgeLabelSize: 'proportional',
					defaultNodeColor: '#3febeb',
					defaultLabelColor: '#333333'
				}
			},
			function(s)
			{
				sigma_instance = s;
				var i,
				nodes = s.graph.nodes(),
				len = nodes.length;

				for (i = 0; i < len; i++)
				{
					nodes[i].x = Math.random();
					nodes[i].y = Math.random();
					nodes[i].size = 1 + s.graph.degree(nodes[i].id);
					//nodes[i].color = nodes[i].id;//nodes[i].center ? '#333' : '#666#'
				}
			
				s.refresh();
				s.startForceAtlas2();


				s.bind('clickNode', function(e) 
				{					
					$('#select_options_id').html(
						"id: " + e.data.node.id + "<br>" +
						"labels:" + e.data.node.label);
					var graphHeight = $('#container').width();
					var graphWidth = $('#container').height();
					var x_offset = 50;
					var y_offset = 0;
					var x = (graphWidth * 0.5) + parseInt(e.data.captor.x) + $('#container').offset().left + x_offset;
					var y = (graphHeight * 0.5) + parseInt(e.data.captor.y) + y_offset;

					$('#select_options').css('left', x);
					$('#select_options').css('top', y);
					$('#select_content').load('popup.php', {ID:e.data.node.id});
					$('#select_options').show("fast");
				
				});

				s.bind('clickStage', function(e) 
				{
					$('#select_content').text("");
					$('#select_options').hide();
				});

				s.bind('rightClickStage', function(e) 
				{
					if (force_on)
					{
						force_on = false;
						s.stopForceAtlas2();
					}
					else
					{
						force_on = true;
						s.startForceAtlas2();
					}
				});

				
			}
		);
	}
	loadGraph();
	$(function() {
		$('#select_options').draggable();
	});
	/*
		Set the size of the console. It doesn't like to behave
		so we have to set it this way.
	*/
	var graph_canvas = document.getElementById('container');
	var graph_height = graph_canvas.offsetHeight;	// get actual height using .offsetHeight
							// .height is undefined
	var contentDiv = document.getElementById('content');
	contentDiv.style.height = graph_height - 350;
	var infoDiv = document.getElementById('g_detail');
	infoDiv.style.height = graph_height - 150;
	
	
	var hover_over = false;		
	$('#content').mouseover(function () 				// for pausing log output 
	{	
		hover_over = true;
		$('#content').css("border", "1px dashed green");
	});
	$('#content').mouseout(function () 
	{
		hover_over = false;
		$('#content').css("border", "1px solid white");
	});
	
	var g_detail_mode = "graph";
	var hover_over_g_detail = false;
	$('#g_detail').mouseover(function () 
	{
		hover_over_g_detail = true;
		$('#g_detail').css("border", "1px dashed black");
	});
	$('#g_detail').mouseout(function () 
	{
		hover_over_g_detail = false;
		$('#g_detail').css("border", "1px solid white");
	});

	$('#g_detail_graph').click(function () 				// show graph info details
	{
		g_detail_mode = "graph";
		refreshGraphInfo();
		$('#g_detail_graph').css("border", "1px solid green");
		$('#g_detail_row').css("border", "1px solid white");
	});
	$('#g_detail_row').click(function () 				// show row info details
	{
		g_detail_mode = "row";
		refreshGraphInfo();
		$('#g_detail_graph').css("border", "1px solid white");
		$('#g_detail_row').css("border", "1px solid red");
	});


	var console_lines = 0;
	var console_lines_prev = 0;

	setInterval(refreshInfo, 100);
	function refreshInfo ()
	{
		//if (console_lines != console_lines_prev)
		//{
			if(!hover_over){refreshConsole();}
			//if(!hover_over_g_detail){refreshGraphInfo();}
		//}
	}
	function refreshConsole ()
	{
		//$('#content').load('console.php');
		var session_value = $('#session_value').text();
		var console_file = "out" + session_value + ".txt";
		$.ajax({
			url : console_file,
			dataType : "text",
			success : function (data)
			{
				
				console_lines_prev = console_lines;
				console_lines = data.length;
				if (console_lines != console_lines_prev)
				{
					data = data + "[" + console_lines + "/" + console_lines_prev + "]";
					$('#content').html(data);
				}
			}
		});
		var objDiv = document.getElementById("content");
		objDiv.scrollTop = objDiv.scrollHeight;
	}
	refreshConsole();

	function refreshDetailGraph ()
	{
		var session_value = $('#session_value').text();
		var detail_file = "out_graph" + session_value + ".json";
		$('#g_detail').css("border", "1px solid green");
		$.ajax({
			url : detail_file,
			dataType : "text",
			success : function (data)
			{
				$('#g_detail').html(data);
			}
		});
	}

	function refreshDetailRow ()
	{
		var session_value = $('#session_value').text();
		var detail_file = "out_row" + session_value + ".json";
		$('#g_detail').css("border", "1px solid red");
		$.ajax({
			url : detail_file,
			dataType : "text",
			success : function (data)
			{
				$('#g_detail').html(data);
			}
		});
	}
	
</script>
</html>
