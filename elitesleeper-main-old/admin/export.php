<?php 
ob_start();
session_start();
error_reporting(0);

include("config.php");
include("class/dbClass.php");
include("includes/connect.php");
include("class/order.php");
include("class/paging.php");
include("class/newsletter.php");
		if(@$_POST['submit'])
		{
			$date = $_POST['date1'];
			$date1 = $_POST['date2'];
			$limit = $_POST['pageno'];
			$num_rows=0;
			$suprission_status=0;
			$table_name='elitesleeper_coreg_users';
			if($date!="" && $date1!="")
			{  
				
					$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM $table_name WHERE suprission_status='$suprission_status' and enterdate BETWEEN '".$date."' AND '".$date1."' order by enterdate desc") or die(mysql_error());
			}
			else if($date!="" && $date1=="")
			{
				$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM $table_name WHERE suprission_status='$suprission_status' and enterdate ='".$date."' order by enterdate desc") or die(mysql_error());
			}
			else if($date=="" && $date1!="")
			{
				$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM $table_name WHERE suprission_status='$suprission_status' and enterdate ='".$date1."' order by enterdate desc") or die(mysql_error());
			}
			else{
				$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM $table_name where suprission_status='$suprission_status' and order by enterdate desc") or die(mysql_error());
			}
		 
			


$i=0;
while ($row = mysqli_fetch_assoc($sql)) {
$newArray[$i] =$row; 
$i++;
}

$out = "";
if(count($newArray) > 0){	
	$j=1;
	$out[0] = array( 'Email','FirstName ','LastName ','Address ','City ','State ','Zip ','Country ','Workphone ','Homephone ','DOB ','Gender ','IP Address ','URL ','Lead Date ' );
	
	foreach($newArray as $rs1){ 
		if(empty($rs1['email'])){$rs1['email']='NULL';}
		if(empty($rs1['name'])){$rs1['name']='NULL';}
		if(empty($rs1['lastname'])){$rs1['lastname']='NULL';}
		if(empty($rs1['address'])){$rs1['address']='NULL';}
		if(empty($rs1['city'])){$rs1['city']='NULL';}
		if(empty($rs1['state'])){$rs1['state']='NULL';}
		if(empty($rs1['zip'])){$rs1['zip']='NULL';}
		if(empty($rs1['country'])){$rs1['country']='NULL';}
		if(empty($rs1['workphone'])){$rs1['workphone']='NULL';}
		if(empty($rs1['cellprefix'])){$rs1['cellprefix']='NULL';}
		if($rs1['dob']=="0000-00-00"){$rs1['dob']='00/00/0000';}else{$rs1['dob']=date('m/d/Y',strtotime($rs1['dob']));}
		if(empty($rs1['gender'])){$rs1['gender']='NULL';}
		if(empty($rs1['ip'])){$rs1['ip']='NULL';}
		if(empty($rs1['url'])){$rs1['url']='NULL';}
		if(empty($rs1['enterdate'])){$rs1['enterdate']='NULL';}
		$out[$j]= array($rs1['email'],$rs1['name'],$rs1['lastname'],$rs1['address'],$rs1['city'],$rs1['state'],$rs1['zip'],$rs1['country'],$rs1['workphone'],$rs1['cellprefix'],$rs1['dob'],$rs1['gender'],$rs1['ip'],$rs1['url'],date('m/d/Y',strtotime($rs1['enterdate'])));
		$j++;
	}
	//echo $out;
}

header("Content-type: text/csv");  
header("Cache-Control: no-store, no-cache");  
header('Content-Disposition: attachment; filename="CoregUserOfEliteRev.csv"');  
  
$outstream = fopen("php://output",'w');  

foreach( $out as $row )  
{  
    fputcsv($outstream, $row, ',', '"');  
}  
  
fclose($outstream);  
			
		}
   ?>