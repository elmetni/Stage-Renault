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

		if($D_user['type']=="disable")
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
			
			

?>
																			



	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
		<title> Recherche  </title>
		<link href="style.css" rel="stylesheet" type="text/css" media="screen" >
		</head>
		
		<body>
		<script language="JavaScript">
		
		
												<!--
												function Resize(id){
													var newheight;
													

													if(document.getElementById){
														newheight=document.getElementById(id).contentWindow.document .body.scrollHeight+40;
														
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
					
					echo'<p> effectué:'.$done.'&nbsp;  en attente:'.$y.'&nbsp;&nbsp;';
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
															<li> <a href="Nouvelles.php?limit=30" > Nouvelles </a></li>
															<li> <a href="Recherche.php" class="main"> Recherche </a></li>';
															
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

						echo'<form method="GET" action="Recherche.php" >';
						echo'<div  class="search">';
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
						echo'<option value="1" selected> </option>';
							for($i=1;$i<=31;$i++)
							{
								
								echo'<option value="'.$i.'"';
								
								
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						
							echo'<select name="from_search_month">
							';
							echo'<option value="1" selected> </option>';
							for($i=0;$i<12;$i++)
							{
								$g=$i+1;
								echo'<option value="'.$g.'"';
								
								
								
								echo'>'.mois($g).'</option>';		
								
							}
						echo'</select>';
						echo'<select name="from_search_year">
							';
							echo'<option value="2007" selected> </option>';
							for($i=2009;$i<=date('Y');$i++)
							{
								
								echo'<option value="'.$i.'"';
								
							
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						
						
						echo' A : <select name="to_search_day" id="from_search">
							';
							echo'<option value="1" selected> </option>';
							for($i=1;$i<=31;$i++)
							{
								
								echo'<option value="'.$i.'"';
								
							
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select>';
						
							echo'<select name="to_search_month">
							';
							echo'<option value="1" selected> </option>';
							for($i=0;$i<12;$i++)
							{
								$g=$i+1;
								echo'<option value="'.$g.'"';
								
								
								
								echo'>'.mois($g).'</option>';		
								
							}
						echo'</select>';
						echo'<select name="to_search_year">
							';
							echo'<option value="2060" selected> </option>';
							for($i=2009;$i<=date('Y');$i++)
							{
								
								echo'<option value="'.$i.'"';
								
							 
								
								echo'>'.$i.'</option>';		
								
							}
						echo'</select></p>';
						
						
						
					echo'  </div></form>';
					if(isset($_GET['search_main_botton']))
										
					ECHO'<iframe src="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=60&order=time&nature=DESC" height="300" marginheight="0" name="search_body" id="search_body" width="950" SCROLLING="no" NORESIZE FRAMEBORDER="0" onLoad="Resize(\'search_body\');"></iframe>';
					
					
					?>
				</div>
						<p class="clear"></p>
				<BR><BR>
				</div> <!---------------- ALL -------------------->
		</body>
		
</html>
