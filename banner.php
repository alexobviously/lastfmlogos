<?php

error_reporting(E_ALL);

$_PATH['classes'] = 'classes/';
include_once($_PATH['classes'].'CrawlerLastFM.class.php');
include_once($_PATH['classes'].'OneColumnLayout.class.php');
include_once($_PATH['classes'].'TwoColumnsLayout.class.php');
include_once($_PATH['classes'].'DatabaseCache.class.php');
include_once($_PATH['classes'].'Errors.class.php');
include_once($_PATH['classes'].'Config.class.php');
include($_PATH['classes'].'util/colors.php');
include('bans.php');
for($i=0;$i<sizeof($bans);$i++)
{
	$bans[$i] = strtolower($bans[$i]);
}

if(in_array(strtolower($_GET['user']),$bans)) 
{
	header("Location: banned.png");
	exit;
}

//BANNER GENERATION LIMITING
$start = microtime(true);
$curgen = Config::MAX_GENERATIONS+1;
while($curgen>Config::MAX_GENERATIONS)
{
	$db = Config::$dbInstance;
	$sqlres = $db->execQuery("select count(`time`) as c from lastfm_generations");
	$curgen = mysql_result($sqlres,0,"c");
	sleep(Config::WAIT_TIME);
	if(rand(0,9)==1)
		$db->execQuery("delete from lastfm_generations where ".(int)microtime(true)."<`time`-60");
}
$db->execQuery("INSERT INTO  `lastfm_generations` (`time`) VALUES (".(int)$start.");",array());

header("Content-type: image/jpeg");
header("Author: h3xStream");

//Errors handler
ob_start();
set_error_handler(array('Errors','catchError'));
set_exception_handler(array('Errors','catchException'));

//--Initialisation
//Inputs
$user = isset($_GET['user'])?$_GET['user']:'';
$nb = isset($_GET['nb'])?$_GET['nb']:10; //Default 10 artists
$type = isset($_GET['type'])?$_GET['type']:'overall';
$color = isset($_GET['color'])?$_GET['color']:'white';
$layout = isset($_GET['layout'])?$_GET['layout']:'OneCol';
$bbg = (isset($_GET['blackbg'])&&$_GET['blackbg']=='on')?true:false;

$cacheDelay = Config::CACHE_DELAY; //Period to keep in cache

////Validations
//Checking for invalid number
/*if(!($nb == 5 || $nb == 10 || $nb == 15 || $nb == 20 || $nb == 25))
	$nb = Config::DEFAULT_BANNER_NB;*/
if($nb>50) $nb = 50;
if($nb<1) $nb = 1;

//Checking for invalid type
if(!($type == '3month' || $type == '6month' ||	$type == '12month' ||
	$type == 'overall'))
	$type = Config::DEFAULT_BANNER_TYPE;

//Colors..
/*if(!($color == 'white' || $color == 'black' || $color == 'gray' || 
	$color == 'blue' || $color == 'red' || $color == 'orange' || 
	$color == 'turquoise' || $color == 'trans'))
	$color = Config::DEFAULT_BANNER_COLOR;*/
if(!($color == 'white'||$color == 'black'||$color == 'gray'||in_array($color,$cnames)||$color == 'trans'))
	$color = Config::DEFAULT_BANNER_COLOR;
	
//Layouts
if(!($layout == 'OneCol' || $layout == 'TwoCols'))
	$layout = Config::DEFAULT_BANNER_LAYOUT;
	
//Is there a cached version of the banner on this server?
$_fh = fopen("log.txt","a");
$_ban = $user.'_'.$nb.'_'.$type.'_'.$color.'_'.$layout.($bbg?"_b":"");
$cachefile = 'cache/'.$_ban.'.png';
fwrite($_fh,"\n\n".$_ban);
if(file_exists($cachefile))
{
	fwrite($_fh,"\nRedirecting to: ".$cachefile);
	header("Location: ".$cachefile);
	exit;
}

//Slaves
if(ENABLE_SLAVES)
{
	//Is there a cached version of the banner on a slave server?
	if(($sc=checkSlaveCache($_ban)))
	{
		fwrite($_fh,"\nSC: ".($sc=checkSlaveCache($_ban)));
		fwrite($_fh,"\nRedirecting to: ".$sc."/cache/".$_ban.".png");
		fclose($_fh);
		header("Location: ".$sc."/cache/".$_ban.".png");
		exit;
	}
	
	//Pick a host to generate the new banner on
	$h = rand(0,$total_weight-1);
	fwrite($_fh,"\nRand: ".$h.", Host:".$hosts[$h]);
	if($hosts[$h]!=false)
	{
		fwrite($_fh,"\nRedirecting to: ".$hosts[$h]."/banner.php?user=".$user."&nb=".$nb."&type=".$type."&color=".$color."&layout=".$layout.($bbg?"&blackbg=on":""));
		fclose($_fh);
		header("Location: ".$hosts[$h]."/banner.php?user=".$user."&nb=".$nb."&type=".$type."&color=".$color."&layout=".$layout.($bbg?"&blackbg=on":""));
		addSlaveCache($hosts[$h],$_ban);
		exit;
	}
}
fclose($_fh);

////Cache initialisation
//Mostly obsolete with new file and slave caching but I'll leave it here for now
$cacheHandler = new DatabaseCache();

$cacheHandler->setUser($user);
$cacheHandler->setNbArtists($nb);
$cacheHandler->setPeriodType($type);
$cacheHandler->setColor($color);
$cacheHandler->setbbg($bbg);

////Layout selection

switch ($layout) {
	case 'TwoCols':
		$layout = new TwoColumnsLayout();
		break;
	default:
	case 'OneCol':
		$layout = new OneColumnLayout();
		break;
}

////Output process
//header("Expires: ".(gmdate("D, d M Y H:i:s", time() + $cacheDelay))." GMT");
$cacheHandler->setLayout($layout);
$cacheHandler->generate();

//ob_start("ob_gzhandler"); //Optionnal
$cacheHandler->outputResult();

$db->execQuery('DELETE FROM  `lastfm_generations` WHERE `time` = '.(int)$start.';',array());
Config::$dbInstance->disconnect();

?>