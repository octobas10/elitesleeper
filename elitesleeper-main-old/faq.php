<?php
$page_obj=new pages();
$res=$page_obj->fetchPage(2);
$page_detail=mysqli_fetch_array($res);
//echo $res;

?>

                          <tr>
						      <td class="heading1txt">
							  <h1><?=$page_detail['title'];?></h1>
								<?php if($page_detail['status']==1){ ?>
								<?=$page_detail['details']?>	
								<?php } else {  echo "<h3>Coming Soon</h3>"; } ?>
							</td>	
                          </tr>
						  <tr>
                            <td align="right"><p>&nbsp;</p></td>
                          </tr>
						  <tr>
                            <td align="right"><a href="?mode=ordernow"><img src="images/buybottolenow.gif" alt="" width="132" height="30" border="0" /></a></td>
                          </tr>
                   


