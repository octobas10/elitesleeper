<?php
$page_obj=new pages();
$res=$page_obj->fetchPage(1);
$page_detail=mysqli_fetch_array($res);
//echo $res;

?>


                          <tr>
						    <h1><?=$page_detail['title'];?></h1>
                              <td class="heading1txt">
								<?php if($page_detail['status']==1){ ?>
								<?=$page_detail['details']?>	
								<?php } else {  echo "<h3>Comming Soon</h3>"; } ?>
							</td>	
                          </tr>
						
                              

