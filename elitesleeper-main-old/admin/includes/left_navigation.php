<script language="JavaScript">
$(document).ready(function() {
	$(".admin-left-menu").accordion({
		accordion:true,
		speed: 200,
		closedSign: '+',
		openedSign: '--'
	});
var errorDiv = $('.active:visible').first();
var scrollPos = errorDiv.offset().top;
$('#menu').scrollTop(scrollPos - 300);

});
</script>

<ul id="menu" class="lft_sidebar admin-left-menu">
	<li class="<?php if($_GET['mode']==''){ echo 'active';} ?>"><a href="home.php" >Dashboard</a></li>
	<li><a href="#">Order Management</a>
		<ul style="display:none;">
			<li class="<?php if(in_array($_GET['mode'], array('manageorder','vieworder','editorder'))){ echo 'active';} ?>"><a href="?mode=manageorder" >Manage Order</a></li>
			<li class="<?php if(in_array($_GET['mode'], array('customerlist'))){ echo 'active';} ?>"><a href="?mode=customerlist" >Customer list</a></li>
			<li class="<?php if(in_array($_GET['mode'], array('coregusers','viewcoregusers'))){ echo 'active';} ?>"><a href="?mode=coregusers" >Coreg Users</a></li>
			<li class="<?php if(in_array($_GET['mode'], array('inactiveuser'))){ echo 'active';} ?>"><a href="?mode=inactiveuser" >Suprission list</a></li>
		</ul>
	</li>
    <li><a href="#">Cost Management</a>
		<ul style="display:none;">
			<li class="<?php if(in_array($_GET['mode'], array('manageproduct','editproduct'))){ echo 'active';} ?>"><a href="?mode=manageproduct" >Manage Cost</a></li>
		</ul>
	</li>
	 <li>
	 <a href="#">Newsletter</a>
		<ul style="display:none;">
		   <li class="<?php if(in_array($_GET['mode'], array('bannerlist','viewbannerlist','editbannerlist','addbannerlist'))){ echo 'active';} ?>"><a href="?mode=bannerlist" >Newsletter Management</a></li>
		</ul>
	</li>
	 <li>
	 <a href="#">Email Relayer</a>
		<ul style="display:none;">
		   <li class="<?php if(in_array($_GET['mode'], array('emailrelayeradmin'))){ echo 'active';} ?>"><a href="?mode=emailrelayeradmin" >Email Relayer Management</a></li>
		   <li class="<?php if(in_array($_GET['mode'], array('emailrelayer'))){ echo 'active';} ?>"><a href="?mode=emailrelayer" >Email Relayer Report</a></li>
		</ul>
	</li>
	<li><a href="#">Static Page Management</a>
		<ul style="display:none;">
			<li class="<?php if(in_array($_GET['mode'], array('pagemanage','editpages'))){ echo 'active';} ?>"><a href="?mode=pagemanage" >Manage Pages</a></li>
		</ul>
	</li>
	<li class="<?php if(in_array($_GET['mode'], array('changepassword'))){ echo 'active';} ?>"><a href="?mode=changepassword" >Change Password</a></li>
	<li class="<?php if(in_array($_GET['mode'], array('logout'))){ echo 'active';} ?>"><a href="?mode=logout" >Logout</a></li>
</ul>
