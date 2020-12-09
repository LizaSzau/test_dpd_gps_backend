<?php 

class Calculation
{   
	private $aLat;
	private $aLong;
	private $bLat;
	private $bLong;
	private $cLat;
	private $cLong;
	private $dLat;
	private $dLong;
	private $latSide;
	private $longSide;
	private $cost;
	private const R = 6371000;
	private const FILE = 'items.json';
	
    public function __construct($aLat, $aLong, $bLat, $bLong)
	{
        $this->aLat = $aLat;
        $this->aLong = $aLong;
        $this->bLat = $bLat;
        $this->bLong = $bLong;
		
		$this->setCoordinates();
		$this->calcSides();
    } 
	
//--------------------------------------------------------------------------------
// Getters
//--------------------------------------------------------------------------------
	public function get_cLat()
	{
		return $this->cLat;
	}
	
	public function get_cLong()
	{
		return $this->cLong;
	}	
	
	public function get_dLat()
	{
		return $this->dLat;
	}
	
	public function get_dLong()
	{
		return $this->dLong;
	}	
	
	public function get_perimeter()
	{
		return round(2 * ($this->latSide + $this->longSide), 2);
	}

	public function get_area()
	{
		return round($this->latSide * $this->longSide, 2);
	}
	
	public function get_cost()
	{
		return $this->cost;
	}
	
//--------------------------------------------------------------------------------
// Set coordinates
//--------------------------------------------------------------------------------
	public function setCoordinates()
	{
		$this->cLat = $this->bLat;
		$this->cLong = $this->aLong;
		$this->dLat = $this->aLat;
		$this->dLong = $this->bLong;
	}
		
//--------------------------------------------------------------------------------
// Calc sides
//--------------------------------------------------------------------------------
	public function calcSides()
	{
		$x = $this->aLat - $this->cLat;
		$f = ($x / 180) * pi();
		$f = abs($f * self::R);
		$this->latSide = $f;

		$x = $this->bLong - $this->cLong;
		$f = ($x / 180) * pi();
		$f = abs($f * self::R);
		$this->longSide = $f;
	}	

//--------------------------------------------------------------------------------
// Calc Cost
//--------------------------------------------------------------------------------
	public function calcCost()
	{
		$strJSON = file_get_contents(self::FILE);
		$arrJSON = json_decode($strJSON, true);
		
		$cost = 0;
		$long = $this->get_perimeter();
		
		// Sarok
		$long -= 4 * 2 * $arrJSON['sarok']['size'];
		$cost = 4 * $arrJSON['sarok']['cost'];

		// Kapu
		$long -= 4 * $arrJSON['kapu']['size'];
		$cost += 4 * $arrJSON['kapu']['cost'];
		
		// Drót + oszlop
		$drot_oszlop =  $arrJSON['drot']['size'] + $arrJSON['oszlop']['size'];
		$in = ceil($long / $drot_oszlop);
		
		// Cost
		$this->cost =  $cost + ($in * ($arrJSON['drot']['cost'] + $arrJSON['oszlop']['cost']));
	}
}
