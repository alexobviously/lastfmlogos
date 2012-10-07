<?php
include_once('classes/Config.class.php');
include_once('classes/Artists.class.php');
for($i=40;$i<=50;$i++)
{
	echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=yellow&layout=OneCol&blackbg=on">');
	echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=lightblue&layout=TwoCols&blackbg=on">');
	echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=green&layout=OneCol&blackbg=on">');
	echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=red&layout=TwoCols">');
}
/*function a($b)
{
	if($b>5)
		return $b*2;
	else
		return false;
}
for($i=0;$i<9;$i++)
{
	if(($c=a($i)))
		echo("B".$i.", ");
	else
		echo("A, ");
}*/
/*function getLogo($name)
{
	$search = glob("logos/".$name.".{png,gif,jpg,jpeg}",GLOB_BRACE);
	if(sizeof($search)>0)
		return (string)$search[0];
	else return false;
}
$db = Config::$dbInstance;
$sql = "SELECT * ".
			"FROM lastfm_artists ".
			"ORDER BY name ASC";
$it = $db->execQueryIterator($sql,array());
$artists = array();
while($line = $it->getNext()) {
	$artists[] = array($line['name'],$line['path_logo']);
}
foreach($artists as $artist)
{
	$a = getLogo($artist[0]);
	$b = "logos/".$artist[0];
	$parts = explode('.',$a);
	$ext = array_pop($parts);
	$a = join(".",$parts);
	if($a===$b) $c = true; else $c = false;
	if(!$c) echo(($c?"":"<b>").$b." || ".$a." || ".($c?"yes":"no</b>")."<br>");
}*/
?>