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
			
			if(isset($_GET['limit'])==false OR is_numeric($_GET['limit'])==false  or (isset($_GET['limit']) && $_GET['limit']<30))
			header('location:Nouvelles.php?limit=30');

?>
																			



	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
		<title> Les Nouvelles  </title>
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
					
					echo'<p> effectué:'.$done.'&nbsp;  en attente:'.$y.'&nbsp;&nbsp; ';
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
															<li> <a href="Nouvelles.php?limit=30" class="main"> Nouvelles </a></li>
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
						$R_frame=$db->query('SELECT * FROM done  ORDER BY time DESC ');
						$D_frame=$R_frame->fetch();
						ECHO'<iframe src="mini_done.php?id_done='.$D_frame['id_done'].'&year='.$D_frame['year'].'&month='.$D_frame['month'].'&day='.$D_frame['day'].'" height="320" marginheight="0" name="mini_done" id="mini_done" width="500" SCROLLING="no" NORESIZE FRAMEBORDER="0" ></iframe>';
						
						
						$R_new=$db->query('SELECT * FROM done  ORDER BY time DESC  LIMIT 0,'.$_GET['limit'].'');
						echo'<table>';
						echo'<tr><th class="test" > les derniers Mesures </th><th class="temp" > Temps d\'ajout </th></tr>';
						while($D_new=$R_new->fetch())
						{
						echo'<tr><td class="test" > ';
								$R=$db->prepare('SELECT * FROM test WHERE id_test=:id_test');
								$R->execute(array(
													'id_test'=>$D_new['id_test']
													));
									$D=$R->fetch();
						echo '<a href="mini_done.php?id_done='.$D_new['id_done'].'&year='.$D_new['year'].'&month='.$D_new['month'].'&day='.$D_new['day'].'" target="mini_done">'.$D['name'].' ';
						if($D['re_test']!=1)
								echo '('.$D_new['number'].'): ';
						if($D_new['id_support']!=-1)
						{
								$R2=$db->prepare('SELECT * FROM support WHERE id_support=:id_support');
								$R2->execute(array(
													'id_support'=>$D_new['id_support']
													));
									$D2=$R2->fetch();
							echo $D2['detail'].':'.$D2['valeur'];
						}
						echo ' </a>';
						echo'</td><td class="temp">'.$D_new['time'].'</td></tr>';
						}
						
						echo'</table>';
						
						$R7=$db->query('SELECT COUNT(id_done) AS number  FROM done ');
						
					
						$number=$R7->fetch();
						$resultat=$number['number'];
						
						if($resultat>$_GET['limit'])
						{
						$new_limit=$_GET['limit']+30;
						echo '<a id="more" href="Nouvelles.php?limit='.$new_limit.'"  > here </a>';
						
						}
						
					?>
				</div>
						<p class="clear"></p>
				<BR><BR>
				</div> <!---------------- ALL -------------------->
		</body>
		
</html>
