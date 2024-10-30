<?php
/**
 * @author Fethi Bendimerad <fethi_bendimerad@hotmail.com>
 * @link http://www.bf_tech.info/
 */
class Hijri {

	public $hijriYear = 0;
	public $hijriMonth = 0;
	public $hijriDay = 0;
	public $hijriMonthName = '';
	public $cycleDays = 0;
	public $cycles = 0;
	public $gregorianDays = 0;
	public $gregorianDate = '';

	/**
	 * Initiate the gregorianDate to the current date is provided
	 */
	function __construct() {

//		date_default_timezone_set('America/New_York');
//		$this->gregorianDate = date('Y-m-d');
//		$this->cycleDays();
	}

	/**
	 * @return All variables of \Hijri Php Object
	 */
	public function result() {
    	var_dump(get_object_vars($this));
	}

	public function setTimeZone($timezone) {
		date_default_timezone_set($timezone);
	}

	/**
	 * @param String $date Anything that we can convert to a \DateTime object
	 *
	 * @return String of set \DateTime object
	 */
	public function setDate($date) {
		$this->gregorianDate = date('Y-m-d', strtotime($date));
		$this->cycleDays();
		return $this->gregorianDate;
	}

	/**
	 * @return String custom custon format of the \DateTime object gregorianDate
	 */
	public function getDate() {
		return date('l d F Y',strtotime($this->gregorianDate));
	}

	/**
	 * @return Number of gregorian days that elapsed from day 1 of Hijri to selected date	 
	 */
	private function gregorianDays() {
		$firstMoharram = new DateTime('622-7-16');
		$givenDate = new DateTime($this->gregorianDate);
		$fridayOctobre = new DateTime('1582-10-15');

		if ($givenDate > $fridayOctobre) {
			return $this->gregorianDays = $firstMoharram->diff($givenDate)->days;

		} else {
			$thursdayOctobre = new DateTime('1582-10-4');
			return $this->gregorianDays = $firstMoharram->diff($givenDate)->days - $thursdayOctobre->diff($fridayOctobre)->days;
		}
	}

	/**
	 * @param Integer $year also refered as the year rank in the current cycle
	 *
	 * @return Boolean
	 */
	public function isLeapYear($year) {
		$leapYears = [2, 5, 7, 10, 13, 16, 18, 21, 24, 26, 29];
		return (in_array($year, $leapYears)) ? true : false ;
	}

	/**
	 * @return String of phonetic transcription of the monthes names
	 */
	public function hijriMonthName() {
		$name = [1 => "Mouharrem", 2 => "Safar", 3 => "Rabie Al-Awwal", 4 =>  "Rabie Al-Âkher", 5 => "Joumada I", 6 => "Joumada II", 7 =>  "Rajab", 8 => "Chabân", 9 => "Ramadân", 10 => "Chawwâl", 11 => "Dhoul-Qida", 12 => "Dhoul-Hijja"];
		return $this->hijriMonthName = $name[$this->hijriMonth];
	}

	/**
	 * @return Number of days from beginning of this cycle
	 */
	private function cycleDays() {
		$this->gregorianDays();
		$totalMonths = $this->gregorianDays/29.53058868;
		$yearRank = ($totalMonths / 12) % 30;
		$this->cycles = floor(($totalMonths / 12) / 30);
		$cycleMonths = $totalMonths - ($this->cycles * 30 * 12);
		return $this->cycleDays = $cycleMonths * 29.53058868;
	}


	/**
	 * @return String of Hijri Date
	 */
	public function hijriDate() {
		$y = 1;
		$m = 1;
		$d = 0;

		for ($i=1; $i <= $this->cycleDays - 1; $i++) {

			$d++;

			if ($m % 2 == 0) {
				if (($m < 12) and ($d == 29)) {
					$m++;
					$d=0;
				} else if (($m == 12) and ($d == 30)) {
					$m++;
					$d=0;
				}
			}

			if (($m % 2 == 1)) {
				if ($d == 29) {
					if (($m == 11) and (!$this->isLeapYear($y))) {
						$m++;
						$d=0;
					}
				} else if ($d == 30) {
					$m++;
					$d=0;
				}
			}

			if ($m == 13) {
				$y++;
				$m = 1;
			}

		}// End For loop

		$this->hijriYear = ($y + $this->cycles * 30);
		$this->hijriMonth = $m;
		$this->hijriDay = $d;

		return $d.' '.$this->hijriMonthName().' '.($y + $this->cycles * 30);
	}
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
	//Get & Sanitize $_POST Values
	$date 		= trim($_POST['date']);
	$timezone 	= $_POST['timezone'];
	
	//Simple Validation
	if (empty($date)){
		// Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Please check your form fields";
        exit;
	}
	
	if (empty($timezone)) {
		$timezone = 'America/New_York';
	}

	date_default_timezone_set($timezone);
	if(DateTime::createFromFormat('Y-m-d', $date)){
		$H = new Hijri;
		$H->setTimeZone($timezone);
		$H->setDate($date);
		$H->hijriDate();
		//Set 200 Response (Success)
        http_response_code(200);
        echo $H->hijriDay." ".$H->hijriMonthName." ".$H->hijriYear." Hijri";
	} else {
		//Set 500 Response (internal server error)
        http_response_code(500);
        echo "Error: There was a problem sending your message";
	}
} else {
	//Set 403 Response (forbidden)
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}