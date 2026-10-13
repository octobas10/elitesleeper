<?php

##################################################
###    File name: dbclass						###
###    Auther Name: Amar  Gupta					###
###    Copy Right : Braintechnosys				###
###    Date: 22/06/2010							###
###    @version 1.1								###
##################################################

/**
*@class to handle functions related to user table
*/

class state{
	private $dbUserTable = 'elitesleeper_state';

	public function load($id){
		if($id==""){
			echo "Id is missing";
			return;
		}
		$query = "SELECT * FROM $this->dbUserTable WHERE id='$id' ";	
		$result = mysqli_query($GLOBALS['conn'],$query) or die("sorry some sql error");
		if(mysqli_num_rows($result)){
			return mysqli_fetch_array($result);
		}
		return array();
	}

	public function fetchAll($countryId="")
	{
		$query = "SELECT * FROM $this->dbUserTable WHERE 1=1 ";	
		if($countryId!=""){
			$query.=" AND country_id='$countryId' ";
		}
		$result = mysqli_query($GLOBALS['conn'],$query) or die("Sorry some sql error");
		if(mysqli_num_rows($result)){
			var $RecordSet = array();
			while($row = mysqli_fetch_assoc($result)){
				$RecordSet[] = $row;
			}
			return $RecordSet;
		}
		return array();
	}
}


?>