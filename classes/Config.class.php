<?php
//include_once("db/MySQLIDB.class.php");
include_once("db/MySQLNative.class.php");
include_once("Slaves.php");
include_once("Log.php");

date_default_timezone_set('America/Montreal');
/**
 * All configuration parameters are set here.
 */
class Config {
	//DB connection
	public static $dbInstance = null; //Initialise ASAP
	
	//Image
	const IMAGE_QUALITY = 85;
	const FOLDER_LOGOS = "logos/";
	
	//Crawler (audioscobber)
	const REQUEST_METHOD = "fopen"; //(cUrl,fopen,file_get_contents)
	const API_KEY = "db109383394640f2b5e174b4514f0014"; //Api key
	
	//Default parameters
	const DEFAULT_BANNER_NB = 10;
	const DEFAULT_BANNER_TYPE = "overall";
	const DEFAULT_BANNER_COLOR = "white";
	const DEFAULT_BANNER_LAYOUT = "OneCol";
	const NB_GENERATION_ALLOW = 1337;
	
	//Delay for browser cache (in sec.)
	const CACHE_DELAY = 432000; //(3600 * 24 * 5)
	
	//Homepage URL
	const HOMEPAGE = "http://127.0.0.1";
	
	//Banner Location (use by the link.php page)
	const LINK_BANNER = "http://127.0.0.1/lfl/banner.php";
	
	//Header size
	const WATERMARK_W = 128;
	const WATERMARK_H = 20;
	
	//Limit simultaneous banner generation
	const MAX_GENERATIONS = 50; //Number of banners that can be generated at any one time
	const WAIT_TIME = 1; //Seconds to wait between generation attempts (don't change this if there's no problem)
	
	//Enable requested logos functionality
	const ENABLE_REQUESTS = true;
	
	//Logging
	const ENABLE_LOG = true;
	const LOG_LEVEL = 3; // 0 = Nothing, 1 = Critical things (recommended), 2 = All everyday errors, 3 = Debugging
}

//Config::$dbInstance = MySQLIDB::getInstance();
Config::$dbInstance = MySQLNative::getInstance();

?>