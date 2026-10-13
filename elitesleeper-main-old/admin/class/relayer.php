<?php

##################################################
###    File name: newsletter.php			    ###
###    Auther Name: Anil Kumar				    ###
###    Copy Right : Braintechnosys				###
###    Date: 18/10/2016							###
###    @version 1.1								###
##################################################

/**
*@class to handle functions related to user table
*/

class relayer{
	private $dbUserTable = 'email_relayer.email_relayer';

	public function fetchAll($condition="",$order="",$limit=""){
		$query = "SELECT * FROM $this->dbUserTable";
		if($condition!=""){
			$query.=" $condition";
		}
		if($order!="")
		{
			$query.=" $order";
		}
		if($limit!="")
		{
			$query.=" $limit";
		}
		return mysqli_query($GLOBALS['conn'],$query);
	}

	public function totalNumberOfRows($condition="",$order="",$limit=""){
		$result = $this->fetchAll($condition,$order,$limit);
		return mysqli_num_rows($result);
	}

	public function relayerlistSelect(){
		$relayer_data=mysqli_query($GLOBALS['conn'],"SELECT * FROM $this->dbUserTable");
		return $relayer_data;
	}

	public function relayerSelectById($id){
		$relayer_data=mysqli_query($GLOBALS['conn'],"SELECT * FROM $this->dbUserTable WHERE id=$id");
		return $relayer_data;
	}

	public function relayerUpdate($data,$id){
		$query = "UPDATE $this->dbUserTable set ";
		$temp = array();
		foreach($data as $key=>$value){
			$temp[]="$key = '$value'";
		}
		$query.=implode(",",$temp);
		$query.=" WHERE id='$id'";

		return mysqli_query($GLOBALS['conn'],$query) or die(mysql_error());
	}

}
?>
