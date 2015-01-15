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
	
		
		if(isset($_POST['logout']))
			{
				session_destroy();
				setcookie('login', $d['login'], $time -60*60*24*7,null, null,false,true);
				setcookie('password', $d['password'], $time -60*60*24*7,null, null,false,true);
				header('location:login.php');

			}
			
			if($D_user['statue']!="administrator")
			header('location:agenda.php?year='.date('Y').'&month='.date('n'));
			
		if(isset($_POST['general']) && (isset($_GET['year'])==false OR isset($_GET['month'])==false OR is_numeric($_GET['month'])==false OR is_numeric($_GET['year'])==false OR (isset($_GET['month']) && $_GET['month']>12) OR (isset($_GET['month']) && $_GET['month']<1)OR (isset($_GET['year']) && $_GET['year']<2009) OR (isset($_GET['year']) && $_GET['year']>date('Y')) OR (isset($_GET['year']) && isset($_GET['month'])&& $_GET['year']==date('Y') && $_GET['month']>date('n') ) ))
		header('location:Statistiques.php?year='.date('Y').'&month='.date('n'));

		

			

?>


	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
				<?php echo'<title>Statistiques </title>';?>
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
						
								header('location:show.php?year='.$_POST['bar_year'].'&month='.$_POST['bar_month'].'&day='.$_POST['bar_day'].'&week='.week($_POST['bar_month'],$_POST['bar_day'],$nbrjour).'&type=jour*1');
						
						
						}
						
						echo'<div> <img src="'.$D_user['picture'].'" height="40" width="40" class="user"><p  class="user" > '.$D_user['first'].' '.$D_user['last'].' </p> ';
						echo'<a href="show_controle.php?type=user&operation=show&id='.$_SESSION['id'].'" class="bar_edit" > edit </a>';
						
						echo'<form action='.$_SERVER['PHP_SELF'].' method="POST">
											<input type="submit" name="logout" id="logout" title="logout" >
											</form><p class="clear" ></p></div>';
				
						?>	
		
							</div>
		
									
									<div id="menu">
													<?php 
													echo'<ul>
															<li> <a href="agenda.php?year='.date('Y').'&month='.date('n').'" > Agenda </a></li>
															<li> <a href="Nouvelles.php?limit=30"> Nouvelles </a></li>
															<li> <a href="Recherche.php"> Recherche </a></li>';
															
														if($D_user['statue']=="administrator")
														echo'
														<li> <a href="Statistiques.php" class="main" > Statistiques  </a></li>
															<li> <a href="Administration.php"> Administration </a></li>';
													echo'
													</ul>';
													?>
													
									</div><!-- div menu -->	
					</div><!-- div header -->
		<?php
		echo'<form method="POST" action="Statistiques.php?year='.date('Y').'&month='.date('n').'" >';
		echo'<input type="submit" name="general"  id="general" >';	
		echo'</form>';
		
		echo'<form method="GET" action="Statistiques.php" >';
						echo'<div  class="search_static">';
						echo'<input type="submit" name="search_main_botton"  id="search_main_botton" >';						
						echo'<p>Mesure : ' ;
						echo'<select name="test" id="static_test" >';
						echo'<option value="-1*-1">choisir</option>';
						
									$R1_test=$db->query('SELECT * FROM test GROUP BY parametre');
									
									while($D1_test=$R1_test->fetch())
									
									{
												echo'<optgroup label="'.$D1_test['parametre'].'">';
												$R_test=$db->prepare('SELECT * FROM test WHERE parametre=:parametre ');
												 $R_test->execute(array(
														'parametre'=>$D1_test['parametre']
														));
														
												while($D_test=$R_test->fetch())
												{
														
														$R2=$db->prepare('SELECT * FROM support  WHERE id_test=:id ');
														$R2->execute(array(
																'id'=>$D_test['id_test']
																));
														
														$D2=$R2->fetch();
														if($D2==false)
														
														echo'<option value="'.$D_test['id_test'].'*0">'.$D_test['name'].'</option>';
												
														else
														{
														
															echo'<option value="'.$D_test['id_test'].'*0">'.$D_test['name'].'</option>';
															
															$R2=$db->prepare('SELECT * FROM support  WHERE id_test=:id GROUP BY detail');
															$R2->execute(array(
																	'id'=>$D_test['id_test']
																	));
															
														
															while($D2=$R2->fetch())
															{
																echo'<optgroup class="detail" label="'.$D2['detail'].'">';
																$R3=$db->prepare('SELECT * FROM support  WHERE id_test=:id AND detail=:detail ');
																$R3->execute(array(
																	'id'=>$D_test['id_test'],
																	'detail'=>$D2['detail']
																	));
																while($D3=$R3->fetch())
																{
																	echo'<option value="'.$D_test['id_test'].'*'.$D3['id_support'].'">'.$D3['valeur'].'</option>';
																}
																echo'</optgroup>';
															}
														
														
														}
												}
												
						
									}
						echo'</select></p>';
						
						
					
						
						
						echo' <p class="date_search">  de :  ';
						echo' <select name="from_search_day" id="from_search">
							';
						
							for($i=1;$i<=31;$i++)
							{
								
								echo'<option value="'.$i.'"';
								
								 if($i==date('d')) echo' selected';
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						
							echo'<select name="from_search_month">
							';
							
							for($i=0;$i<12;$i++)
							{
								$g=$i+1;
								echo'<option value="'.$g.'"';
								
								 if($i==(date('n')-2)) echo' selected';
								
								echo'>'.mois($g).'</option>';		
								
							}
						echo'</select>';
						echo'<select name="from_search_year">
							';
							
							for($i=2009;$i<=date('Y');$i++)
							{
								
								echo'<option value="'.$i.'"';
								
							if($i==2012) echo' selected';
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						
						
						echo' A : <select name="to_search_day" id="from_search">
							';
							
							for($i=1;$i<=31;$i++)
							{
								
								echo'<option value="'.$i.'"';
								
							if($i==date('d')) echo' selected';
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						
							echo'<select name="to_search_month">
							';
							
							for($i=0;$i<12;$i++)
							{
								$g=$i+1;
								echo'<option value="'.$g.'"';
								
								if(($i+1)==date('n')) echo' selected';
								
								echo'>'.mois($g).'</option>';		
								
							}
						echo'</select>';
						echo'<select name="to_search_year">
							';
							
							for($i=2009;$i<=date('Y');$i++)
							{
								
								echo'<option value="'.$i.'"';
								
							 if($i==date('Y')) echo' selected';
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select></p>';
						
						
						
					echo'  </div></form>';
					
						

		if(isset($_POST['general']))
		{
			ECHO'<iframe src="agenda_static.php?year='.$_GET['year'].'&month='.$_GET['month'].'" height="300" marginheight="0" name="agenda_static" id="agenda_static" width="1000" SCROLLING="no" NORESIZE FRAMEBORDER="0" onLoad="Resize(\'agenda_static\');"></iframe><br><br>';
		}
							else if(isset($_GET['search_main_botton']))
							{
								$tab2=explode('*',$_GET['test']);
								
								if($tab2[0]!=-1 && $tab2[1]!=-1)
								{
												$a1=$_GET['from_search_year'];
												$a2=$_GET['to_search_year'];
												$m1=$_GET['from_search_month'];
												$m2=$_GET['to_search_month'];
												$d1=$_GET['from_search_day'];
												$d2=$_GET['to_search_day'];
												if($a2>date('Y') OR ($a2==date('Y') && $m2>date('n') ) or ($a2==date('Y') && $m2==date('n') && $d2>date('d')) )
												{
													$a2=date('Y');
													$m2=date('n');
													$d2=date('d');
												
												}
												
										$days1=count_all_days($a1,$m1,$d1);
										$days2=count_all_days($a2,$m2,$d2);
										
										
										if($tab2[1]==0)
										{
											$R1=$db->prepare('SELECT COUNT(*) AS numbers FROM done WHERE all_days>=:days1 AND all_days<=:days2 AND id_test=:id_test');
											$R1->execute(array(
														'days1'=>$days1,
														'days2'=>$days2,
														'id_test'=>$tab2[0]
														)
														);
												
											$number=$R1->fetch();
											$resultat=$number['numbers'];
										}
										
										else
										{
												$R1=$db->prepare('SELECT COUNT(*) AS numbers FROM done WHERE all_days>=:days1 AND all_days<=:days2 AND id_test=:id_test AND id_support=:id_support');
												$R1->execute(array(
														'days1'=>$days1,
														'days2'=>$days2,
														'id_test'=>$tab2[0],
														'id_support'=>$tab2[1]
														)
														);
												
													$number=$R1->fetch();
													$resultat=$number['numbers'];
										
										}
										$R2=$db->prepare('SELECT * FROM test WHERE id_test=:id_test');
										$R2->execute(array(
													'id_test'=>$tab2[0]
													));
										$D2=$R2->fetch();
										
										$tab=explode('*',$D2['period']);
										
										if($tab[0]=="jour")
										{
											
											$suppose=($days2-$days1)/($tab[1]);
											$suppose=variant_int($suppose);
										}
										else if($tab[0]=="semaine")
										{
											$suppose=($days2-$days1)/($tab[1]*7);
											$suppose=variant_int($suppose);
										
										}
										
										else if($tab[0]=="mois")
										{
											$suppose=(count_all_months($a2,$m2)-count_all_months($a1,$m1))/$tab[1];
											$suppose=variant_int($suppose);
										
										}
										
										
										if($suppose==0)  $suppose=1;
										$suppose=$suppose*$D2['re_test'];
										if($tab2[1]==0)
										{
											$R22=$db->prepare('SELECT COUNT(*) AS no FROM support WHERE  id_test=:id_test');
											$R22->execute(array(
														
														'id_test'=>$tab2[0]
														)
														);
												
											$number22=$R22->fetch();
											if($number22['no']==0) $resultat22=1;
											else $resultat22=$number22['no'];
											$suppose=$resultat22*$suppose;
										}
										
										
										echo '<div class="resultat"> <p >  Les statistiques pour la mesure : ';
										if($tab2[1]==0)
										echo '<span>'.$D2['name'].'</span> de Prosédure <span>'.$D2['parametre'].'</span>' ;
										else 
										{
												$R3=$db->prepare('SELECT * FROM support WHERE id_support=:id_support');
												$R3->execute(array(
													'id_support'=>$tab2[1]
													));
													$D3=$R3->fetch();
										
											echo '<span>'.$D2['name'].'</span> de Prosédure <span>'.$D2['parametre'].' <br> '.$D3['detail'].' :'.$D3['valeur'].'</span>' ;
											
										}
										echo'</p>';
										echo'<p> De '.$d1.' '.mois($m1).' '.$a1.' jusqu\'à '.$d2.' '.mois($m2).' '.$a2.' </p>';
										
										 $purcentage=($resultat/$suppose)*100;
										
										$P=variant_int($purcentage);
										
										echo'<p > <span class="purcent"> '.$P.'% <img src="'.purcent_image($P).'" width=15 height=45> </span>';
											
										$T=$suppose-$resultat;
										echo'Mesures effectué &nbsp;<span>'.$resultat.'</span> &nbsp;   &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;Mesures en attente <span>&nbsp;'.$T.'</span> </p></div>';
										$h=$db->prepare('SELECT * FROM done WHERE  all_days>=:days1 AND all_days<=:days2 ORDER BY time');
										$h->execute(array(
														'days1'=>$days1,
														'days2'=>$days2
														)
														);
												
											$y=$h->fetch();
										if($y==false)
										{
											$a4=$a1;
											$m4=$m1;
										}
										
										else
										{
											$a4=$y['year'];
											$m4=$y['month'];
										
										}
									ECHO'<iframe src="agenda_frame.php?year='.$a4.'&month='.$m4.'&test='.$_GET['test'].'" height="300" marginheight="0" name="agenda_frame" id="agenda_frame" width="1000" SCROLLING="no" NORESIZE FRAMEBORDER="0" onLoad="Resize(\'agenda_frame\');"></iframe><br><br>';
								}
							
							}
						?>
						
			<p class="clear"></p>
			</div><!-----------  ALL ---------------->
			
		</body>
		
</html>