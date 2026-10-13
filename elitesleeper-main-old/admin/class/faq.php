<?php

class Faq{
	private $dbUserTable = 'elitesleeper_faq';
	
	public function trash($id){		
		if(!$id){
			echo "id is missing";
			return;
		}
		//mysqli_query($GLOBALS['conn'],"delete from $this->dbPaymentTable where id='$id'") or die("some sql error");
		$query = "delete from $this->dbUserTable where id=$id";
		return mysqli_query($GLOBALS['conn'],$query);
	}

	public function newsubstr($str, $length, $minword = 3)
	{
    $sub = '';
    $len = 0;
   
    foreach (explode(' ', $str) as $word)
    {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
       
        if (strlen($word) > $minword && strlen($sub) >= $length)
        {
            break;
        }
    }
	
    return $sub . (($len < strlen($str)) ? '.........' : '');
	} 

	public function load($id){		
		if(!$id){
			echo "id is missing";
			return;
		}
		$result = mysqli_query($GLOBALS['conn'],"SELECT * FROM $this->dbUserTable where id='$id'") or die("Wrong sql");
		if(mysqli_num_rows($result))
			return mysqli_fetch_array($result);
		else
			return array();
	}

	public function removeFaq()
	{
		$ids = @implode(",",$_POST['users']);
		$query = "DELETE FROM $this->dbUserTable WHERE id in ($ids)";
		return mysqli_query($GLOBALS['conn'],$query);
	}
	public function updateFaq($data,$primarykey,$id)
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
	public function fetchRow($condition="")
	{
		$query = "SELECT * FROM $this->dbUserTable ";	
		if($condition!=""){
			$query.=" $condition";
		}
						
		return mysqli_query($GLOBALS['conn'],$query);
	}
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


		################ function for check exists mail id ##################

	public function email_exists($id,$email)
	{		
		$query="select * from $this->dbUserTable where id!='".$id."' and email_id='".$email."'";
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
		$query="select * from $this->dbUserTable where  email='".$email."'";
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
	public function insertFaq($data)
	{
		$query="insert into $this->dbUserTable set ";
		$temp = array();
		foreach($data as $key=>$value)
		{

			$temp[]="$key = '$value'";
		}
		$query.=implode(",",$temp);
		$query.=",status = '1' ,create_date = NOW()";		

		mysqli_query($GLOBALS['conn'],$query);
		return mysql_insert_id();
	}	
	public function expiryDate($id)
	{
		$query="SELECT * FROM $this->dbPaymentTable as p INNER JOIN $this->dbUserTable as u ON p.user_id = u.id where p.user_id = $id order by p.id desc limit 1";
		$data=mysqli_query($GLOBALS['conn'],$query)or die('not ruuning');
		while($res=mysqli_fetch_array($data))
		{
		$date=$res['payment_date'];
		}
		return $date;
	}

	public function subscriptionValidDate($id)
	{
		$query="select DATE_FORMAT(DATE_ADD(max(payment_date), INTERVAL 30 day), '%Y-%m-%d') as validity_upto from tbl_payments where user_id=$id";
		$data = mysqli_query($GLOBALS['conn'],$query) or die('Sorry some error');
		while($res=mysqli_fetch_array($data))
		{
			$date=$res['validity_upto'];
		}
		return $date;		
	}

}
?>