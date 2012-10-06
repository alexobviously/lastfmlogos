<?php
include('classes/Config.class.php');
echo("<u>Wait time: ".Config::WAIT_TIME."</u><br>--Start--<br><table>");
$s = microtime();
for($i=0;$i<180;$i++)
{
	$db = Config::$dbInstance;
	$sqlres = $db->execQuery("select count(`time`) as c from lastfm_generations");
	$curgen = mysql_result($sqlres,0,"c");
	$load = sys_getloadavg();
	$now = microtime();
	echo($i." : ".(($now-$s)*1000)." : ".$curgen." : ".$load[0]." : ".$load[1]."<br>");
	sleep(Config::WAIT_TIME);
}
echo("</table>--End--");
?>