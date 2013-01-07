<?php
$_PATH['classes'] = 'classes/';
include_once($_PATH['classes'].'Config.class.php');

$admin = isset($_GET['admin'])?true:false;

$lform = '<title>Lastfmlogos User Authentication</title>
<form name="login" method="post" action="login.php'.($admin?"?admin":"").'">
   Username: <input type="text" name="user" id="user" value="'.$_POST['user'].'"><br>
   Password: <input type="password" name="pass" id="pass"><br>
   Remember Me: <input type="checkbox" name="r" id="r" value="1"><br>
   <input type="submit" name="submit" value="Login!">
   </form>';

if (isset($_POST['user']) && isset($_POST['pass'])) {
	$db = Config::$dbInstance;
	$sql = "select * from lastfm_users where `user` = '".$_POST['user']."'";
	$res = $db->execQuery($sql);
	$nr = mysql_num_rows($res);
	if($nr==0)
	{
		echo $lform.'<br><b>Invalid Username</b>';
		exit;
	}
	$pw = mysql_result($res,0,'pass');
	if($pw!=md5($_POST['pass']))
	{
		echo $lform.'<br><b>Invalid Password</b>';
		exit;
	}
	if (isset($_POST['r'])) {
		// Set cookie to expire in a year
		setcookie('user', $_POST['user'], time()+31536000, '/', '127.0.0.1');
		setcookie('pass', md5($_POST['pass']), time()+31536000, '/', '127.0.0.1');
	
	} else {
		// Set cookie to expire when the browser closes
		setcookie('user', $_POST['user'], false, '/', '127.0.0.1');
		setcookie('pass', md5($_POST['pass']), false, '/', '127.0.0.1');
	}
	if($admin&&mysql_result($res,0,'rights')=='1') header('Location: admin.php');
	else header('Location: user.php');
    
} else {
	echo $lform;
}
?>