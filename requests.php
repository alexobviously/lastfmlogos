<?php
include_once("classes/DatabaseCache.class.php");
if(Config::ENABLE_REQUESTS)
{
	$db = Config::$dbInstance;
	$sql = "select * from lastfm_requests order by requests desc limit 0,500";
	$result = $db->execQuery($sql);
	$output = '<table width="421">
		<tr>
		<td width="281"><nobr><strong>Artist</strong></nobr></td> <td width="128"><strong>Times Requested</strong></td>
		</tr>';
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		$output .= sprintf('<tr><td width="281"><nobr>%s</nobr></td> <td width="128">%s</td></tr>', $row[1], $row[2]);
	}
	$output .= "</table>";
}
else $output = "Requested logos functionality disabled.";
?>

<html>
<head>
	<title>Band Logos - Most Requested Logos</title>

	<link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>


<h2>Band Logos - Most Requested Logos</h2>


<?php echo($output); ?>

</body>
</html>