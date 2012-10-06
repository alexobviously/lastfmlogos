<?php
include_once("Layout.inter.php");
include_once("Artists.class.php");
include_once("util/ImageUtil.class.php");
include_once("Config.class.php");

/**
 * Generate an image with logos on one column
 */
class OneColumnLayout implements Layout {	
	//Various settings
	private static $marginSide = 4; //Side margin (in px)
	private static $marginTop = 4; //Between the first logo and top
	private static $width = 168;
	private static $seperation = 5; //Separation between logos
	private static $baseDirectoryLogos = 'logos/';
	
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
		return "OneCol";
	}
	
	/**
	 * Output the image with the logos of each artist
	 */
	public function show() {
		if(count($this->artists) == 0)
			throw new Exception("Missing some informations (artists).");
		
		$totalHeight=0;
		$listImageRessources = array();
		$_w = self::$width-self::$marginSide*2;
		
		foreach($this->artists as $name) {
			if(count($listImageRessources) == $this->nbArtists)
				break;
			
			$a = Artists::getArtistByName($name);
			
			if($a != null) //Artist is found
			{
				$image = Artists::imageFromArtist($a);
				
				if(isset($image)) {
					$x = imagesx($image);
					$y = imagesy($image);
					if($x!=$_w)
						$y = $y/($x/$_w);
					//$totalHeight += imagesy($image)+self::$seperation;
					$totalHeight += $y+self::$seperation;
					array_push($listImageRessources,$image);
				}
			}
			else
			{
				if(Config::ENABLE_REQUESTS) Artists::requestArtist($name);
			}
		}
		//echo $totalHeight;
		$totalHeight += self::$marginTop + Config::WATERMARK_H;
		
		$container = imagecreatetruecolor(self::$width,$totalHeight);
		
		//Some colors needed
		$colWhite = imagecolorallocate($container, 255, 255, 255);
		
		//Background color
		imagefilledrectangle($container, 0, 0, self::$width, $totalHeight, $colWhite);
		
		$expectWidth = self::$width - (2 * self::$marginSide);
		$currentHeight = self::$marginTop;
		foreach($listImageRessources as $image) {
			$x = $widthImg = imagesx($image);
			$y = $heightImg = imagesy($image);
			if($x!=$_w)
			{
				$heightImg = $y/($x/$_w);
				$x = $_w;
			}
			$newHeight = floor(($y * $_w) / $x);
			
			$resized = imagecreatetruecolor($_w,$heightImg);				
			imagecopyresampled($resized,$image,0,0,0,0,$_w,$heightImg,$widthImg,$y);
			
			imagecopymerge($container,
				$resized,
				self::$marginSide +
					($x < $expectWidth ?($expectWidth - $x)*.5 :0),
				$currentHeight,
				0,
				0,
				$x,
				$heightImg,
				100);
			
			/*imagecopymerge($container,
				$image,
				self::$marginSide +
					($widthImg < $expectWidth ?($expectWidth - $widthImg)*.5 :0),
				$currentHeight,
				0,
				0,
				imagesx($image),
				imagesy($image),
				100);*/
				
			//$currentHeight += imagesy($image) + self::$seperation;
			$currentHeight += $heightImg + self::$seperation;
		}
		//Add header
		$wmimg = imagecreatefrompng("watermark.png");
		imagecopymerge($container,$wmimg,(self::$width-Config::WATERMARK_W)/2,$currentHeight,0,0,Config::WATERMARK_W,Config::WATERMARK_H,100);	
		
		ImageUtil::applyFilter($container,$this->color,$this->bbg);
		
		imagepng($container);
	}
	
	
}

?>