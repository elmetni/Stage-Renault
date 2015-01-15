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
			
?>
																			



	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
		
		<link href="style.css" rel="stylesheet" type="text/css" media="screen" >
		<title> Administraction  </title>
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
															<li> <a href="Recherche.php"> Recherche </a></li>';
															
														if($D_user['statue']=="administrator")
														echo'
														<li> <a href="Statistiques.php"> Statistiques  </a></li>
															<li> <a href="Administration.php" class="main"> Administration </a></li>';
													echo'
													</ul>';
													?>
													
									</div><!-- div menu -->	
							
							<div class="left_menu">
																				<?php
																				
																					
																					echo '<ul>
																						<li><a  href="Administration.php"';
																						if(isset($_GET['go'])==false  )
																									echo'class="here"';
																						echo'			
																						>Les utilisateurs </a></li>
																						<li><a   href="Administration.php?go=test"';
																						if(isset($_GET['go'])&& $_GET['go']=="test")
																						echo'class="here"';
																						echo'
																						>  Les mesures '; 
																						echo'</a></li>
																						<li><a   href="Administration.php?go=add_test"';
																						if(isset($_GET['go'])&& $_GET['go']=="add_test")
																						echo'class="here"';
																						echo' > Ajouter mesure </a></li>
																						<li><a   href="Administration.php?go=add_user"';
																						if(isset($_GET['go'])&& $_GET['go']=="add_user")
																						echo'class="here"';
																						echo'
																						>Ajouter utilisateur </a></li>
																						
																						
																					</ul>
																					<p class="clear"> </p>';
																					if(isset($_GET['go'])&&isset($_GET['search']) && $_GET['go']!="message" && $_GET['go']!="send" && $_GET['go']!="finder" && $_GET['go']!="confirme") header('location:friends.php');
																						
																				
																				
																				?>
																				
																				</div>
																				<div class="right_administrastion">
																				<?php
																						if(isset($_GET['go']) && $_GET['go']=="add_test")
																						{
																								$error=0;
																								echo'<div class="add_test">';
																								echo'<form method="POST"  action="Administration.php?go=add_test" >';
																								
																									echo'<div class="add_test_title"><p  > Ajouter une Mesure </p></div>';
																									
																									
																									
																										
																									echo'<p class="main">La mesure  : <input type="text" name="add_test_name"';
																									
																									if(isset($_POST['add_test_name'])) echo 'value="'.$_POST['add_test_name'].'"';
																									echo'>';
																									if(isset($_POST['add_test']) && $_POST['add_test_name']=="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																									
																									 echo' </p>';
																								
																									
																									echo'<p class="main"> Fréquence   : ' ;
																									
																									 echo'<input type="text" name="add_test_period_nombre"  class="nombre" ';
																									
																									if(isset($_POST['add_test_period_nombre'])) echo 'value="'.$_POST['add_test_period_nombre'].'"';
																									ELSE echo'value="1"';
																									echo'>';
																									 echo' * ';
																									 echo ' <select name="add_test_period"><option value="jour"';
																									 if(isset($_POST['add_test_period']) && $_POST['add_test_period']=="jour") echo ' selected';
																									 else if(!isset($_POST['add_test_period'])) ' selected';
																									 echo' > Jour </option><option value="semaine"  ';
																									 if(isset($_POST['add_test_period']) && $_POST['add_test_period']=="semaine") echo ' selected';
																									 echo'> Semaine </option><option value="mois" ';
																									 if(isset($_POST['add_test_period']) && $_POST['add_test_period']=="mois") echo ' selected';
																									 echo'> Mois </option></select>';
																									if(isset($_POST['add_test']) && $_POST['add_test_period_nombre']<1)
																												{
																														echo'<span class="error" ><img src="images/waring2.png">Invalide valeur</span>' ;
																														$error=1;
																														
																												
																												}
																												echo'</p>';
																									 echo'<input type="submit" name="add_test" class="add_test" value="">';
																									 
																									 echo'<p class="main">Attribut : ' ;
																									  echo ' <select name="Atribut"><option value="-1" ';
																									 if(isset($_POST['Atribut']) && $_POST['Atribut']=="-1") echo ' selected';
																									 else if(!isset($_POST['Atribut'])) ' selected';
																									 echo' > Non </option><option value="1"  ';
																									 if(isset($_POST['Atribut']) && $_POST['Atribut']=="1") echo ' selected';
																									 
																									 echo'>Oui  </option></select></p>';
																									  echo'<p class="main">Mesurable : ' ;
																									  echo ' <select name="mesurable"><option value="1" ';
																									 if(isset($_POST['mesurable']) && $_POST['mesurable']=="1") echo ' selected';
																									 else if(!isset($_POST['mesurable'])) ' selected';
																									 echo' > Oui </option><option value="-1"  ';
																									 if(isset($_POST['mesurable']) && $_POST['mesurable']=="-1") echo ' selected';
																									 
																									 echo'>Non  </option></select></p>';
																									 echo'<p class="main"> Nombre de fois :</p><p>qu\'il faut l\'effectuer  son fréquence   : ' ;
																									 echo'<input type="text" name="add_test_re_test"  class="nombre" ';
																									if(isset($_POST['add_test_re_test'])) echo 'value="'.$_POST['add_test_re_test'].'"';
																									else echo 'value="1"';
																									echo'></p>';
																									 
																									echo'<p class="main">La procédure :  ';
																									
																									if(isset($_POST['add_test']) && $_POST['add_test_parametre']==-1 && $_POST['add_test_creat_parametre']=="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																									echo'		<p>Vous pouvez choisir une procédure déja exisiter : ';
																									echo'<select name="add_test_parametre">';
																									echo'<option value="-1"';
																									  if(!isset($_POST['add_test_parametre'])) ' selected';
																									 echo'> Choisir </option></p>';
																									
																									$R_add=$db->query('SELECT * FROM test GROUP BY parametre ');	
																									while($D_add=$R_add->fetch())
																									{
																									
																									echo'<option value="'.$D_add['parametre'].'" ';
																									 if(isset($_POST['add_test_parametre']) && $_POST['add_test_parametre']==$D_add['parametre']) echo ' selected';
																									 
																									 echo' > '.$D_add['parametre'].' </option>';
																									
																									}
																									echo'</select>';
																									 echo'<p> Ou bien créer une nouvelle procédure :' ;
																									 echo'<input type="text" name="add_test_creat_parametre"';
																									
																									if(isset($_POST['add_test_creat_parametre'])) echo 'value="'.$_POST['add_test_creat_parametre'].'"';
																									echo'></p>';
																									 
																									 echo'<p class="main"> L\'utilisateur :';
																									  if(isset($_POST['add_test']) && $_POST['add_test_user']==-1 )
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																									 
																									echo' </p><p>qui va effectuer la mesure : ' ;
																									  echo'<select name="add_test_user">';
																										echo'<option value="-1" ';
																									  if(!isset($_POST['add_test_user'])) ' selected';
																									 echo'> Choisir </option>';
																										
																										$R_add=$db->query('SELECT * FROM user ');	
																										while($D_add=$R_add->fetch())
																										{
																										
																										echo'<option value="'.$D_add['id_user'].'" ';
																									 if(isset($_POST['add_test_user']) && $_POST['add_test_user']==$D_add['id_user']) echo ' selected';
																									 
																									 echo'> '.$D_add['first'].' '.$D_add['last'].' </option>';
																										
																										}
																										echo'</select></p> ';
																										
																											echo'<p class="main" > Information sure la mesure  :</p>';
																											echo'<textarea  name="add_test_info" COLS="80" ROWS="5"  WRAP="virtual">';
																									
																									if(isset($_POST['add_test_info'])) echo  $_POST['add_test_info'];
																									echo'</textarea>';
																								echo'</div>';
																						
																						
																						
																						
																						
																						
																						
																				
																									echo'<p id="clear"></p>';
																											if(isset($_POST['add_test']) && $error==0)
																											{
																						
																								
																								$period=$_POST['add_test_period'].'*'.$_POST['add_test_period_nombre'];
																								if($_POST['add_test_parametre']!=-1 && $_POST['add_test_creat_parametre']=="")
																									$parametre=$_POST['add_test_parametre'];
																								 else 
																								$parametre=$_POST['add_test_creat_parametre'];
																								
																								$R12=$db->prepare('INSERT INTO test VALUES(\'\',:name,:parametre,:info,:id_user,:period,:re_test,:attribut,NOW(),:mesurable)');
																												
																								$R12->execute(array(
																												
																												'name'=>$_POST['add_test_name'],
																												'parametre'=>$parametre,
																												'info'=>$_POST['add_test_info'],
																												'id_user'=>$_POST['add_test_user'],
																												'period'=>$period,
																												're_test'=>$_POST['add_test_re_test'],
																												'attribut'=>$_POST['Atribut'],
																												'mesurable'=>$_POST['mesurable']
																												
																											)
																											);
																								
																						
																						
																						
																									header('location:Administration.php?go=test');
																						
																						
																						
																							}
																						}
																						
																						else if(isset($_GET['go']) && $_GET['go']=="add_user")
																						{
																								$error=0;
																								echo'<div class="add_test">';
																								echo'<form method="POST"  action="Administration.php?go=add_user"  enctype="multipart/form-data">';
																								
																									echo'<div class="add_test_title"><p  > Ajouter un Utilisateur </p></div>';
																									
																									
																									
																										
																									echo'<p class="main">* Nom  : <input type="text" name="add_user_first" ';
																									
																									if(isset($_POST['add_user_first'])) echo 'value="'.$_POST['add_user_first'].'"';
																									echo'>';
																									
																									
																									 echo' </p>';
																									if(isset($_POST['add_user']) && $_POST['add_user_first']=="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																									 echo'<p class="main">* Prénom  : <input type="text" name="add_user_last"';
																									
																									if(isset($_POST['add_user_last'])) echo 'value="'.$_POST['add_user_last'].'"';
																									echo'>';
																									
																									
																									 echo' </p>';
																									 if(isset($_POST['add_user']) && $_POST['add_user_last']=="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																									  echo'<p class="main">* Identifiant (pour se connecter): <input type="text" name="add_user_login"';
																									
																									if(isset($_POST['add_user_login'])) echo 'value="'.$_POST['add_user_login'].'"';
																									echo'>';
																									
																									
																									 echo' </p>';
																									 if(isset($_POST['add_user']) && $_POST['add_user_login']=="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																									else if(isset($_POST['add_user']) && $_POST['add_user_login']!="")
																											{
																													$g=$db->prepare('SELECT * FROM user WHERE login=:login');
																													$g->execute(array(
																															'login'=>$_POST['add_user_login']
																															));
																													$t=$g->fetch();
																													if($t!=false)
																													{
																													
																													echo'<span class="error" ><img src="images/waring2.png"> Identifiant déja exist </span>' ;
																													$error=1;
																													
																													}
																													
																													
																													
																											
																											}
																									  echo'<input type="submit" name="add_user" class="add_test" value="">';
																									 
																									  echo'<p class="main">* Mot de passe &nbsp; : &nbsp;&nbsp;<input type="password" name="add_user_password"';
																									
																									if(isset($_POST['add_user_password'])) echo 'value="'.$_POST['add_user_password'].'"';
																									echo'></P>';
																									  
																									  if(isset($_POST['add_user']) && $_POST['add_user_password']=="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																											else if(isset($_POST['add_user']) && strlen($_POST['add_user_password'])<8  && $_POST['add_user_password']!="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> le mot de passe doit être de plus de 8 caractères</span>' ;
																													$error=1;
																													
																											
																											}
																										echo'<p class="main">* Re-Mot de passe  : <input type="password" name="add_user_re_password"';
																									
																									if(isset($_POST['add_user_re_password'])) echo 'value="'.$_POST['add_user_re_password'].'"';
																									echo'></P>';
																									 if(isset($_POST['add_user']) && $_POST['add_user_re_password']!=$_POST['add_user_password'])
																											{
																													echo'<span class="error" ><img src="images/waring2.png">s\'il vous plaît confirmer le mot de passe</span>' ;
																													$error=1;
																													
																											
																											}
																									
																									  echo'<p class="main">* Type de compte  : ' ;
																									  echo ' <select name="statue"><option value="user" ';
																									  
																									  if(isset($_POST['statue'])&& $_POST['statue']=="user") echo ' selected ';
																									  
																									  echo'> Utilisateur </option><option value="administrator" ';
																									  
																									  if(isset($_POST['statue'] )&& $_POST['statue']=="administrator") echo ' selected ';
																									  echo '>administrateur  </option></select></p>';
																								
																									echo'<p class="main">Fonction : <input type="text" name="fonction"';
																									
																									if(isset($_POST['fonction'])) echo 'value="'.$_POST['fonction'].'"';
																									echo'></p>';
																								
																									echo'<p class="main">Télephone  : <input type="text" name="add_user_Telephone"';
																									
																									if(isset($_POST['add_user_Telephone'])) echo 'value="'.$_POST['add_user_Telephone'].'"';
																									echo'></p>';
																									echo'<p class="main">Email  : <input type="text" name="add_user_email"';
																									
																									if(isset($_POST['add_user_email'])) echo 'value="'.$_POST['add_user_email'].'"';
																									echo'></p>';
																									echo'<p class="main">Numéro de matricule  : <input type="text" name="add_user_nm"';
																									
																									if(isset($_POST['add_user_nm'])) echo 'value="'.$_POST['add_user_nm'].'"';
																									echo'></p>';
																									
																									
																									echo'<p class="main">Image : <input type="FILE" name="user_image"';
																									
																									
																									echo'></p>';
																						
																						
																						
																						if(isset($_POST['add_user']) && $_FILES['user_image']['error']!=0 && $_FILES['user_image']['size']!=0)
																							{
					
																											echo'<span class="error" ><img src="images/waring2.png"> Erreur de Téléchargement .. essayer plus tard</span>' ;
																													$error=1;
					
					
																							}
																						else if(isset($_POST['add_user']) && $_FILES['user_image']['error']==0 && $_FILES['user_image']['size']!=0)
																						{
																									if(isset($_POST['add_user']) && $_FILES['user_image']['error']!=0 && $_FILES['user_image']['size']> 4000000)
																									{
							
																													echo'<span class="error" ><img src="images/waring2.png">le size d\'image doit être inférieure à 4 mo</span>' ;
																															$error=1;
							
							
																									}
																									else
																									{
																											$infosfichier = pathinfo($_FILES['user_image']['name']);
																											$extension_upload = $infosfichier['extension'];
																											$extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png','flv','bmp','JPG','JPEG','GIF','PNG','FLV','BMP');
																											if (in_array($extension_upload, $extensions_autorisees)==false)
																											{
																												echo'<span class="error" ><img src="images/waring2.png">incorrect extention </span>' ;
																															$error=1;
																									
																									
																									
																											}
																									}
																							}
																						
						
																				
																				
																						if(isset($_POST['add_user']) && $error==0)
																						{
																						
																								
																								if($_FILES['user_image']['size']==0)
																								$image="images/pic.png";
																								else
																								{
																										$max=$db->query('SELECT MAX(id_user) AS maxid FROM user');
																										$d=$max->fetch();
																										$d['maxid']++;
																										$d['maxid']++;
																										$infosfichier = pathinfo($_FILES['user_image']['name']);
																										$extension_upload = $infosfichier['extension'];
																										$image='user/'.$d['maxid'].'.'.$extension_upload;
																										move_uploaded_file($_FILES['user_image']['tmp_name'], $image);
																								
																								}
																								$R1=$db->prepare('INSERT INTO user VALUES(\'\',:first,:last,:telephone,:email,:login,:password,:statue,\'enable\',:nm,:picture,:fonction)');
																													
																								$R1->execute(array(
																												
																												'first'=>$_POST['add_user_first'],
																												'last'=>$_POST['add_user_last'],
																												'telephone'=>$_POST['add_user_Telephone'],
																												'email'=>$_POST['add_user_email'],
																												'login'=>$_POST['add_user_login'],
																												'password'=>$_POST['add_user_password'],
																												'statue'=>$_POST['statue'],
																												'nm'=>$_POST['add_user_nm'],
																												'picture'=>$image,
																												'fonction'=>$_POST['fonction']
																												
																												
																												
																												
																											)
																											);
																								
																								header('location:Administration.php');
																						
																						
																						
																						
																						
																						
																						}
																						}
																						
																						else	if(isset($_GET['go']) && $_GET['go']=="test")
																							{
																								echo'<div class="all_tests">';
																										$Q=$db->query('SELECT * FROM test GROUP BY parametre ');
																										
																										while($D=$Q->fetch())
																										{	
																											echo ' <p> '.$D['parametre'].' :</p>';
																											$Q2=$db->prepare('SELECT * FROM test WHERE parametre=:parametre ORDER BY name ');
																											$Q2->execute(array(
																														'parametre'=>$D['parametre']
																														));
																											echo'<ul>';
																											while($D2=$Q2->fetch())
																											{
																												
																												echo ' <li> <a href="show_controle.php?type=test&operation=show&id='.$D2['id_test'].'"> '.$D2['name'].' </a></li>';
																											
																											
																											}
																											echo'</ul>';
																										
																											
																										
																										}
																									echo'</div>';
																							
																							
																							}
																						else
																						{
																						$Q=$db->query('SELECT * FROM user ');
																								
																							while($G=$Q->fetch())
																							{
																								echo'<div class="user_card">';
																								echo'<a href="show_controle.php?type=user&operation=show&id='.$G['id_user'].'" title="'.$G['first'].' '.$G['last'].'"> <img src="'.$G['picture'].'" width=120 height=120 > </a>';
																								echo'<p>'.$G['first'].'<br>'.$G['last'].'</p>';
																								echo'<a href="show_controle.php?type=user&operation=show&id='.$G['id_user'].'" class="'.$G['statue'].'"  > here </a>';
																								
																								
																								echo'</div>';
																								
																							
																							
																							}
																						
																						
																						
																						
																							
																						
																						}
																						
																				
																				
																				?>
																				<br><br><br>
																				</div> <!-- right_administrastion ---->
																				
																				
				
				
				
				<p id="clear"></p>
				
				</div> <!---------------- ALL -------------------->
		</body>
		
</html>