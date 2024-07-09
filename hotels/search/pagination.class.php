<?php
class PerPage {
	public $perpage;
	
	function __construct() {
		$this->perpage = 10;
	}
	
	function getAllPageLinks($count,$currntpage) {

		$output = '';
		if(!isset($currntpage)) $currntpage = 1;
		if($this->perpage != 0)
			$pages  = ceil($count/$this->perpage);
		if($pages>1) {
			if($currntpage == 1) 
				$output = $output . '<span class="link first disabled">&#8810;</span><span class="link disabled">&#60;</span>';
			else	
				$output = $output . '<a class="link first" onclick="getresult(\'' .  (1) . '\')" >&#8810;</a><a class="link" onclick="getresult(\'' .  ($currntpage-1) . '\')" >&#60;</a>';
			
			
			if(($currntpage-3)>0) {
				if($currntpage == 1)
					$output = $output . '<span id=1 class="link current">1</span>';
				else				
					$output = $output . '<a class="link" onclick="getresult(\'' .  '1\')" >1</a>';
			}
			if(($currntpage-3)>1) {
					$output = $output . '<span class="dot">...</span>';
			}
			
			for($i=($currntpage-2); $i<=($currntpage+2); $i++)	{
				if($i<1) continue;
				if($i>$pages) break;
				if($currntpage == $i)
					$output = $output . '<span id='.$i.' class="link current">'.$i.'</span>';
				else				
					$output = $output . '<a class="link" onclick="getresult(\'' .  $i . '\')" >'.$i.'</a>';
			}
			
			if(($pages-($currntpage+2))>1) {
				$output = $output . '<span class="dot">...</span>';
			}
			if(($pages-($currntpage+2))>0) {
				if($currntpage == $pages)
					$output = $output . '<span id=' . ($pages) .' class="link current">' . ($pages) .'</span>';
				else				
					$output = $output . '<a class="link" onclick="getresult(\'' .   ($pages) .'\')" >' . ($pages) .'</a>';
			}
			
			if($currntpage < $pages)
				$output = $output . '<a  class="link" onclick="getresult(\'' .  ($currntpage+1) . '\')" >></a><a  class="link" onclick="getresult(\'' .  ($pages) . '\')" >&#8811;</a>';
			else				
				$output = $output . '<span class="link disabled">></span><span class="link disabled">&#8811;</span>';
			
			
		}
		return $output;
	}
	function getPrevNext($count,$href) {
		$output = '';
		if(!isset($currntpage)) $currntpage = 1;
		if($this->perpage != 0)
			$pages  = ceil($count/$this->perpage);
		if($pages>1) {
			if($currntpage == 1) 
				$output = $output . '<span class="link disabled first">Prev</span>';
			else	
				$output = $output . '<a class="link first" onclick="getresult(\'' .  ($currntpage-1) . '\')" >Prev</a>';			
			
			if($currntpage < $pages)
				$output = $output . '<a  class="link" onclick="getresult(\'' .  ($currntpage+1) . '\')" >Next</a>';
			else				
				$output = $output . '<span class="link disabled">Next</span>';
			
			
		}
		return $output;
	}
}
?>

