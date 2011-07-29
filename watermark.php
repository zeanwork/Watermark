<?php
/**
 * Apply watermark image
 * 
 * Copyright 2011, Josemar Davi Luedke <josemarluedke@gmail.com>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições do arquivos e/ou parte do códigos deve manter o aviso de copyright acima.
 * 
 * @author Josemar Davi Luedke <josemarluedke@gmail.com>
 * @version 0.1.0
 * @copyright Copyright 2010, Josemar Davi Luedke <josemarluedke@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Watermark {
	
	/**
	 * 
	 * Erros
	 * @var array
	 */
	public $errors = array();

	/**
	 * 
	 * Image Source
	 * @var img
	 */
	private $imgSource = null;

	/**
	 * 
	 * Image Watermark
	 * @var img
	 */
	private $imgWatermark = null;

	/**
	 * 
	 * Positions watermark
	 * 0: Centered
	 * 1: Top Left
	 * 2: Top Right
	 * 3: Footer Right
	 * 4: Footer left
	 * 5: Top Centered
	 * 6: Center Right
	 * 7: Footer Centered
	 * 8: Center Left
	 * @var number
	 */
	private $watermarkPosition = 0;
	
	/**
	 * 
	 * Check PHP GD is enabled
	 */
	public function __construct(){
		if(!function_exists("imagecreatetruecolor")){
			if(!function_exists("imagecreate")){
				$this->error[] = "You do not have the GD library loaded in PHP!";
			}
		}
	}

	/**
	 * 
	 * Get function name for use in apply
	 * @param string $name Image Name
	 * @param string $action |open|save|
	 */
	private function getFunction($name, $action = 'open') {
		if(eregi("^(.*)\.(jpeg|jpg)$", $name)){
			if($action == "open")
				return "imagecreatefromjpeg";
			else
				return "imagejpeg";
		}elseif(eregi("^(.*)\.(png)$", $name)){
			if($action == "open")
				return "imagecreatefrompng";
			else
				return "imagepng";
		}elseif(eregi("^(.*)\.(gif)$", $name)){
			if($action == "open")
				return "imagecreatefromgit";
			else
				return "imagegif";
		}else{
			$this->error[] = "Image Format Invalid!";
		}
	}

	/**
	 * 
	 * Get image sizes
	 * @param object $img Image Object
	 */
	public function getImgSizes($img){
		return array('width' => imagesx($img), 'height' => imagesy($img));
	}

	/**
	 * Get positions for use in apply
	 * Enter description here ...
	 */
	public function getPositions(){
		$imgSource = $this->getImgSizes($this->imgSource);
		$imgWatermark = $this->getImgSizes($this->imgWatermark);
		$positionX = 0;
		$positionY = 0;

		# Centered
		if($this->watermarkPosition == 0){
			$positionX = ( $imgSource['width'] / 2 ) - ( $imgWatermark['width'] / 2 );
			$positionY = ( $imgSource['height'] / 2 ) - ( $imgWatermark['height'] / 2 );
		}

		# Top Left
		if($this->watermarkPosition == 1){
			$positionX = 0;
			$positionY = 0;
		}

		# Top Right
		if($this->watermarkPosition == 2){
			$positionX = $imgSource['width'] - $imgWatermark['width'];
			$positionY = 0;
		}

		# Footer Right
		if($this->watermarkPosition == 3){
			$positionX = ($imgSource['width'] - $imgWatermark['width']) - 5;
			$positionY = ($imgSource['height'] - $imgWatermark['height']) - 5;
		}

		# Footer left
		if($this->watermarkPosition == 4){
			$positionX = 0;
			$positionY = $imgSource['height'] - $imgWatermark['height'];
		}

		# Top Centered
		if($this->watermarkPosition == 5){
			$positionX = ( ( $imgSource['height'] - $imgWatermark['width'] ) / 2 );
			$positionY = 0;
		}

		# Center Right
		if($this->watermarkPosition == 6){
			$positionX = $imgSource['width'] - $imgWatermark['width'];
			$positionY = ( $imgSource['height'] / 2 ) - ( $imgWatermark['height'] / 2 );
		}

		# Footer Centered
		if($this->watermarkPosition == 7){
			$positionX = ( ( $imgSource['width'] - $imgWatermark['width'] ) / 2 );
			$positionY = $imgSource['height'] - $imgWatermark['height'];
		}

		# Center Left
		if($this->watermarkPosition == 8){
			$positionX = 0;
			$positionY = ( $imgSource['height'] / 2 ) - ( $imgWatermark['height'] / 2 );
		}

		return array('x' => $positionX, 'y' => $positionY);
	}

	/**
	 * 
	 * Apply watermark in image
	 * @param string $imgSource Name image source
	 * @param string $imgTarget Name image target
	 * @param string $imgWatermark Name image watermark
	 * @param number $position Position watermark
	 */
	public function apply($imgSource, $imgTarget,  $imgWatermark, $position = 0){
		$this->watermarkPosition = $position;

		$functionSource = $this->getFunction($imgSource, 'open');
		$this->imgSource = $functionSource($imgSource);

		$functionWatermark = $this->getFunction($imgWatermark, 'open');
		$this->imgWatermark = $functionWatermark($imgWatermark);
		
		$sizesWatermark = $this->getImgSizes($this->imgWatermark);
		$positions = $this->getPositions();

		imagecopy($this->imgSource, $this->imgWatermark, $positions['x'], $positions['y'], 0, 0, $sizesWatermark['width'], $sizesWatermark['height']);

		$functionTarget = $this->getFunction($imgTarget, 'save');
		$functionTarget($this->imgSource, $imgTarget, 100);

		imagedestroy($this->imgSource);
		imagedestroy($this->imgWatermark);
	}
}