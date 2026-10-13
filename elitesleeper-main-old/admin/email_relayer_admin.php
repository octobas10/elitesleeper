<?php
/**
 * @author MarcAnthony Castillo
 * @created 20161201
 * @copyright GC0D3.LLC
 */
include("class/relayer.php");

if($_GET['msg']=='edit'){
	$msg = "<font class='msg_success'>Email Relayer Edited successfully";
}

$userObj = new relayer();

if(!empty($_SESSION['msg'])){
	$msg=$_SESSION['msg'];
	unset($_SESSION['msg']);
}

?>

	<!-- Add jQuery library -->
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="../lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="../source/jquery.fancybox.js?v=2.1.4"></script>
	<link rel="stylesheet" type="text/css" href="../source/jquery.fancybox.css?v=2.1.4" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="../source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="../source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="../source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="../source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="../source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});


		});
	</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
	</style>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Manage Email Relayer</td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;" colspan="2">
         <form name='pagemanage' method='post' action="">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr><td style="color:red;" class="done_mess" colspan="3"><?=$msg?><?php $msg="";?></td></tr>
				<tr>
                <td align="center" colspan="2" >
                <table width="100%"  border="0" align="left" class="alt_table" cellpadding="2" cellspacing="2" class='admin-table'>
					<tr>
					<th  width='10%' align="center">
					Relayer
					</th>
					<th  width='10%' align="center">
					From Email
					</th>
					<th  width='10%' align="center">
					Reply Email
					</th>
					<th  width='10%' align="center">
					Return Email
					</th>
					<th  width='10%' align="center">
					Send (days)
					</th>
					<th  width='10%' align="center">
					Delay (sec)
					</th>
					<th  width='10%' align="center">
					Mail Host
					</th>
					<th  width='10%' align="center">
					Action
					</th>
					</tr>
					<?php
					$userResult = $userObj->relayerlistSelect();
					if(mysqli_num_rows($userResult)){
						while($userRecord = mysqli_fetch_assoc($userResult)){
					?>
					<tr>
					<td align='left'>
					<?php echo $userRecord['relayer'];?>
					</td>
					<td align='left'>
					<?php echo htmlspecialchars($userRecord['fromemail']);?>
					</td>
					<td align='left'>
					<?php echo htmlspecialchars($userRecord['replyemail']);?>
					</td>
					<td align='left'>
					<?php echo htmlspecialchars($userRecord['returnemail']);?>
					</td>
					<td align='left'>
					<?php echo $userRecord['sendday'];?>
					</td>
					<td align='left'>
					<?php echo $userRecord['delay'];?>
					</td>
					<td align='left' >
					<?php echo (is_numeric($userRecord['mailhost'])) ? long2ip($userRecord['mailhost']):'' ?>
					</td>
					<td align='center'>
					<span>
					<a title="edit" border="0" href="home.php?mode=editrelayer&edit=edit&id=<?=$userRecord['id']?>" style="color:green;"><img border="0"  title="Edit" alt="edit" src="images/edit.png"></a>
					</td>
					</tr>
					<?php
						}
					}
					?>
</table>
</td>
</table>
</form>
</td>
</tr>
</table>
