<?php

##################################################
###    File name: dbclass						###
###    Auther Name: Amar Gupta					###
###    Copy Right : Braintechnosys				###
###    Date: 22/06/2010							###
###    @version 1.1								###
##################################################

/**
*@class to handle functions related to user table
*/

class order{
	private $dbOrderTable = 'elitesleeper_customer';
	private $dbPaymentTable='elitesleeper_payment';
	private $dbCountry='elitesleeper_country';
	public function trash($id){
		if(!$id){
			echo "id is missing";
			return;
		}
		mysqli_query($GLOBALS['conn'],"delete from $this->dbPaymentTable where customer_id='$id'") or die("some sql error");
		$query = "delete from $this->dbOrderTable where id=$id";
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function load($id){
		if(!$id){
			echo "id is missing";
			return;
		}
		$result = mysqli_query($GLOBALS['conn'],"SELECT * FROM $this->dbOrderTable where id='$id'") or die("Wrong sql");
		if(mysqli_num_rows($result))
			return mysqli_fetch_array($result);
		else
			return array();
	}

	public function removeOrder()
	{
		$ids = @implode(",",$_POST['orders']);
		//$query = "DELETE FROM $this->dbPaymentTable WHERE user_id IN ($ids)";
		//mysqli_query($GLOBALS['conn'],$query) or die("some sql error");
		mysqli_query($GLOBALS['conn'],"delete from $this->dbPaymentTable where customer_id='$ids'") or die("some sql error");
		$query = "DELETE FROM $this->dbOrderTable WHERE id in ($ids)";

		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function updateOrder($data,$primarykey,$id)
	{
		if(count($data)==0)
		{
			echo "Sorry no data to update";
			return;
		}
		$query = "update $this->dbOrderTable set ";
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
	public function fetchRow($condition="")
	{
		$query = "SELECT * FROM $this->dbOrderTable ";
		if($condition!=""){
			$query.=" $condition";
		}
		//return $query;
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function fetchAll($condition="",$order="",$limit="")
	{
		$query = "SELECT * FROM $this->dbOrderTable LEFT JOIN $this->dbPaymentTable ON $this->dbOrderTable.id = $this->dbPaymentTable.customer_id";
		if($condition!=""){
			$query.=" $condition";
		}
		if($order!="")
		{
			$query.=" $order";
		}else{
			$query.=" ORDER BY elitesleeper_customer.id DESC";
		}
		if($limit!="")
		{
			$query.=" $limit";
		}
		$query;
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function fetchList($condition="",$order="",$limit="")
	{
		$query = "SELECT * FROM $this->dbOrderTable";
		if($condition!=""){
			$query.=" $condition";
		}
		$query.=" group by email_id";
		if($order!="")
		{
			$query.=" $order";
		}
		if($limit!="")
		{
			$query.=" $limit";
		}
		//echo $query;exit;
		return mysqli_query($GLOBALS['conn'],$query);
	}

	public function totalNumberOfRows($condition="",$order="",$limit="")
	{
		$result = $this->fetchAll($condition,$order,$limit);
		return mysqli_num_rows($result);
	}
	public function totalNumberOfRows2($condition="",$order="",$limit="")
	{
		$result = $this->fetchList($condition,$order,$limit);
		return mysqli_num_rows($result);
	}


		################ function for check exists mail id ##################

	public function email_exists($id,$email)
	{
		$query="select * from $this->dbOrderTable where id!='".$id."' and email_id='".$email."'";
		$data=mysqli_query($GLOBALS['conn'],$query);

		@$res=mysqli_num_rows($data);
		if($res>0)
		{
			$result="1";
		}
		else
		{
			$result ="0";
		}
		return $result;
	}
	public function email_exists2($email)
	{
		$query="select * from $this->dbOrderTable where  email='".$email."'";
		$data=mysqli_query($GLOBALS['conn'],$query);
		$res=mysqli_num_rows($data);
		if($res>0)
		{
			$result="1";
		}
		else
		{
			$result ="0";
		}
		return $result;
	}
	/*******	Function to insert data in order table key=>value pair where key must be same as column name	*************/
	public function insertOrder($data)
	{
		$query="insert into $this->dbOrderTable set ";
		$temp = array();
		foreach($data as $key=>$value)
		{

			$temp[]="$key = '$value'";
		}
		$query.=implode(",",$temp);
		$query.="";
		mysqli_query($GLOBALS['conn'],$query);
		return mysqli_insert_id($GLOBALS['conn']);
	}
	public function fetchOrderRow($customer_id)
	{
		$query = "SELECT * FROM $this->dbOrderTable LEFT JOIN $this->dbPaymentTable ON $this->dbOrderTable.id = $this->dbPaymentTable.customer_id where $this->dbOrderTable.id = '$customer_id' order by $this->dbPaymentTable.payment_date desc limit 1";
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function getUserinfo($customerid)
	{

		$query = mysqli_query($GLOBALS['conn'],"SELECT * from $this->dbOrderTable JOIN $this->dbCountry ON $this->dbOrderTable.bill_country = $this->dbCountry.id where $this->dbOrderTable.id=$customerid");

		return mysqli_fetch_assoc($query);
	}

}
?>
