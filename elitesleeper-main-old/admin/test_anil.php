 <?php 
if(($_GET['sort']=='desc')&&($_GET['column']=='status')){
	
	?>
	<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=status&sname=<?=$sname?>">
	<img class="arrow" src='images/up_arrow.png' border='0'></a>
<?php }
  elseif(($_GET['sort']=='asc')&&($_GET['column']=='status')){
?>
	<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=status&sname=<?=$sname?>">
	<img class="arrow" src='images/down_arrow.png' border='0'></a>
	
<?php }else{
	?>
<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=status&sname=<?=$sname?>">
<img class="arrow" src='images/up_arrow.png' border='0'></a>
<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&status=email&sname=<?=$sname?>">
<img class="arrow" src='images/down_arrow.png' border='0'></a>
<?php 
}
?>