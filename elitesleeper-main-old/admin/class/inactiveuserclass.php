<?php
include("./includes/connect.php");
	class usercontroller{
	
		var $dbUserTable = 'wrec_users';
		//var $dbUserSubscriptionTable = 'kv_UserSubscription';
        function addUserSubscription($userid,$categoryid,$subcategoryid){
			$sql="insert into $this->dbUserSubscriptionTable(UserId,CategoryId,SubCategoryId) values('$userid','$categoryid','$subcategoryid')";
			
			$result=mysqli_query($GLOBALS['conn'],$sql);
		}
	
		function editUserSubscription($id,$categoryid,$subcategoryid){
			$sql="update $this->dbUserSubscriptionTable SET CategoryId='$categoryid',SubCategoryId='$subcategoryid' where ReceiveId='$id'";
			
			$result=mysqli_query($GLOBALS['conn'],$sql);

		}

         function fetch_from_email(){
	     $sql="select * from  wrec_from_email";
	     $result=mysqli_query($GLOBALS['conn'],$sql);
		 $value=mysqli_fetch_array($result);
	     return $value['from_email'];
	    }
		
		function fetchAll(){
			$sql="select * from $this->dbUserTable where is_active='0'";
			$query=mysqli_query($GLOBALS['conn'],$sql);
			
			return $query;
		}
		function fetchRow(){
			$sql="select * from $this->dbUserTable where id='".$_GET['userid']."'";
			$query=mysqli_query($GLOBALS['conn'],$sql);
			$result=mysql_fetch_object($query);
			
			return $result;
		}
		
		function getUserSubscription($userid){
			$sql="select * from $this->dbUserSubscriptionTable where UserId='".$userid."'";
			$query=mysqli_query($GLOBALS['conn'],$sql);
			
			return $query;
		}

		
		
		
		
		
		
		function filterSearch($searchname="",$searchby="",$column1="",$qsort="",$lsort=""){
			//echo $searchname; echo $searchby;
			if($searchname!=""){
				if($searchby!="" && $searchby=='name'){
			$query1= "and username like '%".$searchname."%'";
				}
				else if($searchby!="" && $searchby=='email'){
			$query1= "and email like '%".$searchname."%'";
				}
			}
			$search =$query1;
			/*if($searchby!="" && $searchby=='name'){
				$query2="and SubCategoryId IN($subcategory)";
			}		
			$search="$query1 $query2 ";
			
			if($qsort!=""){
				$sql="SELECT * FROM $this->dbUserTable where 1=1 ".$search." order by ".$column1." ".$qsort; 
			}
			else{
				$sql="select * from $this->dbUserTable where 1=1 $search";
			}*/

			if($qsort!=""){
				$sql="SELECT * FROM $this->dbUserTable where 1=1 and is_active='0' and approve_deny='0' ".$search." order by ".$column1." ".$qsort;
			}
			else{
				 $sql="select * from $this->dbUserTable where 1=1 $search and is_active='0'"; 
			}

			
			$query=mysqli_query($GLOBALS['conn'],$sql);
			return $query;
		}
		
		function filterSearch2($searchname="",$searchby="",$column1="",$qsort="",$lsort=""){
			//echo $searchname; echo $searchby;
			if($searchname!=""){
				if($searchby!="" && $searchby=='name'){
			 $query1= "and username like '%".$searchname."%'";
				}
				else if($searchby!="" && $searchby=='email'){
			$query1= "and email like '%".$searchname."%'";
				}
			}
			$search =$query1;
			/*if($country!="" && $country!=0){
			$query1= "and CountryId='".$country."'";
			}
			if($subcategory!=""){
				$query2="and SubCategoryId IN($subcategory)";
			}		
			$search="$query1 $query2 ";*/
			
			if($qsort!=""){
				 $sql="SELECT * FROM $this->dbUserTable where 1=1 and is_active='0' and approve_deny='0' ".$search." order by ".$column1." ".$qsort; 
			}else{
				 $sql="select * from $this->dbUserTable where 1=1 $search and is_active='0'  and approve_deny='0' "; 
			}

			
			$query=mysqli_query($GLOBALS['conn'],$sql);
			
			return $query;
		}
		function updatestatus($status,$userid){
			$sql="UPDATE `wrec_users` SET `is_active` = '$status',`approve_deny` = '1' WHERE `wrec_users`.`id` ='$userid'"; 
			$result=mysqli_query($GLOBALS['conn'],$sql);

				if($result=='1')
				{
					$sql1="select * from wrec_users where id='$userid'";
					$result1=mysqli_query($GLOBALS['conn'],$sql1);
					$value=mysqli_fetch_array($result1);
					$email=$value['email']; 
					$username=$value['username'];
					$firstname=$value['firstname'];
					$lastname=$value['lastname'];
					$mail=$this->send_approve_Mail($email,$firstname,$lastname,$username);			 
					}

		}
		function denystatus($status,$userid){
		    $sql="UPDATE `wrec_users` SET `is_active` = '$status',`approve_deny` = '1' WHERE `wrec_users`.`id` ='$userid'"; 
            $result=mysqli_query($GLOBALS['conn'],$sql);
		}
	
	 function send_approve_Mail($email,$firstname,$lastname,$username)
        {   
		  $from_email=$this->fetch_from_email();
		    $name=ucfirst($firstname);
		    $to = $email;
			$from =$from_email;
			$subject= "Account Activation.";
            $message= "<span style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>Dear ".$name.",<br><br>"."Welcome to our website Wrecked.<br>Your account has been activated. Please login using this link. <br/><br/>".'<a href=http://www.braintechnosys.biz/wreckedweb/login.php>www.braintechnosys.biz/wreckedweb/login.php</a>'."<br/>
"."<br />"."<br/><br/>Regards<br/>The Wrecked Team! </span>";
			$xheaders = "X-Mailer: PHP\n";
			$xheaders.="From:".$from ."\n";
			$xheaders .= "Content-Type: text/html; charset=iso-8859-1\n";
			if(!mail($to,$subject,$message,$xheaders)){
                            return 0;
			}else{
                            return 1;
                        }
        }
    function selectuseractivation($id)
		{
			$sql="select * from `wrec_registration_activation` where id ='".$id."'"; 
			$query=mysqli_query($GLOBALS['conn'],$sql);
			$res = mysqli_fetch_array($query);
			return $res;
		}
	
	 function updateuseractivation($condition){
              $sql="UPDATE `wrec_registration_activation` SET `on_off` = '$condition' WHERE `wrec_registration_activation`.`id` =1;";
			  $result=mysqli_query($GLOBALS['conn'],$sql);
              return $result;
	         }
	
	}
?>