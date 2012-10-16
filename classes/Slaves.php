<?php
//---------------
// Configuration
//---------------
const SERVER_TYPE = 1; // 1 = master, 2 = slave
const ENABLE_SLAVES = true;
const MASTER_WEIGHT = 2;
$slave_array = array(
			array("url"=>"http://localhost/lfl/slave","weight"=>1),
			array("url"=>"http://localhost/lfl/slave2","weight"=>1)
			);
			
//Authorisation
const AUTH1 = "banana"; // Make sure these two codes are the same on your slaves
const AUTH2 = "8xcme02a";
			
//---------------
// Code
//---------------
//define("ENABLE_SLAVES",(SERVER_TYPE==1&&$ENABLE_SLAVES)?true:false);
if(ENABLE_SLAVES)
	$slaves = $slave_array;
else $slaves = array();
$total_weight = MASTER_WEIGHT;
foreach($slaves as $slave) $total_weight += $slave['weight'];
$hosts = array();
for($i=0;$i<MASTER_WEIGHT;$i++) $hosts[] = false;
foreach($slaves as $slave)
	for($i=0;$i<$slave['weight'];$i++) $hosts[] = $slave['url'];
	
function checkSlaveCache($banner)
{
	$db = Config::$dbInstance;
	$sql = "select `slave` from lastfm_slavecache where banner=?";
	$values = array($banner);
	$it = $db->execQuery($sql,$values);
	if(mysql_num_rows($it)<1)
		return false;
	else
		return mysql_result($it,0,"slave");
}
function addSlaveCache($slave,$banner)
{
	$db = Config::$dbInstance;
	$sql = "insert into lastfm_slavecache (`slave`, `banner`) VALUES (?, ?);";
	$values = array($slave,$banner);
	$db->execQuery($sql,$values);
}
function emptySlaveCache($_s)
{
	$db = Config::$dbInstance;
	$_cl = array();
	$sql = "select count(`slave`) as n from lastfm_slavecache";
	$it = $db->execQuery($sql,array());
	$_cl[0] = mysql_result($it,0,"n");
	$db = Config::$dbInstance;
	$sql = "truncate table lastfm_slavecache";
	$db->execQuery($sql,array());
	$_cl[1] = array();
	foreach($_s as $slave)
	{
		$head = get_headers($slave['url'].'/remote.php');
		if(strpos($head[0],"404")===false)
			$_cl[1][] = array($slave['url'],file_get_contents($slave['url'].'/remote.php?a='.AUTH1."&b=".AUTH2."&c=ec"));
		else $_cl[1][] = array($slave['url'],"E404");
	}
	return $_cl;
}
function slaveStatus($slave)
{
	$head = get_headers($slave['url'].'/remote.php?a='.AUTH1."&b=".AUTH2, 1);
	if(strpos($head[0],"404")===false)
		$m = file_get_contents($slave['url'].'/remote.php?a='.AUTH1."&b=".AUTH2);
	else $m = "E404";
	return $m;
}

/*print_r($hosts);
echo(sizeof($hosts)."<BR>");
for($i=0;$i<50;$i++)
{
	$x = rand(0,$total_weight-1);
	if($hosts[$x]!=false) echo($hosts[$x]."<br>");
}*/
?>