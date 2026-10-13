<?php

##################################################
###    Auther Name: Amar Gupta                 ###
###    Copy Right : Braintechnosys             ###
###    Date: 06/05/2010                        ###
###    @version 0.1                            ###
##################################################

/**
*@admin class used to handle login action
*/
class admin{
	var $dbUserTable = 'elitesleeper_admin';
	function loginUser($adminname, $password){			
		$sql="select * from $this->dbUserTable where admin_name='".$adminname."' and admin_password='".md5($password)."'";
		$result=mysqli_query($GLOBALS['conn'],$sql)or die('not selected');			 
		if(mysqli_num_rows($result)){	
			$query_result = mysqli_fetch_assoc($result);
			$_SESSION['ADMIN_ID']=$query_result['id'];
			$_SESSION['ADMIN_NAME']=$query_result['admin_name'];
			return true;
		}else{
			return false;
		}
	}	
	function passwordValid($password)
	{
		$query = "select * from $this->dbUserTable where admin_password ='".md5($password)."'";
		return @mysqli_num_rows(mysqli_query($GLOBALS['conn'],$query));
	}	
	function setPassword($password)
	{
		$query = "UPDATE $this->dbUserTable SET admin_password='".md5($password)."' where id=1";		
		return mysqli_query($GLOBALS['conn'],$query);
	}
}
?>