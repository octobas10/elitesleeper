<?php

class paging{

	public function paginglink($pagingcount,$class,$currpage,$function,$prvnxtlinks=true,$firstlastlinks=true){
	$links="<table width='100%'><tr class='".$class."'><td width='10%'>";	
	if($pagingcount>1){
		$links.="<span class='paging_text'>".$currpage .' '.'Of Pages'.' '.$pagingcount."</span></td><td width='90%'>";
		if($prvnxtlinks){
			if($currpage>1){
			    $links.= "<a class='".$class."' onclick='".$function."(1);' >First</a>&nbsp; ";   
		    }
		}	
		if($firstlastlinks){
			if($currpage > "1"){
				$pageno = $currpage - 1;
				$links.= "<a class='".$class."' onclick='".$function."($pageno); return false;' >Previous</a>&nbsp;";   
			}
		}
		if($pagingcount<4){
			$firstlimit=$pagingcount;
		}else{
			$firstlimit=4;	
		}
		for($i=1; $i<=$firstlimit; $i++){
			if(($pagingcount==$i)&&($currpage == $i)){
				$links.=" <a style='color:red; cursor:pointer;' class='".$class."'  onclick='".$function."($i);' return false;'  >$i</a>&nbsp;";
			}
			elseif($pagingcount==$i){
				$links.=" <a style='cursor:pointer;' class='".$class."'  onclick='".$function."($i);' return false;'  >$i</a>&nbsp;";
			}
			elseif ($currpage == $i){
				$links.=" <span class='".$class."' style='color:red;cursor:pointer;'>$i&nbsp;</span>|";
			}else{
				$links.=" <a style='cursor:pointer;' class='".$class."'  onclick='".$function."($i);' return false;'  >$i</a>&nbsp;|";
			}
		}
		$x=$currpage-3;
		$endstr=$x+6;
		if($x<=4){
			$x=$i;
			$endstr=$x+3;
		}else{
			$links.='....';
		}
		if($endstr>$pagingcount){
			$endstr=$pagingcount;
		}
		for($i=$x; $i<=$endstr; $i++){
			if(($pagingcount==$i)&&($currpage == $i)){
				$links.="  <span class='".$class."'  style='color:red;'>$i&nbsp;</span>&nbsp;";
			}elseif($pagingcount==$i){
				$links.=" <a class='".$class."'  onclick='".$function."($i); return false;'  >$i</a>&nbsp;";
			}
			elseif ($currpage == $i){
				$links.="  <span class='".$class."'  style='color:red;'>$i&nbsp;</span>&nbsp;|";
			}else{
				$links.=" <a class='".$class."'  onclick='".$function."($i); return false;'  >$i</a>&nbsp;| ";
			}
		}
		
		$endlinks=$i;
		if(($pagingcount-$endlinks)>6){
			$endlinks=$pagingcount-3;
			$links.='....';
		}
		for($i=$endlinks; $i<=$pagingcount; $i++){
			
			if($pagingcount==$i){
				$links.="  <span class='".$class."'>$i&nbsp;</span>&nbsp;";
			}
			elseif ($pageno == $i){
				$links.="  <span class='".$class."' style='color:red;'>$i&nbsp;</span>&nbsp;|";
			}else{
				$links.=" <a  class='".$class."' onclick='".$function."($i); return false;'  >$i</a>&nbsp;| ";
			}
		}
		
		
		if($currpage=="" || $currpage=="1"){
			$pageno=2;
		}else{
			$pageno=$currpage+1;
		}
		if($firstlastlinks){
			if($currpage < $pagingcount){
				$links .= "<a class='".$class."' onclick='".$function."($pageno); return false;' >Next</a>&nbsp;";   
			}
		}
		if($prvnxtlinks){
			if($currpage<$pagingcount){
			    $links .= "<a class='".$class."' onclick='".$function."($pagingcount); return false;' >Last</a>&nbsp;";
			}			
		}
	}			
	   
       
       $links.="</td></tr></table>";
	  
	   return $links;
	   
	
	}

}
