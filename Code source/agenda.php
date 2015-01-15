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
		
		if(isset($_GET['year'])==false OR isset($_GET['month'])==false OR is_numeric($_GET['month'])==false OR is_numeric($_GET['year'])==false OR (isset($_GET['month']) && $_GET['month']>12) OR (isset($_GET['month']) && $_GET['month']<1)OR (isset($_GET['year']) && $_GET['year']<2009) OR (isset($_GET['year']) && $_GET['year']>date('Y')) OR (isset($_GET['year']) && isset($_GET['month'])&& $_GET['year']==date('Y') && $_GET['month']>date('n') ) )
		header('location:agenda.php?year='.date('Y').'&month='.date('n'));

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
				<?php echo'<title>plan de surveillance </title>';?>
				<link href="style.css" rel="stylesheet" type="text/css" media="screen" >
		</head>
		
		<body>
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
															<li> <a href="agenda.php?year='.date('Y').'&month='.date('n').'" class="main" > Agenda </a></li>
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
		
		$preview_year=$_GET['year']-1;
		$next_year=$_GET['year']+1;
			echo'
			
							<div class="year">';
							if($_GET['year']>2009)
							{
										echo'<a href="agenda.php?year='.$preview_year.'&month='.$_GET['month'].'" class="preview"> preview year </a>';
							}
										echo'<p> '.$_GET['year'].'</p> ';
							if($_GET['year']<date('Y'))
							{
										echo'<a href="agenda.php?year='.$next_year.'&month='.$_GET['month'].'" class="next"> next year </a>';
							}
							echo'</div><!---------- year --------->
							
							
							<p class="clear">
							<div class="agenda">
										
										<div class="mois">
												<ul>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=1"';
														if($_GET['month']==1) echo'class="here"';
														echo'
														> Janvier </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=2"';
														if($_GET['month']==2) echo'class="here"';
														echo'> Février </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=3"';
														if($_GET['month']==3) echo'class="here"';
														echo'> Mars </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=4"';
														if($_GET['month']==4) echo'class="here"';
														echo'> Avril </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=5"';
														if($_GET['month']==5) echo'class="here"';
														echo'> Mai </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=6"';
														if($_GET['month']==6) echo'class="here"';
														echo'> Juin </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=7"';
														if($_GET['month']==7) echo'class="here"';
														echo'> Juillet </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=8"';
														if($_GET['month']==8) echo'class="here"';
														echo'> août  </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=9"';
														if($_GET['month']==9) echo'class="here"';
														echo'> Septembre</a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=10"';
														if($_GET['month']==10) echo'class="here"';
														echo'> Octobre</a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=11"';
														if($_GET['month']==11) echo'class="here"';
														echo'> Novembre </a>	</li>
														<li><a href="agenda.php?year='.$_GET['year'].'&month=12"';
														if($_GET['month']==12) echo'class="here"';
														echo'> Décembre </a>	</li>
														
														
												</ul>
													<p class="clear">
										</div><!---------- mois --------->
										<div class="agenda_contenue">
										<div class="days">';
													
															$CAL_FRENCH=0;
															 
															$premierdumois = jddayofweek(cal_to_jd($CAL_FRENCH, $m, 1, $a), 0);
															if($premierdumois == 0){
																$premierdumois = 7;
															}

																echo '<p class="week"></p>';
															foreach($jours as $element)
															{
																	echo '<p>'.$element.'</p>';
															
															}

															$jour=1; 
															$joursmoisavant = $nbrjour[$m-1] - $premierdumois+2;	 
															$jourmoissuivant = 1; 
															if($m == 1){
																$joursmoisavant = $nbrjour[$m+11] - $premierdumois+2;  
															}

															 
															for($i=1;$i<40;$i++){
																if($i<$premierdumois && $i%7==1){
																$week=week($m-1,$joursmoisavant,$nbrjour);
																		
																		echo '<a class="week" href="show.php?year='.$a.'&month='.$m.'&day='.$joursmoisavant.'&type=semaine*1"> Semaine '.$week.'</a>';
																}
																else if($i>=$premierdumois && $i%7==1)
																{$week=week($m,$jour,$nbrjour);
																		
																		echo '<a class="week" href="show.php?year='.$a.'&month='.$m.'&day='.$jour.'&type=semaine*1"> Semaine '.$week.' </a>';
																
																
																}
																if($i<$premierdumois){	 
																echo '<p class="block">'.$joursmoisavant.'</p>';
																$joursmoisavant++;
																}else{
																	 if($i % 7 == 0 )
																	{
																				if($jour == date("d") && $m== date("n") && $a == date('Y'))
																				echo '<p class="to_day">'.$jour.'</p>';
																				
																				else 
																				{
																				echo '<p class="block">'.$jour.'</p>';
																				
																				}
																				
																	
																	}
																	else if($jour == date("d") && $m == date("n") && $i % 7 != 0 && $a == date('Y')){  
																		echo '<a class="to_day" href="show.php?year='.$a.'&month='.$m.'&day='.$jour.'&type=jour*1"> '.$jour;
																					
																					echo '</a> ' ;
					
																	}
															
																	 
																	 else if($jour > date("d") && $m == date("n") && $a == date('Y'))
																	 {
																	 echo '<p class="block">'.$jour.'</p>';
																	 }
																	
																	else   { 
																		
																		echo '<a class="normal_days" href="show.php?year='.$a.'&month='.$m.'&day='.$jour.'&type=jour*1"> '.$jour;
																		
								
								
					
					
																								
																				
																					echo '</a> ' ;
					
																	}
																	$jour++;	 
																
																 
																	if($jour > ($nbrjour[$m])){
																	
																		while($i % 7 != 0){
																	
																			echo '<p class="block">'.$jourmoissuivant.'</p>';
																			$i++;
																			$jourmoissuivant++;
																		}
																
																	$i=41;
																	}
															
															
																

																}

															}

								
												echo'
										</div> <!---------- days ------------>
										</div>
							</div><!---------- agenda --------->
			<p class="clear"></p>
			</div><!-----------  ALL ---------------->
			';?>
		</body>
		
</html>