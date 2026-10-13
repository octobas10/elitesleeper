

<?php

##################################################
###    File name: dbclass						###
###    Author Name: Amit Kumar					###
###    Copy Right : Braintechnosys				###
###    Date: 22/06/2010							###
###    @version 1.0								###
##################################################

/**
*@class to handle functions related to membershipfee table
*/
class payment{
	private $dbTable = 'elitesleeper_payment';	
	
	public function trash($id){		
		if(!$id){
			echo "id is missing";
			return;
		}
		$query = "delete from $this->dbTable where id=$id";		
		return mysqli_query($GLOBALS['conn'],$query);
	}

	public function updateRecord($data,$primarykey,$id)
	{
		if(count($data)==0)
		{
			echo "Sorry no data to update";
			return;
		}
		$query = "update $this->dbTable set ";
		$temp = array();
		foreach($data as $key=>$value)
		{
			if($key!=$primarykey){
			$temp[]="$key = '$value'";
			}
		}
		$query.=implode(",",$temp);
		$query.=" where $primarykey=$id";
		return mysqli_query($GLOBALS['conn'],$query);
	}
	
	public function fetchAll($condition="",$order="",$limit="")
	{
		$query = "SELECT * FROM $this->dbTable ";	
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

	public function totalNumberOfRows($condition="",$order="",$limit="")
	{
		$result = $this->fetchAll();
		return mysqli_num_rows($result);
	}

	public function insertRecord($data)
	{
		$query="INSERT INTO $this->dbTable set ";
		$temp = array();
		foreach($data as $key=>$value)
		{

			$temp[]="$key = '$value'";
		}
		$query.=implode(",",$temp);
		$query.=";";		
		mysqli_query($GLOBALS['conn'],$query) or die("Sorry data could not be inserted");
		return mysql_insert_id();
	}	
}
?>