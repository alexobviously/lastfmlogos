<?php
error_reporting(E_ALL);
$_PATH['classes'] = 'classes/';
include_once($_PATH['classes'].'Artists.class.php');
include_once($_PATH['classes'].'Config.class.php');

$nbInsert = 0;

function artistExist($name) {
	
	$db = Config::$dbInstance;
	
	//Manual query to avoid loading all the column
	$sql = "SELECT name "."FROM lastfm_artists where name = ?";
	
	$it = $db->execQueryIterator($sql,array($name));
	if($line = $it->getNext()) {
		return true;
	}
	else {
		return false;
	}
}

function loadLogos($dir)
{
	if(!is_dir($dir))
	{
		echo "> Invalid directory\n";
	}
	else
	{
		global $nbInsert;
		global $_PATH;
		
		if($handle = opendir($dir))
		{
			while(($file = readdir($handle)) !== false)
			{
				///---Extraction
				$parts = explode('.',$file);
				$ext = array_pop($parts);
				
				///---Valid formats
				if(!($ext == 'gif' || $ext == 'jpg' || $ext == 'png'))
					continue;
				
				///---Name repack
				$name = join(".",$parts);
				$name = str_replace('__','.',$name);
				$name = str_replace('_','%',$name);
								
				///---Extra Validations
				
				//Check for special caracters not encoded
				if(strpos($name,'_') !== false || strpos($name,' ') !== false)
				{
					echo "> ".$name." should be renamed to ".
						str_replace(array('_',' '),array('+','+'),$name)."\n";
					continue;
				}
				
				else if(strpos($name,'%') === false){
					$nameCheck = urlencode(str_replace(array('+'),array(''),$name)); //Char excludes
					
					//Some caracter are not support yet
					if(strpos($nameCheck,'%') !== false){
						echo "> ".$name." was not properly parsed\n";
						continue;
					}
				}
				
				$prev = $name;
				
				///---Insert in db
				
				if(artistExist($name)) {
					continue;
				}
				
				//echo $name." - ";
				
				$newDate=filemtime(Config::FOLDER_LOGOS.$file); //Date of modification (new file)
				$newArtist = new Artist($name,$file,"",-1,$newDate);
				Artists::addArtist($newArtist);
				if(Config::ENABLE_REQUESTS) Artists::deleteRequest($name);
				$nbInsert++;
				
			}
			closedir($handle);
		}
	}
	return $nbInsert;
}
function emptycache()
{
	$start = microtime(true);
	$x = 0;
	foreach(new DirectoryIterator('cache') as $files)
	{
		if(!$files->isDot()) // Don't delete .files!
		{
			unlink("cache/".$files->getFilename());
			$x++;
			if($x%500==0) sleep(1); //Pause every now and then for anti-crashing purposes
		}
	}
	$end = microtime(true);
	echo $x." cached banners deleted in ".round(($end - $start),3)." seconds";
}
function filldb()
{
	//--Ini
	set_time_limit(360);
	$start = microtime(true);
	
	//--Fill db base on the file this folder
	$nbInsert = loadLogos(Config::FOLDER_LOGOS);
	
	$end = microtime(true);
	echo $nbInsert." artists inserted in ".round(($end - $start),3)." seconds";
}
function clearlist()
{
	Artists::emptyList();
	echo "Artists list cleared";
}
function bans()
{
	include('bans.php');
	$o = "Banned users: ";
	for($i=0;$i<sizeof($bans);$i++)
	{
		$o .= $i<sizeof($bans)-1?$bans[$i].", ":$bans[$i];
	}
	echo $o;
}
function ban($b)
{
	include('bans.php');
	$bans[] = $b;
	$o = "";
	for($i=0;$i<sizeof($bans);$i++)
	{
		$o .= $i<sizeof($bans)-1?"'".$bans[$i]."', ":"'".$bans[$i]."'";
	}
	$f = fopen('bans.php',"w+");
    fwrite($f,"<?php\n\$bans = array(".$o.");\n?>");
	fclose($f);
	echo "Banned ".$b;
}
function unban($b)
{
	include('bans.php');
	if(in_array($b,$bans))
	{
		//$bans[] = $b;
		$o = "";
		for($i=0;$i<sizeof($bans);$i++)
		{
			if($bans[$i]!=$b) $o .= $i<sizeof($bans)-1?"'".$bans[$i]."', ":"'".$bans[$i]."'";
		}
		$f = fopen('bans.php',"w+");
		fwrite($f,"<?php\n\$bans = array(".$o.");\n?>");
		fclose($f);
		echo "Unbanned ".$b;
	}
	else echo $b." wasn't banned anyway!";
}
function clearrequests()
{
	Artists::clearRequests();
	echo "Requests cleared";
}
function slaves()
{
	if(ENABLE_SLAVES)
		echo("Slaves are enabled on this server");
	else echo("Slaves are disabled on this server");
}
function _emptySlaveCache($slaves)
{
	if(ENABLE_SLAVES)
	{
		$cl = emptySlaveCache($slaves);
		echo("Deleted ".$cl[0]." entries from slavecache table||");
		foreach($cl[1] as $_cl) echo("Cleared ".$_cl[1]." banners on slave ".$_cl[0]."||");
	}
	else echo("Slaves are disabled on this server");
}
function analysis()
{
	$db = Config::$dbInstance;
	$sqlres = $db->execQuery("select count(`time`) as c from lastfm_generations");
	$curgen = mysql_result($sqlres,0,"c");
	$load = sys_getloadavg();
	echo($curgen." : ".round($load[0],3)." : ".round($load[1],3));
}
function adminpage()
{
	include('adminform.php');
}
$c = isset($_GET['c'])?$_GET['c']:"console";
switch($c)
{
	case "emptycache":
	case "ec":
		emptycache();
		break;
	case "filldb":
		filldb();
		break;
	case "clearlist":
		clearlist();
		break;
	case "bans":
		bans();
		break;
	case "ban":
		ban($_GET['v']);
		break;
	case "unban":
		unban($_GET['v']);
		break;
	case "clearrequests":
	case "cr":
		clearrequests();
		break;
	case "slaves":
		slaves();
		break;
	case "emptyslavecache":
	case "esc":
		_emptySlaveCache($slaves);
		break;
	case "analysis":
	case "ana":
		analysis();
		break;
	case "console":
		adminpage();
		break;
	default:
		echo "Invalid command";
		break;
}
?>