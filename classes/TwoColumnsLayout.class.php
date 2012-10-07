<?php
include_once("Layout.inter.php");
include_once("Artists.class.php");
include_once("util/ImageUtil.class.php");
include_once("Config.class.php");

/**
 * Generate an image with logos on two columns
 */
class TwoColumnsLayout implements Layout {
	private static $marginTop = 4; //Between the first logo and top
	private static $width = 300;
	//private static $seperation = 4; //Separation between logos (deprecated)
	private static $sepV = 4; //Vertical separation between logos
	private static $sepH = 4; //Horizontal separation between logos (space between columns)
	
	private static $marginSide = 4;
	
	private $artists = array();
	private $nbArtists = 0;
	private $color = '';
	private $bbg = false;
	
	public function setArtists(array $artists) {
		$this->artists = $artists;
	}
	
	public function setNbArtists($nbArtists) {
		$this->nbArtists = $nbArtists;
	}
	
	public function setColor($color) {
		$this->color = $color;
	}
	
	public function setbbg($bbg){
		$this->bbg = $bbg;
	}
	
	public function getName() {
		return "TwoCols";
	}
	
	/**
	 * Output the image with the logos of each artist
	 */
	public function show() {
		if(count($this->artists) == 0)
			throw new Exception("Missing some informations (artists).");
			
		$totalHeight = array(self::$marginTop,self::$marginTop);
		$listImageRessources = array(array(),array());
		
		$forcedWidth = (self::$width/2) - (self::$sepH/2);
		
		foreach($this->artists as $name) {
			if(count($listImageRessources[0])+count($listImageRessources[1]) 
				== $this->nbArtists)
				break;
			
			$a = Artists::getArtistByName($name);
			
			if($a) //Artist is found
			{
				$image = Artists::imageFromArtist($a);
				
				if(isset($image)) {
					$i = ($totalHeight[1]<$totalHeight[0]?1:0);
					
					$x = imagesx($image);
					$y = imagesy($image);
					$newHeight = floor(($y * $forcedWidth) / $x);
					
					$totalHeight[$i] += $newHeight+self::$sepV;
					array_push($listImageRessources[$i],$image);
				}
			}
			else
			{
				if(Config::ENABLE_REQUESTS) Artists::requestArtist($name);
			}
		}
		
		$totalwidth = self::$width+self::$marginSide*2;//+self::$marginMid;
		$container = imagecreatetruecolor($totalwidth,max($totalHeight)+Config::WATERMARK_H);
		
		//Some colors needed
		$colWhite = imagecolorallocate($container, 255, 255, 255);
		
		//Background color
		imagefilledrectangle($container,0,0,$totalwidth,max($totalHeight)+Config::WATERMARK_H,$colWhite);
		
		
		$currentHeight = array(self::$marginTop,self::$marginTop);
		for($l=0;$l<count($listImageRessources);$l++) {
			$list = $listImageRessources[$l];
			
			foreach($list as $image) {
				$x = imagesx($image);
				$y = imagesy($image);
				
				$newHeight = floor(($y * $forcedWidth) / $x);
				
				$resized = imagecreatetruecolor($forcedWidth, $newHeight);				
				imagecopyresampled($resized,$image,0,0,0,0,$forcedWidth,$newHeight,$x,$y);
				
				imagecopymerge($container,$resized,($forcedWidth+self::$sepH) * $l + self::$marginSide,$currentHeight[$l],0,0,$forcedWidth,$newHeight,100);
				
				//echo $currentHeight[$l] . "-";
				$currentHeight[$l] += $newHeight + self::$sepV;
			}
		}
		
		//Add header
		$wmimg = imagecreatefrompng("watermark.png");
		imagecopymerge($container,$wmimg,(self::$width-Config::WATERMARK_W)/2,max($currentHeight),0,0,Config::WATERMARK_W,Config::WATERMARK_H,100);	
		
		ImageUtil::applyFilter($container,$this->color,$this->bbg);
		
		imagepng($container);
	}
}

?>