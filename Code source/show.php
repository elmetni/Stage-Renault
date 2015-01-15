<?php session_start();
		
		
																				

		

				if(!isset($_SESSION['loged']) or $_SESSION['loged']!=1 )
				{
						header('location:login.php');
				
				
				}
				
		
			try
			{
				$db=new PDO('mysql:host='.$_SERVER['HTTP_HOST'].';dbname=surveillance','root','');
			}
			catch(exception $e)
			{
				die('Error :'.$e->getmessage());
			}
		$R_user=$db->prepare('SELECT * FROM user WHERE id_user=:id');
		$R_user->execute(array(
							'id'=>$_SESSION['id']
							));
		$D_user=$R_user->fetch();
		
		if($D_user['type']=="disable" OR $D_user==false)
		{
				session_destroy();
				header('location:login.php');
		}
		if ((date('Y') % 4) == 0){
					$nbrjour = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
				}else{
					$nbrjour = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
					
				
				}
		require_once("fonctions.php");	
		if(isset($_GET['type'])==false OR isset($_GET['year'])==false OR isset($_GET['month'])==false OR is_numeric($_GET['month'])==false OR is_numeric($_GET['year'])==false OR (isset($_GET['month']) && $_GET['month']>12) OR (isset($_GET['month']) && $_GET['month']<1)OR (isset($_GET['year']) && $_GET['year']<2009) OR (isset($_GET['year']) && $_GET['year']>date('Y')) OR (isset($_GET['year']) && isset($_GET['month'])&& $_GET['year']==date('Y') && $_GET['month']>date('n') ) OR  ( $_GET['year']==date('Y') && $_GET['month']==date('n') && $_GET['day']>date('d') ) )
		header('location:show.php?year='.date('Y').'&month='.date('n').'&day='.date('d').'&week='.week(date('n'),date('d'),$nbrjour).'&type=jour*1');
		
		
		
		if(isset($_POST['logout']))
			{
		session_destroy();
		setcookie('login', $d['login'], $time -60*60*24*7,null, null,false,true);
		setcookie('password', $d['password'], $time -60*60*24*7,null, null,false,true);
		header('location:login.php');

			}
			
			

?>
																			



	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
		<?php 
		require_once("fonctions.php");
		if($_GET['month']==1)$month="Janvier";
		else if($_GET['month']==2) $month="Février";
		else if($_GET['month']==3) $month="Mars";
		else if($_GET['month']==4) $month="Avril";
		else if($_GET['month']==5) $month="Mai";
		else if($_GET['month']==6) $month="Juin";
		else if($_GET['month']==7) $month="Juillet";
		else if($_GET['month']==8) $month="août";
		else if($_GET['month']==9) $month="Septembre";
		else if($_GET['month']==10) $month="Octobre";
		else if($_GET['month']==11) $month="Novembre";
		else if($_GET['month']==12) $month="Décembre";
		 
		echo'<title>'.$_GET['day'].' '.$month.' '.$_GET['year'].' semain'.week($_GET['month'],$_GET['day'],$nbrjour).'</title>';
		
		
		
		
		?>
		<link href="style.css" rel="stylesheet" type="text/css" media="screen" >
		</head>
		
		<body>
		<script language="JavaScript">
		
		
												<!--
												function Resize(id){
													var newheight;
													

													if(document.getElementById){
														newheight=document.getElementById(id).contentWindow.document .body.scrollHeight;
														
													}

													document.getElementById(id).height= (newheight) + "px";
													
												}
												//-->
												</script>
				<div id="all" >
				<div id="header">
		<div id="user_bar">

				<?php
				include("config.php");
	
														
															$m = $_GET['month'];
															$a = $_GET['year'];

									
															if ($m == "") { $m = $m_donne; }
															if ($a == "") { $a = $a_donne; }

														
															if (($a % 4) == 0){
																$nbrjour = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
															}else{
																$nbrjour = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
															}
															
					$done=0;
					$not_done=0;
					$purcentage=0;
					if($D_user['statue']=="administrator")
					{
							$R=$db->query('SELECT * FROM test');							
					
					
					}
					else if($D_user['statue']=="user")
					{
							$R=$db->prepare('SELECT * FROM test WHERE id_user=:id');
							$R->execute(array(
										'id'=>$_SESSION['id']
									));
							
							
							
					}
					
							while($D=$R->fetch())
							{$re_test=$D['re_test'];
							for($i=1;$i<=$re_test;$i++)
							{
									$R2=$db->prepare('SELECT * FROM support WHERE id_test=:id_test');
									$R2->execute(array(
												'id_test'=>$D['id_test']
											));
									$D2=$R2->fetch();
									
									
									if($D2==false){
													$not_done++;
													if(test_done(date('Y'),date('n'),date('d'),$D['period'],$D['id_test'],-1,$i)!=false) $done++;
													}
									else {
											while($D2!=false)
											{
												
													$not_done++;
													if(test_done(date('Y'),date('n'),date('d'),$D['period'],$D['id_test'],$D2['id_support'],$i)!=false) $done++;
													$D2=$R2->fetch();
													
											
											}
										
										
										}
								}
								}
								
						
					$y=$not_done-$done;
					
					echo'<p> effectué:'.$done.'&nbsp; en attente:'.$y.'&nbsp;&nbsp;';
					if($not_done == 0)
					{
						$purcentage=100;
						
					}
					else $purcentage=($done/$not_done)*100;
					
					echo variant_int($purcentage).'%</p><img src="'.purcent_image($purcentage).'" height=40 width=15 class="purcent"></img> ' ;
					echo' <form action='.$_SERVER['PHP_SELF'].' method="POST">
							<select name="bar_day" id="bar_day">
							';
							for($i=1;$i<=31;$i++)
							{
								
								echo'<option value="'.$i.'"';
								
								if($i==date('d'))  
								echo 'selected';
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						
							echo'<select name="bar_month">
							';
							for($i=0;$i<12;$i++)
							{
								$g=$i+1;
								echo'<option value="'.$g.'"';
								
								if($g==date('n'))  
								echo 'selected';
								
								echo'>'.mois($g).'</option>';		
								
							}
						echo'</select>';
						echo'<select name="bar_year">
							';
							for($i=2009;$i<=date('Y');$i++)
							{
								
								echo'<option value="'.$i.'"';
								
								if($i==date('Y'))  
								echo 'selected';
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						echo'<input type="submit" name="bar_go" id="bar_go">';
						
						echo'</form>';
						if(isset($_POST['bar_go']))
						{
						
								header('location:show.php?year='.$_POST['bar_year'].'&month='.$_POST['bar_month'].'&day='.$_POST['bar_day'].'&type=jour*1');
						
						
						}
						
						echo'<div>  <img src="'.$D_user['picture'].'" height="40" width="40" class="user"><p  class="user" > '.$D_user['first'].' '.$D_user['last'].' </p> ';
						echo'<a href="show_controle.php?type=user&operation=show&id='.$_SESSION['id'].'" class="bar_edit" > edit </a>';
						
						echo'<form action='.$_SERVER['PHP_SELF'].' method="POST">
											<input type="submit" name="logout" id="logout" title="logout" >
											</form><p class="clear" ></p></div>';
				
						?>	
		
							</div>
		
									
									<div id="menu">
													<?php 
													echo'<ul>
															<li> <a href="agenda.php?year='.date('Y').'&month='.date('n').'"  > Agenda </a></li>
															<li> <a href="Nouvelles.php?limit=30"> Nouvelles </a></li>
															<li> <a href="Recherche.php"> Recherche </a></li>';
															
														if($D_user['statue']=="administrator")
														echo'
														<li> <a href="Statistiques.php"> Statistiques  </a></li>
															<li> <a href="Administration.php"> Administration </a></li>';
													echo'
													</ul>';
													?>
													
									</div><!-- div menu -->	
					</div><!-- div header -->
					<?php 		
						echo'<div class="year"> <p>Semaine '.week($_GET['month'],$_GET['day'],$nbrjour).': '.$_GET['day'].' '.$month.' '.$_GET['year'].' </p> ';
						$purcentage=purcent($_GET['year'],$_GET['month'],$_GET['day'],$_SESSION['id'])	;
						
						echo '<p class="purcent_show"><img src="'.purcent_image($purcentage).'" height=40 width=15 > ' ;
						echo variant_int($purcentage).'%</p></div>';
							
								echo'<p class="clear"></p><div class="menu_test"> <div>';
							if($D_user['statue']=="administrator")
							{
							$R=$db->query('SELECT * FROM test GROUP BY period');
								}
								
							else if($D_user['statue']=="user")
									{
											$R=$db->prepare('SELECT * FROM test WHERE id_user=:id_user  GROUP BY period');
											$R->execute(array(
												
												'id_user'=>$_SESSION['id']
												));
												
									
									}
									
							while($D=$R->fetch())
							{
								$tab=explode('*',$D['period']);
								if($tab[1]==1)
								{
								echo'<a href="show.php?year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&type='.$D['period'].'" ';
								if($D['period']==$_GET['type'])
								echo'class="here"';
								echo'> '.$tab[0].' </a>';
								}
								else
								{
								echo'<a href="show.php?year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&type='.$D['period'].'" ';
								if($D['period']==$_GET['type'])
								echo'class="here"';
								echo'>'.$tab[1].' '.$tab[0].' </a>';
								}
							}
						
							echo'</div></div><!---------------- menu test------------------>
									
									
									<div class="right">
									
									';
									// $R7=$db->prepare('SELECT COUNT(id_test) AS nbr_test  FROM test WHERE period=:type ');
									// $R7->execute(array(
												// 'type'=>$_GET['type']
												// ));
									
									// $nbr_test=$R7->fetch();
									// $resultat_nbr_test=$nbr_test['nbr_test'];
									
									// $R8=$db->prepare('SELECT COUNT(id_done) AS nbr_done  FROM done WHERE period=:type AND day=:day AND month=:month AND year=:year ');
									// $R8->execute(array(
												// 'type'=>$_GET['type'],
												// 'day'=>$_GET['day'],
												// 'month'=>$_GET['month'],
												// 'year'=>$_GET['year']
												
												
												// ));
									
									// $nbr_done=$R8->fetch();
									// $resultat_nbr_done=$nbr_done['nbr_done'];
									
									
									
									if($D_user['statue']=="administrator")
									{
									
									$R2=$db->prepare('SELECT * FROM test WHERE period=:type  GROUP BY parametre');
									$R2->execute(array(
												'type'=>$_GET['type']
												));
												}
									else if($D_user['statue']=="user")
									{
											$R2=$db->prepare('SELECT * FROM test WHERE period=:type AND id_user=:id_user  GROUP BY parametre');
											$R2->execute(array(
												'type'=>$_GET['type'],
												'id_user'=>$_SESSION['id']
												));
												
									
									}
									$b=0;
									$src="";
								    while($D2=$R2->fetch())
									{
											echo '<div class="parametre">'.$D2['parametre'].'</div>';
											if($D_user['statue']=="administrator")
											{
											$R3=$db->prepare('SELECT * FROM test WHERE period=:type AND parametre=:parametre');
											$R3->execute(array(
														'type'=>$_GET['type'],
														'parametre'=>$D2['parametre']
														));
											}
											else if($D_user['statue']=="user")
											{
												$R3=$db->prepare('SELECT * FROM test WHERE period=:type AND parametre=:parametre AND id_user=:id_user');
											$R3->execute(array(
														'type'=>$_GET['type'],
														'parametre'=>$D2['parametre'],
														'id_user'=>$_SESSION['id']
														));
											}
											while($D3=$R3->fetch())
												{
													$i=0;
													$re_test=$D3['re_test'];
													for($i=1;$i<=$re_test;$i++)
													{
													$R_test1=$db->prepare('SELECT * FROM support WHERE id_test=:id ');
													$R_test1->execute(array(
														'id'=>$D3['id_test']
														));
													$D_test1=$R_test1->fetch();
													if($D_test1!=false)
													{
															echo '<p class="test_title">'.$D3['name'];
																	if($re_test!=1)
																	echo ' ('.$i.') ';																	
																	echo ' </p>';
															$R4=$db->prepare('SELECT * FROM support WHERE id_test=:id_test GROUP BY detail');
															$R4->execute(array(
																'id_test'=>$D3['id_test']
																));
															echo'<ul  class="test_detail">';
															while($D4=$R4->fetch())
															{
																echo '<li>Par '.$D4['detail'].'</li>';
																$R5=$db->prepare('SELECT * FROM support WHERE id_test=:id_test AND detail=:detail');
																$R5->execute(array(
																'id_test'=>$D3['id_test'],
																'detail'=>$D4['detail']
																));
																echo'<ol>';
																while($D5=$R5->fetch())
																{
																			if(test_done($_GET['year'],$_GET['month'],$_GET['day'],$_GET['type'],$D3['id_test'],$D5['id_support'],$i)!=false)
																			{
																					echo'<div class="done_test"> <a class="contenue"  target="left_frame"  href="done.php?id_done='.test_done($_GET['year'],$_GET['month'],$_GET['day'],$_GET['type'],$D3['id_test'],$D5['id_support'],$i).'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'">'.$D5['valeur'].'</a></div>';
																					if($b==0)
																					{
																						$src='done.php?id_done='.test_done($_GET['year'],$_GET['month'],$_GET['day'],$_GET['type'],$D3['id_test'],$D5['id_support'],$i).'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'];
																						$b=1;
																					}
																			}
																			else
																			{
																					echo'<div class="not_done_test"><a  class="contenue" target="left_frame"  href="not_done.php?id_test='.$D3['id_test'].'&id_support='.$D5['id_support'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&nombre='.$i.'">'.$D5['valeur'].'</a></div>';
																					
																					if($b==0)
																					{
																						$src='not_done.php?id_test='.$D3['id_test'].'&id_support='.$D5['id_support'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&nombre='.$i;
																						$b=1;
																					}
																			
																			}
																}
																echo'</ol>';
															
															}
															echo'</ul><p class="clear"></p>';
													}
													else{
															if(test_done($_GET['year'],$_GET['month'],$_GET['day'],$_GET['type'],$D3['id_test'],-1,$i)!=false)
															{
																	echo'<div class="done_test">   <a  class="contenue" target="left_frame" href="done.php?id_done='.test_done($_GET['year'],$_GET['month'],$_GET['day'],$_GET['type'],$D3['id_test'],-1,$i).'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'">'.$D3['name'];
																	if($re_test!=1)
																	echo ' ('.$i.') ';																	
																	echo '</a></div>';
																	if($b==0)
																					{
																						$src='done.php?id_done='.test_done($_GET['year'],$_GET['month'],$_GET['day'],$_GET['type'],$D3['id_test'],-1,$i).'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'];
																						$b=1;
																					
																					}
															}
															else
															{
																	echo'<div class="not_done_test">   <a class="contenue"  target="left_frame"  href="not_done.php?id_test='.$D3['id_test'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&id_support=-1&nombre='.$i.'">'.$D3['name'];
																	if($re_test!=1)
																	echo ' ('.$i.') ';																	
																	echo '</a></div>';
																	if($b==0)
																					{
																						$src='not_done.php?id_test='.$D3['id_test'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&id_support=-1&nombre='.$i;
																						$b=1;
																					
																					}
																		}
													
													
													}
													}
												}
									
									}
									echo'
									<p class="clear"></p>
									</div> <!---------------- rightt------------------>
									<div class="left">';
								
									if($src!="")
									
									ECHO'<iframe src="'.$src.'" height="200" marginheight="0" name="left_frame" id="left_frame" width="680" SCROLLING="no" NORESIZE FRAMEBORDER="0" onLoad="Resize(\'left_frame\');"></iframe>';
									
									
									
									
									
							
							
					
					?>
				</div>
						<p class="clear"></p>
				<BR><BR>
				</div> <!---------------- ALL -------------------->
		</body>
		
</html>
