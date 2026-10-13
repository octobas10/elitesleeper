<?php
include_once("admin/class/product.php");
class Header	
{
	function queryParam(){
		$qstr='';
		if($_GET){
			foreach (($_GET) as $key=>$val){
				if($key !=='mode'){
					$qstr .= "&$key=$val";	
				}
			}
		} 
		return $qstr;
	}
	function show_header1()
	{
	  $params= $this->queryParam();
	  $page=$_GET['mode'];
	  $active='active';
	?>
	<!--Header Start from here-->
      <div id="header">
        <div class="logo"> <img src="images/logo.jpg" /> </div>
        <div class="menu-top">
           <ul>
            <li class="<?php if($page=='') { echo $active ; } ?>"><a href="index.php">Home</a></li>
            <li class="<?php if($page=='howitwork') { echo $active; } ?>"><a href="index.php?mode=howitwork">How it Works</a></li>
            <li class="<?php if($page=='ordernow') { echo $active; } ?>"><a href="index.php?mode=ordernow<?php echo $params; ?>">Order Now</a></li>
            <li class="<?php if($page=='faq') { echo $active; } ?>"><a href="index.php?mode=faq">FAQs</a></li>
            <li class="<?php if($page=='contact') { echo $active; } ?>"><a href="index.php?mode=contact">Contact Us</a></li>
            <li class="<?php if($page=='optoutlink') { echo $active; } ?> last"><a href="index.php?mode=optoutlink">Opt Out </a></li>
          </ul>
        </div><center>
<iframe id='a71dc529' name='a71dc529' src='https://ads.elitemate.com/adframe.php?n=a71dc529&amp;what=zone:349&amp;target=_blank' framespacing='0' frameborder='no' scrolling='no' width='728' height='90'><a href='https://ads.elitemate.com/adclick.php?n=a71dc529' target='_blank'><img src='https://ads.elitemate.com/adview.php?what=zone:349&amp;n=a71dc529' border='0' alt=''></a></iframe>
<script language='JavaScript' type='text/javascript'><!--
if (typeof phpAds_adg == 'undefined') {
	document.write('<scr'+'ipt language="JavaScript" type="text/javascript" src="https://ads.elitemate.com/adg.js"></scr'+'ipt>');
}
//--></script>
      </div>
	  <?php if($page=='') { ?> <div class="banner-top"> <?php }else{ ?> 
	   <div class="<?php echo 'inner_'.$page; ?>">
	  <?php  }?>
      
	  <div class="bannerbuy">
        <?php
	  /***********Code to get the price dynamic**************/
		$userObj = new product();					 
		$userResult = $userObj->fetchAll();
		if(mysqli_num_rows($userResult))
		{
		while($userRecord = mysqli_fetch_assoc($userResult)){ 
		
		echo '<div class="banertxt">60 Capsules - $<span>'.$userRecord['price'].'</span></div>';
		
		} }
		
	    /***********End***********************/
	 
	  ?>

	  <div class="bannerbtn"><a href="?mode=ordernow"><img src="images/buybtn.png" alt="" width="131" height="38" border="0" /></a></div>
	  </div>
	 
	  </div> 	  
      <!--Header End here-->
	<?php
	}
	function show_header2()
	{
	?>
	
	
	<?php
	}
}
?>