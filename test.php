<?php
/*$a = "adfdasg";
$b = explode("_",$a);
echo($b[0].", ".$b[1].", ".strlen($b[0]).", ".strlen($b[1]));*/

/*exec('ps -A | grep filldb.php', $results);
print_r($results);*/

/*include_once('classes/Config.class.php');
$db = Config::$dbInstance;
for($i=0;$i<100;$i++)
{
$r = $db->execQuery("SELECT (UNIX_TIMESTAMP()-".microtime(true).");");
echo(mysql_result($r,0).", ");
}
//$db->execQuery("DELETE FROM `lastfm_generations` WHERE `time`<UNIX_TIMESTAMP()-300",array());*/

for($i=0;$i<5;$i++)
{
	echo(microtime()." ");
	sleep(1);
}
?>