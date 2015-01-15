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
			if(isset($_GET['year'])==false OR isset($_GET['month'])==false OR is_numeric($_GET['month'])==false OR is_numeric($_GET['year'])==false OR (isset($_GET['month']) && $_GET['month']>12) OR (isset($_GET['month']) && $_GET['month']<1)OR (isset($_GET['year']) && $_GET['year']<2009) OR (isset($_GET['year']) && $_GET['year']>date('Y')) OR (isset($_GET['year']) && isset($_GET['month'])&& $_GET['year']==date('Y') && $_GET['month']>date('n') ) )
		header('location:agenda_static.php?year='.date('Y').'&month='.date('n').'&test='.$_GET['test']);
			
?>
																			



	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
		
		<link href="style.css" rel="stylesheet" type="text/css" media="screen" >

		</head>
		
		<body>
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
		$preview_year=$_GET['year']-1;
		$next_year=$_GET['year']+1;
			echo'<br><br><div class="year">';
							if($_GET['year']>2009)
							{
										echo'<a href="agenda_static.php?year='.$preview_year.'&month='.$_GET['month'].'" class="preview"> preview year </a>';
							}
										echo'<p> '.$_GET['year'].'</p> ';
							if($_GET['year']<date('Y'))
							{
										echo'<a href="agenda_static.php?year='.$next_year.'&month='.$_GET['month'].'" class="next"> next year </a>';
							}
							echo'</div><!---------- year --------->
							
							
							<p class="clear">
							<div class="agenda">
										
										<div class="mois">
												<ul>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=1"';
														if($_GET['month']==1) echo'class="here"';
														echo'
														> Janvier </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=2"';
														if($_GET['month']==2) echo'class="here"';
														echo'> Février </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=3"';
														if($_GET['month']==3) echo'class="here"';
														echo'> Mars </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=4"';
														if($_GET['month']==4) echo'class="here"';
														echo'> Avril </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=5"';
														if($_GET['month']==5) echo'class="here"';
														echo'> Mai </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=6"';
														if($_GET['month']==6) echo'class="here"';
														echo'> Juin </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=7"';
														if($_GET['month']==7) echo'class="here"';
														echo'> Juillet </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=8"';
														if($_GET['month']==8) echo'class="here"';
														echo'> août  </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=9"';
														if($_GET['month']==9) echo'class="here"';
														echo'> Septembre</a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=10"';
														if($_GET['month']==10) echo'class="here"';
														echo'> Octobre</a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=11"';
														if($_GET['month']==11) echo'class="here"';
														echo'> Novembre </a>	</li>
														<li><a href="agenda_static.php?year='.$_GET['year'].'&month=12"';
														if($_GET['month']==12) echo'class="here"';
														echo'> Décembre </a>	</li>
														
														
												</ul>
													<p class="clear">
										</div><!---------- mois --------->
										<div class="agenda_contenue">
										<div class="days_static">';
													
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
																		
																		echo '<a class="week" target="_top" href="show.php?year='.$a.'&month='.$m.'&day='.$joursmoisavant.'&type=semaine*1"> Semaine '.$week.'</a>';
																}
																else if($i>=$premierdumois && $i%7==1)
																{$week=week($m,$jour,$nbrjour);
																		
																		echo '<a class="week" target="_top" href="show.php?year='.$a.'&month='.$m.'&day='.$jour.'&type=semaine*1"> Semaine '.$week.' </a>';
																
																
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
																		echo '<a class="to_day" target="_top" href="show.php?year='.$a.'&month='.$m.'&day='.$jour.'&type=jour*1"> '.$jour;
																					$purcentage=purcent($_GET['year'],$_GET['month'],$jour,$_SESSION['id']);
																					echo '<img src="'.purcent_image($purcentage).'" height=30 width=10 class="purcent"></a> ' ;
					
																	}
															
																	 
																	 else if($jour > date("d") && $m == date("n") && $a == date('Y'))
																	 {
																	 echo '<p class="block">'.$jour.'</p>';
																	 }
																	
																	else   { 
																		
																		echo '<a class="normal_days"  target="_top" href="show.php?year='.$a.'&month='.$m.'&day='.$jour.'&type=jour*1"> '.$jour;
																					
																				$purcentage=purcent($_GET['year'],$_GET['month'],$jour,$_SESSION['id'])	;
																					echo '<img src="'.purcent_image($purcentage).'" height=30 width=10 class="purcent"> </a> ' ;
					
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
		
		