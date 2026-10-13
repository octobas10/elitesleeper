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

class product{
	private $dbUserTable = 'elitesleeper_product';	
	private $dbUserTable2 = 'elitesleeper_country';
	
	public function fetchAll($condition="",$order="",$limit="")
	{
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
		//echo $query;
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function totalNumberOfRows($condition="",$order="",$limit="")
	{
		$result = $this->fetchAll($condition,$order,$limit);
		return mysqli_num_rows($result);
	}
	public function removeProduct()
	{
		$ids = @implode(",",$_POST['users']);
		//$query = "DELETE FROM $this->dbPaymentTable WHERE user_id IN ($ids)";		
		//mysqli_query($GLOBALS['conn'],$query) or die("some sql error");
		$query = "DELETE FROM $this->dbUserTable WHERE id in ($ids)";
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function removeShiping()
	{
		$ids = @implode(",",$_POST['users']);
		//$query = "DELETE FROM $this->dbPaymentTable WHERE user_id IN ($ids)";		
		//mysqli_query($GLOBALS['conn'],$query) or die("some sql error");
		$query = "DELETE FROM $this->dbUserTable2 WHERE id in ($ids)";
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function trash($id){		
		if(!$id){
			echo "id is missing";
			return;
		}
		//mysqli_query($GLOBALS['conn'],"delete from $this->dbPaymentTable where id='$id'") or die("some sql error");
		$query = "delete from $this->dbUserTable where id=$id";
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function fetchShiping($condition="",$order="",$limit="")
	{
		$query = "SELECT * FROM $this->dbUserTable2";	
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
		//echo $query;
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function updateProduct($data,$primarykey,$id)
	{
		if(count($data)==0)
		{
			echo "Sorry no data to update";
			return;
		}
		$query = "update $this->dbUserTable set ";
		$temp = array();
		foreach($data as $key=>$value)
		{
			if($key!=$primarykey){
			$temp[]="$key = '$value'";
			}
		}
		$query.=implode(",",$temp);
		$query.=" where $primarykey='$id'";		
		return mysqli_query($GLOBALS['conn'],$query) or die("sql error");
	}
	public function insertProduct($data)
	{
		$query="insert into $this->dbUserTable set ";
		$temp = array();
		foreach($data as $key=>$value)
		{

			$temp[]="$key = '$value'";
		}
		$query.=implode(",",$temp);
		$query.="";		

		mysqli_query($GLOBALS['conn'],$query);
		return mysql_insert_id();
	}
	public function updateShiping($data,$id)
	{
		if(count($data)==0)
		{
			echo "Sorry no data to update";
			return;
		}
		$query = "update $this->dbUserTable2 set ";
		$temp = array();
		foreach($data as $key=>$value)
		{
			
			$temp[]="$key = '$value'";
		}
		$query.=implode(",",$temp);
		$query.=" where id='$id'";		
		
		return mysqli_query($GLOBALS['conn'],$query) or die("sql error");
	}
}