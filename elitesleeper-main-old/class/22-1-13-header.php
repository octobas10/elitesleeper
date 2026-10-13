<?php
include_once("admin/class/product.php");
class Header	
{
	function show_header1()
	{
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
            <li class="<?php if($page=='ordernow') { echo $active; } ?>"><a href="index.php?mode=ordernow">Order Now</a></li>
            <li class="<?php if($page=='faq') { echo $active; } ?>"><a href="index.php?mode=faq">FAQs</a></li>
            <li class="<?php if($page=='contact') { echo $active; } ?>"><a href="index.php?mode=contact">Contact Us</a></li>
            <li class="<?php if($page=='optoutlink') { echo $active; } ?> last"><a href="index.php?mode=optoutlink">Opt Out </a></li>
          </ul>
        </div>
      </div>
       <div class="banner-top">
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