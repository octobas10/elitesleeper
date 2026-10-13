<?php

##################################################
###    File name: newsletter.php						###
###    Auther Name: Amar  Gupta					###
###    Copy Right : Braintechnosys				###
###    Date: 22/06/2010							###
###    @version 1.1								###
##################################################

/**
*@class to handle functions related to user table
*/

class pages{
	private $dbUserTable = 'elitesleeper_newsletter';


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

	public function updateTable($data,$primarykey,$id)
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
		//return $query;
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



	public function removePage()
	{
		$ids = @implode(",",$_POST['users']);

		$query = "DELETE FROM $this->dbUserTable WHERE id in ($ids)";
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

	public function insertUser($data)
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

	public function bannerlistSelect()
	{
		$banner_data=mysqli_query($GLOBALS['conn'],"select * from elitesleeper_banner");

		return $banner_data;
	}

	public function bannerlistSelectById($id)
	{
		$banner_data=mysqli_query($GLOBALS['conn'],"select * from elitesleeper_banner where id=$id");
		return $banner_data;
	}

	public function bannerlistDelete($id)
	{
		if(!$id){
			echo "id is missing";
			return;
		}
		$sql_data=mysql_fetch_object(mysqli_query($GLOBALS['conn'],"select * from elitesleeper_banner where id=".$id));
		unlink("media/".$sql_data->banner_name);
		//mysqli_query($GLOBALS['conn'],"delete from $this->dbPaymentTable where id='$id'") or die("some sql error");
		$query = "delete from elitesleeper_banner where id=$id";
		return mysqli_query($GLOBALS['conn'],$query);
	}

	public function bannerlistUpdate($data,$primarykey,$id)
	{
		if(count($data)==0)
		{
			echo "Sorry no data to update";
			return;
		}
		$query = "update elitesleeper_banner set ";
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

	public function bannerlistadd($subject,$description,$text,$args){
		$url='';
		mysqli_query($GLOBALS['conn'],"insert into elitesleeper_banner(subject,url,banner_name,status,description,text) values ('".$subject."','".$url."','".$args."','0','".$description."','".$text."')");

	}

	private $coregeTable="elitesleeper_coreg_users";

function selectCoreg($limit="",$suprission_status,$condition,$orderby){

	//echo $suprission_status;
    $sql="SELECT * FROM $this->coregeTable where suprission_status=".$suprission_status." " ;

	if($condition!=""){
	$sql .=$condition." ";
	}
	if($orderby!=""){
	$sql .=$orderby." ";
	} else {
	$sql .=" order by id desc ";
	}
	if($limit!=""){
	$sql .=$limit;
	}
   // echo $sql;
  return mysqli_query($GLOBALS['conn'],$sql);

}



function selectCoregView($id){

    $sql="SELECT * FROM elitesleeper_coreg_users WHERE id=$id";

  return mysqli_query($GLOBALS['conn'],$sql);

}

public function coregusersinsert($coreuserdata){




		$query="insert into $this->coregeTable set ";
		$temp = array();
		foreach($coreuserdata as $key=>$value)
		{

			$temp[]="$key = '$value'";
		}
		$query.=implode(",",$temp);
		$query.="";

		mysqli_query($GLOBALS['conn'],$query);

	}

}
?>
