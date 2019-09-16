<?
include 'middle.php';
include ("graph/jpgraph.php");
include ("graph/jpgraph_line.php");
include ("graph/jpgraph_bar.php");
include ("graph/jpgraph_pie.php");
include ("graph/jpgraph_pie3d.php");

$ydata = array();
$datax = array();

$graph = $_GET['graph'];

////////////////////////////////
switch ($graph) {
default:
////////////////////////////////

$rs = mysql_query('SELECT COUNT(*) AS cnt, YEARWEEK(FROM_UNIXTIME(joined),1) AS wk FROM users GROUP BY wk ORDER BY joined DESC LIMIT 20');
if (mysql_num_rows($rs) > 0) {
  for ($i=mysql_num_rows($rs)-1;$i>=0;$i--) {
    mysql_data_seek($rs,$i);
    $row = mysql_fetch_array($rs);
    $ydata[] = $row['cnt'];
    $datax[] = substr($row['wk'],4);
  }
}

// Create the graph. These two calls are always required
$graph = new Graph(350,200,"auto");
$graph->SetScale("textlin");

$graph->SetMarginColor('#fbf6ed');
$graph->SetFrame(true, '#fbf6ed');
$graph->img->SetMargin(40,20,20,40);
$graph->SetColor('#edebd4');

$graph->ygrid->Show(true,true);
$graph->ygrid->SetColor('#cbc9b2');
$graph->xgrid->SetColor('#cbc9b2');

// Specify the tick labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->HideTicks(true,true); 

// Create the linear plot
$lineplot=new LinePlot($ydata);
//$lineplot->SetWidth(0.8);
//$lineplot->SetFillColor('#cbc9b2');

// Add the plot to the graph
$graph->Add($lineplot);

$graph->xaxis->title->Set("Week Number");
$graph->yaxis->title->Set("Registrations");

////////////////////////////////
break;
case 'hits':
////////////////////////////////

$rs = mysql_query('SELECT * FROM pagehits');
if (mysql_num_rows($rs) > 0) {
  while ($row = mysql_fetch_array($rs)) {
    
	$starthit = $row['hitstart'];
	$thenow = gmt("U");
	$hittime = ceil(($thenow - $starthit) / 86400);
	$average = 0;
	if ($hittime != 0) $average = round(($row['hits'] / $hittime),2);
	$data[$row['hitpage']] = $average;
  }
}

arsort($data);
$data = array_slice($data,0,20);

// Create the graph. These two calls are always required
$graph = new Graph(460,300,"auto");
$graph->SetScale("textlin");

$graph->SetMarginColor('#fbf6ed');
$graph->SetFrame(true, '#fbf6ed');
$graph->SetColor('#edebd4');
$graph->Set90AndMargin(100,20,20,20);

$graph->ygrid->Show(true,true);
$graph->ygrid->SetColor('#cbc9b2');
$graph->xgrid->SetColor('#cbc9b2');

// Specify the tick labels
$graph->xaxis->SetTickLabels(array_keys($data));
$graph->xaxis->HideTicks(true,true); 
$graph->xaxis->SetFont(FF_FONT0); 

$graph->yaxis->SetPos('max');
$graph->yaxis->SetLabelSide(SIDE_RIGHT);
$graph->yaxis->SetTickSide(SIDE_LEFT);
$graph->yaxis->SetLabelMargin(15);

// Create the linear plot
$lineplot=new BarPlot(array_values($data));
$lineplot->SetWidth(1);
$lineplot->SetFillColor('#cbc9b2');

// Add the plot to the graph
$graph->Add($lineplot);

////////////////////////////////
break;
case 'uphits':
////////////////////////////////

$rs = mysql_query("SELECT uid AS Username, FROM_UNIXTIME(joined,'%Y-%m-%d') AS AddDate, stat_profilehits AS ProfileViews FROM users");
if (mysql_num_rows($rs) > 0) {
  while ($row = mysql_fetch_array($rs)) {
    
    $timeleft = time();
    if ($row["AddDate"] == "0000-00-00") {
      $starttime = time();
    }
    else {
      $starttime = strtotime($row["AddDate"]);
    }
    $timeleft = ($timeleft - $starttime) / 86400;
    $timeleft = ceil($timeleft);
    if ($timeleft != 0) {
      $pday = round($row["ProfileViews"] / $timeleft,2);
    }
    else {
      $pday = 0;
    }
    
    $data[substr($row['Username'],0,strlen($row['Username']))] = $pday;
  }
}

arsort($data);
$data = array_slice($data,0,20);

// Create the graph. These two calls are always required
$graph = new Graph(460,300,"auto");
$graph->SetScale("textlin");

$graph->SetMarginColor('#fbf6ed');
$graph->SetFrame(true, '#fbf6ed');
$graph->SetColor('#edebd4');
$graph->Set90AndMargin(100,20,20,20);

$graph->ygrid->Show(true,true);
$graph->ygrid->SetColor('#cbc9b2');
$graph->xgrid->SetColor('#cbc9b2');

// Specify the tick labels
$graph->xaxis->SetTickLabels(array_keys($data));
$graph->xaxis->HideTicks(true,true); 
$graph->xaxis->SetFont(FF_FONT0); 

$graph->yaxis->SetPos('max');
$graph->yaxis->SetLabelSide(SIDE_RIGHT);
$graph->yaxis->SetTickSide(SIDE_LEFT);
$graph->yaxis->SetLabelMargin(15);

// Create the linear plot
$lineplot=new BarPlot(array_values($data));
$lineplot->SetWidth(1);
$lineplot->SetFillColor('#cbc9b2');

// Add the plot to the graph
$graph->Add($lineplot);

////////////////////////////////
break;
case 'logins':
////////////////////////////////

$rs = mysql_query("SELECT uid AS Username, FROM_UNIXTIME(joined,'%Y-%m-%d') AS AddDate, log AS Logins FROM users",$dbh);
if (mysql_num_rows($rs) > 0) {
  while ($row = mysql_fetch_array($rs)) {
    
    $timeleft = time();
    if ($row["AddDate"] == "0000-00-00") {
      $starttime = time();
    }
    else {
      $starttime = strtotime($row["AddDate"]);
    }
    $timeleft = ($timeleft - $starttime) / 86400;
    $timeleft = ceil($timeleft);
    if ($timeleft != 0) {
      $pday = round($row["Logins"] / $timeleft,2);
    }
    else {
      $pday = 0;
    }
    
    $data[substr($row['Username'],0,strlen($row['Username']))] = $pday;
  }
}

arsort($data);
$data = array_slice($data,0,20);

// Create the graph. These two calls are always required
$graph = new Graph(460,300,"auto");
$graph->SetScale("textlin");

$graph->SetMarginColor('#fbf6ed');
$graph->SetFrame(true, '#fbf6ed');
$graph->SetColor('#edebd4');
$graph->Set90AndMargin(100,20,20,20);

$graph->ygrid->Show(true,true);
$graph->ygrid->SetColor('#cbc9b2');
$graph->xgrid->SetColor('#cbc9b2');

// Specify the tick labels
$graph->xaxis->SetTickLabels(array_keys($data));
$graph->xaxis->HideTicks(true,true); 
$graph->xaxis->SetFont(FF_FONT0); 

$graph->yaxis->SetPos('max');
$graph->yaxis->SetLabelSide(SIDE_RIGHT);
$graph->yaxis->SetTickSide(SIDE_LEFT);
$graph->yaxis->SetLabelMargin(15);

// Create the linear plot
$lineplot=new BarPlot(array_values($data));
$lineplot->SetWidth(1);
$lineplot->SetFillColor('#cbc9b2');

// Add the plot to the graph
$graph->Add($lineplot);

////////////////////////////////
break;
case 'browsers':
////////////////////////////////

$ffhits = mysql_num_rows(mysql_query("SELECT * FROM users WHERE lastbrowser='Firefox'"));
$ophits = mysql_num_rows(mysql_query("SELECT * FROM users WHERE lastbrowser='Opera'"));
$ie7hits = mysql_num_rows(mysql_query("SELECT * FROM users WHERE lastbrowser='IE 7'"));
$ie6hits = mysql_num_rows(mysql_query("SELECT * FROM users WHERE lastbrowser='IE 1-6'"));

$bdata = array($ffhits,$ophits,$ie7hits,$ie6hits);

$graph = new PieGraph(460,120,"auto");

$graph->SetAntiAliasing(true);
$graph->SetColor('#edebd4');

$graph->SetMarginColor('#fbf6ed');
$graph->SetFrame(true, '#fbf6ed');

$p1 = new PiePlot3d($bdata);
$p1->SetTheme("sand");
$p1->SetCenter(0.4);
$p1->SetAngle(45);
$p1->SetLegends(array("Firefox","Opera","IE 7","IE 1-6"));

$graph->Add($p1);

////////////////////////////////
break;
}
////////////////////////////////

// Display the graph
$graph->Stroke();


?>