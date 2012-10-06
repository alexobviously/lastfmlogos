<?php
include_once("Cache.inter.php");
include_once("Layout.inter.php");
include_once("CrawlerLastFM.class.php");
include_once("Config.class.php");

class DatabaseCache implements Cache {
	
	private $user = null;
	private $type = null;
	private $nbArtists = 0;
	private $color = '';
	private $imgGen = null;
	private $layout = '';
	private $cachefile = '';
	private $handle = null;
	private $bbg = false;
	
	private $result = null;
	
	public function setUser($user) {
		$this->user = $user;
	}
	
	public function setPeriodType($type) {
		$this->type = $type;
	}
	
	public function setNbArtists($nbArtists) {
		$this->nbArtists = $nbArtists;
	}
	
	public function setColor($color) {
		$this->color = $color;
	}
	
	public function setLayout(Layout $imgGen) {
		$this->imgGen = $imgGen;
		$this->layout = $imgGen->getName();
	}
	
	public function setbbg($bbg){ // black background
		$this->bbg = $bbg;
	}
	
	/**
	 * Generate the picture and ouput it.
	 * The second request will use the pregenerated version.
	 */
	public function generate() {
		if(!isset($this->user) || !isset($this->type)  || !isset($this->color)
			|| !isset($this->imgGen))
			throw new Exception("Missing some informations ".
				"(user, type, color or image generator)."); 
		
		$db = Config::$dbInstance;
		$_b = $this->bbg==true?"_b":"";
		$cachefile = 'cache/'.$this->user.'_'.$this->nbArtists.'_'.$this->type.'_'.$this->color.'_'.$this->layout.$_b.'.png';
		
		/*$sql = "SELECT * ".
			"FROM lastfm_images_cache_blob ".
			"WHERE user = ? AND nb_artists = ? AND type = ? AND color = ? AND layout = ?";
		$values = array($this->user,$this->nbArtists,$this->type,$this->color,$this->layout);
		$it = $db->execQueryIterator($sql,$values);
		
		if($line = $it->getNext()) { //Already in cache
			$this->result =  $line['image'];*/
		if(file_exists($cachefile))
		{
			$handle = fopen($cachefile, "r");
			$this->result = fread($handle, filesize($cachefile));
			fclose($handle);
		}
		else { //First request : generate picture and store it in db
			
			//Check if the maximum number of generations is reach
			
			/*$sql = "SELECT count(idimage) as nbgen ".
				"FROM lastfm_images_cache_blob ".
				"WHERE user = ?";
			
			$values = array($this->user);
			$it = $db->execQueryIterator($sql,$values);
			if($line = $it->getNext()) {
				if($line['nbgen'] >= Config::NB_GENERATION_ALLOW) {
					Errors::showImageMessage(
						"You have already generate ".$line['nbgen']." banners.".
						" Wait for the next update to try a different layout.");
					return;
				}
			}*/
			
			//
			
			$crawler = new CrawlerLastFM();
			$artists = $crawler->getListTopArtists($this->user,$this->type);
			
			if(count($artists)>0) {
				$this->imgGen->setArtists($artists);
				$this->imgGen->setNbArtists($this->nbArtists);
				$this->imgGen->setColor($this->color);
				$this->imgGen->setbbg($this->bbg);
				ob_start(array(&$this,'cacheImage'));
				$this->imgGen->show();
				ob_end_flush();
			}
			else { //An invalid/block/empty account
				throw new Exception("No artist found.");
			}
		}
	}
	
	public function outputResult() {
		echo $this->result;
	}
	
	public function cacheImage($buffer) {
		$cachefile = '';
		$handle = null;
		$this->result = $buffer;
		
		$db = Config::$dbInstance;
		
		/*$sql = "INSERT lastfm_images_cache_blob (user,nb_artists,type,color,layout,image) ".
			"VALUES(?,?,?,?,?,?)";
		$values = array($this->user,
			$this->nbArtists,
			$this->type,
			$this->color,
			$this->layout,
			$buffer);
		$db->execQuery($sql,$values);*/
		$_b = $this->bbg==true?"_b":"";
		$cachefile = 'cache/'.$this->user.'_'.$this->nbArtists.'_'.$this->type.'_'.$this->color.'_'.$this->layout.$_b.'.png';
		$handle = fopen($cachefile, "x");
		fwrite($handle,$buffer);
		fclose($handle);
		
		return $buffer;
	}
	
}

?>
