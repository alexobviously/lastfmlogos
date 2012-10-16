<?php
include_once("Config.class.php");
$logfile = null;
function _log($txt,$lvl)
{
	global $logfile; // So it only needs to be opened once per script execution
	if(!Config::ENABLE_LOG||Config::LOG_LEVEL<$lvl) return false;
	if($logfile==null)
		$logfile = fopen("Log.txt","a");
	fwrite($logfile,$txt."\n");
	return true;
}
function clear_log()
{
	$f = fopen("Log.txt","w");
	fclose($f);
}
?>