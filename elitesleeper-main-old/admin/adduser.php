<?php
	include("class/adduserclass.php");
	include("class/category.php");
	include("class/country.php");
	$userObj = new usercontroller();
	$checkname = $userObj->checkUser($_POST['username']);
	$catObj=new CategoryController();
	$countryObj=new CountryController();
	$mainCategory=$catObj->mainCategory();
    $countryList=$countryObj->getCountryOption($_POST['country']);
    if($_POST['country']=='0' || !$_POST['country']){
    	$country_id="";
    }else{
    	$country_id=$_POST['country'];
    }
	
	$cityList=$countryObj->getCityOption($_POST['city'], $country_id);
	if(!isset($_SESSION["ADMIN_ID"])){
		echo "<script language='javascript'>window.parent.location= 'index.php'</script>";
	}
	###### code for insert and validation ######
	
	if(isset($_POST)){
		
		if($_POST['Submit']!=''){
			
			//echo "<pre>";
			//print_r($_POST);exit;
			$flag=true;	
			$username=$_POST['username'];
			$password=$_POST['password'];
			$cnf_password=$_POST['cnf_password'];
			$company_name=$_POST['company_name'];
			$category=$_POST['category'];
			$subcategory=$_POST['subcategory_'.$category.''];
			$country=$_POST['country'];
			$city=$_POST['city'];
			$company_info=$_POST['company_info'];
			$company_website=$_POST['company_website'];
			$company_phone=$_POST['company_phone'];
			$image1=$_FILES['image1']['tmp_name'];
			$image2=$_FILES['image2']['tmp_name'];
			
			if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $username)){ 
				$emailmsg="Please Enter Username in proper format.";
				$flag=false;
			}
			if($checkname=='false'){
				$usermsg="User already exist.";
				$flag=false;

			}

			if($password==''){
				$passmsg="Password can not be empty.";
				$flag=false;
			}
			if($cnf_password==''){
				$cnfpassmsg="Confirm Password can not be empty.";
				$flag=false;
			}
			if($password != $cnf_password){
				$passmatchmsg="Password and Confirm Password did not match.";
				$flag=false;
			}

			if($company_name==''){
				$companynamemsg="Company Name can not be empty.";
				$flag=false;
			}
			if($category==''){
				$manufacturermsg="Please select category.";
				$flag=false;

			}
			if($category!='' && $subcategory=='0'){
				$manufacturermsg="Please select category.";
				$flag=false;
			}
			
			if($services!='' && $select_services=='0'){
				$servicesmsg="Please select Service.";
				$flag=false;
			}
			if(!$image1){
				$image1msg="Please select image to Upload.";
				$flag=false;
			}
						
			if(isset($_FILES['image1']['tmp_name']) || isset($_FILES['image2']['tmp_name'] )){
					
				 $allowedExtensions = array("jpg","jpeg","gif","png"); 
				 foreach ($_FILES as $file) {
					if ($file['tmp_name'] > '') {
					  if (!in_array(end(explode(".",strtolower($file['name']))),$allowedExtensions)) {
							
							$filetypemsg='Only .jpg /.jpeg /.gif /.png  files are Allowed.';
							$flag=false;
		
					  }
					}
				  } 
			}

			
			if($country=='0'){
				$countrymsg="Please Select Country.";
				$flag=false;
			}
			if($city==''){
				$citymsg="Please Enter City.";
				$flag=false;
			}
			if($company_info==''){
				$companyinfomsg="Company Information can not be empty.";
				$flag=false;
			}
			if($company_website==''){
				$companysitemsg="Company Website can not be empty.";
				$flag=false;
			}
			if($company_phone==''){
				$companyphonemsg="Company Phone can not be empty.";
				$flag=false;
			}
			
			##### code to insert user details ########
			if($flag==true){
				
				if(isset($_FILES['image1']['tmp_name'])){
					$name=explode('.',$_FILES['image1']['name']);
					$ext=count($name)-1;
					$ext=$name[$ext];
					if($_FILES['image1']['tmp_name']){
						$image_name=time().basename($_FILES['image1']['tmp_name']).'.'.$ext;
						$first_image=$image_name;
						move_uploaded_file($_FILES['image1']['tmp_name'],'../uploads/'.$image_name);
						chmod('../uploads/'.$image_name,0777);
					}
				}
				if(isset($_FILES['image2']['tmp_name'])){
					$name=explode('.',$_FILES['image2']['name']);
					$ext=count($name)-1;
					$ext=$name[$ext];
					if($_FILES['image2']['tmp_name']){
						$image_name=time().basename($_FILES['image2']['tmp_name']).'.'.$ext;
						$second_image=$image_name;
						move_uploaded_file($_FILES['image2']['tmp_name'],'../uploads/'.$image_name);
						chmod('../uploads/'.$image_name,0777);
					}
				}

				
				$usercontroller=new usercontroller();
				$usercontroller->addUser($username,$password,$company_name,$category,$subcategory,$first_image,$second_image,$country,$city,$company_info,$company_website,$company_phone);
				echo "<script language=javascript>window.location.href='home.php?mode=manageuser'</script>";

			}
		}
	
	
	}
	
	
	
	###### End #####
	
	
?>
<div align="center" style="color:#FF0033;">
<?php if(isset($mes)){
		echo $mes;
	}
?>
</div>
<table border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF" class='grid_table' width="100%" onLoad="bodyclick();">
<form name="frm" method="post" action="" enctype="multipart/form-data">
 <tbody>
   <tr>
    <td colspan="3" align="left" valign="top">
      <table width="100%" border="0" cellpadding="0" align="center">
	    <tbody>
	      <tr>
	        <td rowspan="2" valign="bottom" align="left" width="33">
		     <img src="images/arrow.jpg" align="" width="30" height="25" />
		    </td>
	        <td colspan="2" align="left" height="25"><font size="+1" color="#119ee1"><strong>Add New Listing</strong></font></td>
		  </tr>
		</tbody>
	  </table>
	</td>
   </tr>
  
   <tr>
     <td valign="top" align="left" colspan="3">
	   <table width="100%" align="center" cellpadding="0" border="0">
	     <tbody>
		    <tr>
			  <td align="center"><b>Please complete the form below.<br></b>
			  </td>
			</tr>
		 </tbody>
	   </table>
     </td>
  </tr>
 
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
   <tr>
     <td colspan="3">&nbsp;</td>
  </tr>	 
  	<?php
		if($usermsg){
		?> <tr>
			  	<td></td>
			  	<td>
					<span style="color:#FF0066"><?=$usermsg?></span>
				</td>
  			</tr>
		<?php
		}
		?>

  <tr>
    <td width="40%" align="right" valign="top">
    	<font color="#119ee1">&nbsp;User Name&nbsp;&nbsp;</font>
    </td>
	<td width="26%">
		<?php
			if($emailmsg){
			?>
				<span style="color:#FF0066"><?=$emailmsg?></span>
				
			<?php
			}
		?>

		<input name="username" type="text" value="<?=$username?>">
	  	
	</td>
   
  </tr>	
  
    <tr>
	  	<td >&nbsp;</td>
  	</tr>
  
  <tr>
     <td align="right" valign="top">
     	<font color="#119ee1">&nbsp;Password&nbsp;&nbsp;</font>
     </td>
     <td>
     <?php
		if($passmsg){
		?>
			<span style="color:#FF0066"><?=$passmsg?></span>
			
		<?php
		}
		?>
		<?php
		if($passmatchmsg){
		?>
			<span style="color:#FF0066"><?=$passmatchmsg?></span>
			
		<?php
		}
	?>

     <input name="password" type="password" value="<?=$password?>">
         <div id="pass_div" style="display:none;"><font color="red">Please enter the password</font></div>
     </td>
	 <td></td>
  </tr>
  <tr>
	  	<td >&nbsp;</td>
  	</tr>
  
  <tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Confirm Password&nbsp;&nbsp;</font></td>
     <td>
     <?php
		if($cnfpassmsg){
		?>
			<span style="color:#FF0066"><?=$cnfpassmsg?></span>
		
		<?php
		}
	?>

     <input name="cnf_password" type="password" value="<?=$cnf_password?>">
        
     </td>
	 <td> </td>
  </tr>
  
  <tr>
  	<td >&nbsp;</td>
  </tr>
  
   <tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Company Name&nbsp;&nbsp;</font></td>
     <td>
 	<?php
		if($companynamemsg){
		?>
			<span style="color:#FF0066"><?=$companynamemsg?></span>
		
		<?php
		}
	?>

     <input name="company_name" type="text" value="<?=$company_name?>">        
     </td>

  </tr>
	
   <tr>
  	<td >&nbsp;</td>
  </tr>

  <tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Company category&nbsp;&nbsp;</font></td>
     <td>&nbsp;</td>

  </tr>

<?php
	if($manufacturermsg){
	?>
		<tr>
			<td></td>
			<td>
				<span style="color:#FF0066"><?=$manufacturermsg?></span>
			</td>
		</tr>
		
		
	<?php
	}
?>
<?

foreach($mainCategory as $id => $catvalue)
{
?>
   <tr>
   <td align="right" valign="top"></td>
  	<td><input type="radio" name="category" id="category" value="<?php echo $catvalue['CategoryId'];?>" <?php if($category==$catvalue['CategoryId']){?> checked="checked"<?php }?> >&nbsp;<?php echo $catvalue['CategoryName'];?></td>
  </tr>
    <tr>
  	<td >&nbsp;</td>
  </tr>

   <tr>
   <td align="right" valign="top"></td>
  	<td>
    <select name="subcategory_<?php echo $catvalue['CategoryId'];?>" id="subcategory">
	<option value="0">-Select-</option>
	<?
	$childCategory=$catObj->childCategory($catvalue['CategoryId']);
	foreach($childCategory as $key=>$subcate)
	{
	?>
	
	<option value="<? echo $subcate['CategoryId'];?>" <?php if($subcategory==$subcate['CategoryId']){?> selected="selected" <?php }?>><?echo $subcate['CategoryName'];?></option>
	<?}?>
	</select>
	</td>
  </tr>

  <tr>
  	<td >&nbsp;</td>
  </tr>
  <?}?>
  
  
   <tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Company images</font></td>
     <td>
     <?php
		if($image1msg){
		?>
			<span style="color:#FF0066"><?=$image1msg?></span>
			
		<?php
		}
		?>
		<?php
		if($filetypemsg){
		?>
			<span style="color:#FF0066"><?=$filetypemsg?></span>
			
		<?php
		}
	?>
	</tr>
	<tr>
	
	<td align="right" valign="top">Image1:</td>
	<td>
     <input name="image1" type="file">

        
     </td>

  </tr>
	<tr>
	
	<td align="right" valign="top">Image2:</td>
	<td>
     

        <input name="image2" type="file">
     </td>

  </tr>

   

  <tr>
  	<td >&nbsp;</td>
  </tr>

 <?php
	if($countrymsg){
	?>
		<tr>
			<td></td>
			<td>
				<span style="color:#FF0066"><?=$countrymsg?></span>
			</td>
		</tr>
		
		
	<?php
	}
?>
<tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Country&nbsp;&nbsp;</font></td>
    
     <td>
	 
<select name="country" id="country">
	 <option value="0">-Select-</option>
	<?echo $countryList;?>
</select>

	</td>
    <tr>
	  	<td >&nbsp;</td>
  	</tr>
  	<?php
	if($citymsg){
	?>
		<tr>
			<td></td>
			<td>
				<span style="color:#FF0066"><?=$citymsg?></span>
			</td>
		</tr>
		
		
	<?php
	}
?>

<tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;City&nbsp;&nbsp;</font></td>
      <td>
<input type="text" name="city" id="cityDiv" value="<?=$city?>">
	</td>
	</tr>
	   <tr>
	  	<td >&nbsp;</td>
  	</tr>

	<tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Company information</font></td>
     <td>
	<?php
		if($companyinfomsg){
		?>
			<span style="color:#FF0066"><?=$companyinfomsg?></span>
			
		<?php
		}
	?>

     <textarea name="company_info" cols="30"><?=$company_info?></textarea>
    
	</td>
    <tr>
	  	<td >&nbsp;</td>
  	</tr>

	
	<tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Company website</font></td>
     <td>
     <?php
		if($companysitemsg){
		?>
			<span style="color:#FF0066"><?=$companysitemsg?></span>
			
		<?php
		}
	?>

     <input name="company_website" type="text" value="<?=$company_website?>">
    
	</td>
    <tr>
	  	<td >&nbsp;</td>
  	</tr>


	<tr>
     <td align="right" valign="top"><font color="#119ee1">&nbsp;Company phone number</font></td>
     <td>
     <?php
		if($companyphonemsg){
		?>
			<span style="color:#FF0066"><?=$companyphonemsg?></span>
			
		<?php
		}
	?>
     <input name="company_phone" type="text" value="<?=$company_phone?>">

         
	</td>
    <tr>
	  	<td >&nbsp;</td>
  	</tr>


   
  <tr align="center">
    <td colspan="3" align="center">
    <input name="Submit" value="Submit" type="submit">

  </tr>	
	 	
 </tbody>
 </form>
</table>	
<script language="javascript" src="js/admin_validation.js"></script>
