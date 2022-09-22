<?php
class getSysInfo{
	public $OSystem;
	public $browser;
	public function checkSys(){
		$source = $_SERVER['HTTP_USER_AGENT'];			
			if(preg_match("/OPR/i", $source))
			{
				$browser = "Opera";
			}
			elseif(preg_match("/Firefox/i", $source))
			{
				$browser = "Mozilla Firefox";
			}
			elseif(preg_match("/Edg/i", $source))
			{
				$browser = "Edge";
			}
			elseif(preg_match("/Safari/i", $source))
			{
				if(preg_match("/Chrome/i", $source))
				{
					$browser = "Chrome";
				}
				else
					$browser = "Safari";
			}
			elseif(preg_match("/Chrome/i", $source))
			{
				$browser = "Chrome";
			}
			else
				$browser = "Inna przegladarka";
			
			if(preg_match("/macintosh|mac os x/i", $source))
			{
				$OSystem = "Mac";
			}
			elseif(preg_match("/Android/i", $source))
			{
				$OSystem = "Android";
			}
			elseif(preg_match("/Linux/i", $source))
			{
				$OSystem = "Linux";
			}
			elseif(preg_match("/Windows/i", $source))
			{
				$OSystem = "Windows";
			}
			else 
				$OSystem = "Inny system";	
			$this->OSystem = $OSystem;
			$this->browser = $browser;
		}
	}
?>
