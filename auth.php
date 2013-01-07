<?php
$_PATH['classes'] = 'classes/';
include_once($_PATH['classes'].'Config.class.php');

$admin = isset($_GET['admin'])?true:false;
$isadmin = false;

if(isset($_COOKIE['user']) && isset($_COOKIE['pass']))
{
    $db = Config::$dbInstance;
	$sql = "select * from lastfm_users where `user` = '".$_COOKIE['user']."'";
	$res = $db->execQuery($sql);
	$nr = mysql_num_rows($res);
	if($nr==0)
	{
		header('Location: login.php'.($admin?"?admin":""));
		exit;
	}
	$pw = mysql_result($res,0,'pass');
	if($pw!=$_COOKIE['pass'])
	{
		header('Location: login.php'.($admin?"?admin":""));
		exit;
	}
    //echo 'Logged in as: <b>'.$_COOKIE['user'].'</b><hr>';
	$isadmin = (mysql_result($res,0,'rights')=="1")?true:false;
}
else header('Location: login.php'.($admin?"?admin":""));
?>