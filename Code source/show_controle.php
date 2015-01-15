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
			
			
			else if(isset($_GET['type'])==false OR isset($_GET['operation'])==false OR isset($_GET['id'])==false OR (isset($_GET['type']) && $_GET['type']!="user" && $_GET['type']!="test" ) or (isset($_GET['operation']) && $_GET['operation']!="show" && $_GET['operation']!="edit" ))
			header('Administration.php');
			
			
			else if($D_user['statue']!="administrator" )
			{
					if($_SESSION['id']!=$_GET['id'] OR $_GET['type']!="user" )
					header('location:agenda.php?year='.date('Y').'&month='.date('n'));
			
			}
		
?>
																			



	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
		<title> Administraction  </title>
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
												
												
												<script language="JavaScript">
												<!--
												function autoResize(id){
													var newheight;
													var newwidth;

													if(document.getElementById){
														newheight=document.getElementById(id).contentWindow.document .body.scrollHeight+40;
														newwidth=document.getElementById(id).contentWindow.document .body.scrollWidth;
													}

													document.getElementById(id).height= (newheight) + "px";
													document.getElementById(id).width= (newwidth) + "px";
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
							<?php
														if($D_user['statue']!="user")
														{
																			echo'<div class="left_menu">';
																				
																				
																					
																					echo '<ul>
																						<li><a  href="Administration.php"';
																						
																						echo'			
																						>Les utilisateurs </a></li>
																						<li><a   href="Administration.php?go=test"';
																						
																						echo'
																						>  Les mesures '; 
																						echo'</a></li>
																						<li><a   href="Administration.php?go=add_test"';
																						
																						echo' > Ajouter mesure </a></li>
																						<li><a   href="Administration.php?go=add_user"';
																						
																						echo'
																						>Ajouter utilisateur </a></li>
																						
																						
																					</ul>
																					<p class="clear"> </p>';
																					if(isset($_GET['go'])&&isset($_GET['search']) && $_GET['go']!="message" && $_GET['go']!="send" && $_GET['go']!="finder" && $_GET['go']!="confirme") header('location:friends.php');
																						
																					echo'</div>';
																					
																		}
																				
																				?>
																				
																				<?php
																				if($D_user['statue']!="user") echo'<div class="right_administrastion">';
																				else echo'<div class="left_administrastion">';
																				
																						if($_GET['type']=="user")
																						{
																								if($_GET['operation']=="show")
																								 {
																									echo'<div class="show_controle_user">';
																								 
																									$Q=$db->prepare('SELECT * FROM user WHERE id_user=:id');
																									$Q->execute(array(
																												'id'=>$_GET['id']
																									));
																									$O=$Q->fetch();
																									
																									echo'<div class="user_name_img"><img src="'.$O['picture'].'" height=200 width=200 >';
																									echo '<p id="name_user"> '.$O['first'].' '.$O['last'].' </p></div>';
																									echo'<div class="info">';
																									if($O['statue']=="user") echo'<img src="images/user.png" width=120 height=40 >';
																									else if($O['statue']=="administrator") echo'<img src="images/administrator_hard.png" width=120 height=40 >';
																									
																									
																												
																													if($O['type']=="enable")
																													{
																															echo'<form id="enable_disable"  action="show_controle.php?type='.$_GET['type'].'&operation='.$_GET['operation'].'&id='.$_GET['id'].'" method="POST">';
																															echo'<input type="submit" name="desactiver" id="desactiver" value="desactiver" ></form>';
																													
																													}
																													
																													else if($O['type']=="disable")
																													{
																															echo'<form id="enable_disable" action="show_controle.php?type='.$_GET['type'].'&operation='.$_GET['operation'].'&id='.$_GET['id'].'" method="POST">';
																															echo'<input type="submit" name="activer" id="activer" value="activer" ></form>';
																													
																													}
																													
																													if(isset($_POST['desactiver']))
																													{
																															$F=$db->prepare('UPDATE user SET type=\'disable\' WHERE id_user=:id');
																															$F->execute(array(
																																	'id'=>$_GET['id']
																																	));
																															header('location:show_controle.php?type='.$_GET['type'].'&operation='.$_GET['operation'].'&id='.$_GET['id']);
																													
																													
																													}
																													
																													else if(isset($_POST['activer']))
																													{
																															$F=$db->prepare('UPDATE user SET type=\'enable\' WHERE id_user=:id');
																															$F->execute(array(
																																	'id'=>$_GET['id']
																																	));
																																	header('location:show_controle.php?type='.$_GET['type'].'&operation='.$_GET['operation'].'&id='.$_GET['id']);
																													}
																									
																								
																										echo ' <a id="edit_done" href="show_controle.php?type=user&operation=edit&id='.$_GET['id'].'" > edit done </a> ';
																									
																										echo'<iframe id="delet_done" src="delet_controle.php?id='.$_GET['id'].'&type=user" height="40" width="80" SCROLLING="no" NORESIZE FRAMEBORDER="0"></iframe>';
																										
																										
																									
																									
																									
																									
																									echo '<p><span> Identifiant : </span> '.$O['login'].' </p>';
																									
																									if(isset($_POST['show_password'])==false)
																									{
																												
																												
																												echo'<form  id="show_password"  action="show_controle.php?type='.$_GET['type'].'&operation='.$_GET['operation'].'&id='.$_GET['id'].'" method="POST">';
																												echo'<input type="submit" name="show_password" id="show_password" ></form>';
																												echo'<p id="show_password"  ><span> Mot de passe : </span>&nbsp;&nbsp;'.$O['password'][0].' ';
																												for($i=1;$i<strlen($O['password']);$i++)
																												{
																													echo'*';
																												}
																												echo'</p>';
																												
																									}
																									else
																										echo '<p id="show_password"  ><span> Mot de passe : </span>&nbsp;&nbsp;'.$O['password'].'</p>' ;
																									
																									
																									echo '<p class="clear_left">';
																									echo'<span> Fonction : </span> '.$O['fonction'].' </p>';
																									echo'<p><span> Téléphone : </span> '.$O['telephone'].' </p>';
																									echo '<p><span> Email : </span> '.$O['email'].' </p>';

																									echo '<p><span> Numéro de matricule : </span> '.$O['nm'].' </p>';
																									echo '<p><span> Statue de compte: </span> ';
																									if($O['type']=="disable") echo' <span class="disable">Désactivé </span></p>';
																									if($O['type']=="enable") echo'<span class="enable"> Activé </span></p>';
																									
																									
																									
																								 
																								 
																									echo'</div>';
																									echo'</div>';
																								 }
																								 
																								else if($_GET['operation']=="edit")
																																 {
																																 
																																				$R_edit=$db->prepare('SELECT * FROM user WHERE id_user=:id');
																																				$R_edit->execute(array(
																																						'id'=>$_GET['id']
																																						));
																																				$D_edit=$R_edit->fetch();
																																				if($D_edit==false) header('location:Administration.php');
																																				$error=0;
																																				echo'<div class="edit_user">';
																																				echo'<form method="POST"  action="show_controle.php?type='.$_GET['type'].'&operation=edit&id='.$_GET['id'].'"  enctype="multipart/form-data">';
																																				
																																					echo'<div class="add_test_title"><p>Modifier l\'utilisateur : '.$D_edit['first'].' '.$D_edit['last'].'</p></div>';
																																					
																																					
																																					
																																						
																																	echo'<p class="main">* Nom  : <input type="text" name="add_user_first" ';
																																	
																																	if(isset($_POST['add_user_first'])) echo 'value="'.$_POST['add_user_first'].'"';
																																	else echo 'value="'.$D_edit['first'].'"';
																																	echo'>';
																																	
																																	
																																	 echo' </p>';
																																	if(isset($_POST['add_user']) && $_POST['add_user_first']=="")
																																			{
																																					echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																																					$error=1;
																																					
																																			
																																			}
																																	 echo'<p class="main">* Prénom  : <input type="text" name="add_user_last"';
																																	
																																	if(isset($_POST['add_user_last'])) echo 'value="'.$_POST['add_user_last'].'"';
																																	else echo 'value="'.$D_edit['last'].'"';
																																	echo'>';
																																	
																																	
																																	 echo' </p>';
																																	 if(isset($_POST['add_user']) && $_POST['add_user_last']=="")
																																			{
																																					echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																																					$error=1;
																																					
																																			
																																			}
																																	  echo'<p class="main">* Identifiant (pour se connecter): <input type="text" name="add_user_login"';
																																	
																																	if(isset($_POST['add_user_login'])) echo 'value="'.$_POST['add_user_login'].'"';
																																	else echo 'value="'.$D_edit['login'].'"';
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
																																					if($t!=false && $t['login']!=$D_edit['login'])
																																					{
																																					
																																					echo'<span class="error" ><img src="images/waring2.png"> Identifiant déja exist </span>' ;
																																					$error=1;
																																					
																																					}
																																					
																																					
																																					
																																			
																																			}
																																	  echo'<input type="submit" name="add_user" class="edit_user" value="">';
																																	 
																																	  echo'<p class="main">* Mot de passe &nbsp; : &nbsp;&nbsp;<input type="password" name="add_user_password"';
																																	
																																	if(isset($_POST['add_user_password'])) echo 'value="'.$_POST['add_user_password'].'"';
																																	else echo 'value="'.$D_edit['password'].'"';
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
																																	else echo 'value="'.$D_edit['password'].'"';
																																	echo'></P>';
																																	 if(isset($_POST['add_user']) && $_POST['add_user_re_password']!=$_POST['add_user_password'])
																																			{
																																					echo'<span class="error" ><img src="images/waring2.png">s\'il vous plaît confirmer le mot de passe</span>' ;
																																					$error=1;
																																					
																																			
																																			}
																																	
																																	  echo'<p class="main">* Type de compte  : ' ;
																																	  echo ' <select name="statue"><option value="user" ';
																																	  
																																	  if(isset($_POST['statue'])&& $_POST['statue']=="user") echo ' selected ';
																																	  else if(!isset($_POST['statue'])&& $D_edit['statue']=="user")  echo ' selected ';
																																	  echo'> Utilisateur </option><option value="administrator" ';
																																	  
																																	  if(isset($_POST['statue'] )&& $_POST['statue']=="administrator") echo ' selected ';
																																	  else if(!isset($_POST['statue'])&& $D_edit['statue']=="administrator")  echo ' selected ';
																																	  echo '>administrateur  </option></select></p>';
																																
																																	echo'<p class="main">Fonction  : <input type="text" name="fonction"';
																																	
																																	if(isset($_POST['fonction'])) echo 'value="'.$_POST['fonction'].'"';
																																	else echo 'value="'.$D_edit['fonction'].'"';
																																	echo'></p>';
																																	
																																
																																	echo'<p class="main">Télephone  : <input type="text" name="add_user_Telephone"';
																																	
																																	if(isset($_POST['add_user_Telephone'])) echo 'value="'.$_POST['add_user_Telephone'].'"';
																																	else echo 'value="'.$D_edit['telephone'].'"';
																																	echo'></p>';
																																	echo'<p class="main">Email  : <input type="text" name="add_user_email"';
																																	
																																	if(isset($_POST['add_user_email'])) echo 'value="'.$_POST['add_user_email'].'"';
																																	else echo 'value="'.$D_edit['email'].'"';
																																	echo'></p>';
																																	echo'<p class="main">Numéro de matricule  : <input type="text" name="add_user_nm"';
																																	
																																	if(isset($_POST['add_user_nm'])) echo 'value="'.$_POST['add_user_nm'].'"';
																																	else echo 'value="'.$D_edit['nm'].'"';
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
																																$image=$D_edit['picture'];
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
																																if($D_edit['first']!=$_POST['add_user_first'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET first=:first WHERE id_user=:id');
																																		$update1->execute(array(
																																					'first'=>$_POST['add_user_first'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																if($D_edit['last']!=$_POST['add_user_last'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET last=:last WHERE id_user=:id');
																																		$update1->execute(array(
																																					'last'=>$_POST['add_user_last'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																if($D_edit['fonction']!=$_POST['fonction'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET fonction=:fonction WHERE id_user=:id');
																																		$update1->execute(array(
																																					'fonction'=>$_POST['fonction'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																
																																if($D_edit['telephone']!=$_POST['add_user_Telephone'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET telephone=:telephone WHERE id_user=:id');
																																		$update1->execute(array(
																																					'telephone'=>$_POST['add_user_Telephone'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																
																																
																																
																																
																																if($D_edit['email']!=$_POST['add_user_email'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET email=:email WHERE id_user=:id');
																																		$update1->execute(array(
																																					'email'=>$_POST['add_user_email'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																
																																if($D_edit['login']!=$_POST['add_user_login'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET login=:login WHERE id_user=:id');
																																		$update1->execute(array(
																																					'login'=>$_POST['add_user_login'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																if($D_edit['password']!=$_POST['add_user_password'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET password=:password WHERE id_user=:id');
																																		$update1->execute(array(
																																					'password'=>$_POST['add_user_password'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																
																																if($D_edit['statue']!=$_POST['statue'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET statue=:statue WHERE id_user=:id');
																																		$update1->execute(array(
																																					'statue'=>$_POST['statue'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																if($D_edit['nm']!=$_POST['add_user_nm'])
																																
																																{
																																		$update1=$db->prepare('UPDATE user SET nm=:nm WHERE id_user=:id');
																																		$update1->execute(array(
																																					'nm'=>$_POST['add_user_nm'],
																																					'id'=>$D_edit['id_user']
																																					));
																																}
																																
																																
																																$update1=$db->prepare('UPDATE user SET picture=:picture WHERE id_user=:id');
																																		$update1->execute(array(
																																					'picture'=>$image,
																																					'id'=>$D_edit['id_user']
																																					));
																																
																																
																																					
																																					header('location:show_controle.php?type='.$_GET['type'].'&operation=show&id='.$_GET['id']);
																																			
																																			
																																			
																														
																														
																																		
																																		
																																					}
																																				 
																																				 
																																				 
																																				 
																																				}
																										
																										
																										
																										
																										}
																										
																				
																				
																				if($_GET['type']=="test")
																						{
																								if($_GET['operation']=="show")
																								 {
																									$Y=$db->prepare('SELECT * FROM test WHERE id_test=:id');
																									$Y->execute(array(
																											'id'=>$_GET['id']
																											));
																									$D_test=$Y->fetch();
																									$tab=explode('*',$D_test['period']);
																									echo'<div class="show_controle_test">';
																								echo'<div class="add_test_title"><p  > Description  de la mesure :'.$D_test['name'].' </p></div>';
																								 
																								 echo ' <a id="edit_done" href="show_controle.php?type=test&operation=edit&id='.$_GET['id'].'" > edit done </a> ';
																									
																										echo'<iframe id="delet_done" src="delet_controle.php?id='.$_GET['id'].'&type=test" height="40" width="80" SCROLLING="no" NORESIZE FRAMEBORDER="0"></iframe>';
																										
																								 echo'<p><span> Le nom de Mesure </span> : '.$D_test['name'].'</p>';
																								 
																								  echo'<p><span> La procédure </span> : '.$D_test['parametre'].'</p>';
																								if($tab[1]==1)
																								echo'<p> Cette mesure est effectué une fois par  <strong>'.$tab[0].'</strong></p>';
																								else 
																								{
																								if($tab[0]=="mois")
																								echo'<p> Cette mesure  est effectué une fois chaque <strong>'.$tab[1].' '.$tab[0].'</strong></p>';
																								else 
																								echo'<p> Cette mesure est effectué une fois chaque <strong>'.$tab[1].' '.$tab[0].'s</strong></p>';
																								}
																								
																								
																								
																								$R5=$db->prepare('SELECT * FROM user WHERE  id_user=:id_user');
																											$R5->execute(array(
																											'id_user'=>$D_test['id_user']
																											));
																											$D5=$R5->fetch();
																									
																								echo'<p> <span>Cette mesure doit effectuer par  : </span><a id="show_user_page" href="show_controle.php?type=user&operation=show&id='.$D5['id_user'].' " target="_top">'.$D5['first'].' '.$D5['last'].'</a></p>';
																								echo'<p> <span> Cette test a été ajouter   à :  </span>'.$D_test['add_time'].'</p>';
																								echo'<p> <span> l\'attribut :  </span>';
																								if($D_test['attribut']==1) echo "Oui";
																								else echo "Non";
																								echo '</p>';
																								echo'<p> <span> Mesurable :  </span>';
																								if($D_test['mesurable']==1) echo "Oui";
																								else echo "Non";
																								echo '</p>';
																								echo'<p> <span> Taille d\'échantillon :  </span>'.$D_test['re_test'].'</p>';
																								
																								echo'<p> <span> Des informations sure cette mesure : </span><br>'.$D_test['info'].'</p>';
																								
																								 echo'<div class="add_test_title"><p  > Les divisions   de la mesure :'.$D_test['name'].' </p></div>';
																									$R100=$db->prepare('SELECT * FROM support WHERE id_test=:id GROUP BY detail ');
																									$R100->execute(array(
																											'id'=>$D_test['id_test']
																											));
																									$i=0;
																									while($D100=$R100->fetch())
																									{
																										ECHO'<iframe src="show_detail.php?id='.$D_test['id_test'].'&type=show&detail='.$D100['detail'].'" height="300" marginheight="0" name="show_detail'.$i.'" id="show_detail'.$i.'"  class="show_detail" width="500" SCROLLING="no" NORESIZE FRAMEBORDER="0" onLoad="Resize(\'show_detail'.$i.'\');"></iframe>';
																										$i++;
																									}
																									ECHO'<iframe src="add_detail.php?id='.$D_test['id_test'].'" height="300" marginheight="0" name="add_detail" id="add_detail" width="650" SCROLLING="no" NORESIZE FRAMEBORDER="0" onLoad="Resize(\'add_detail\');"></iframe>';
																								 echo'</div>';
																								 }
																								 
																						
																						
																						
																						
																						
																						else if($_GET['operation']=="edit")
																						{
																										$R_edit=$db->prepare('SELECT * FROM test WHERE id_test=:id');
																										$R_edit->execute(array(
																												'id'=>$_GET['id']
																												));
																										$D_test=$R_edit->fetch();
																										if($D_test==false) header('location:Administration.php?go=test');
																										$tab1=explode('*',$D_test['period']);
																										$error=0;
																										echo'<div class="edit_user">';
																										echo'<form method="POST"  action="show_controle.php?type='.$_GET['type'].'&operation=edit&id='.$_GET['id'].'"  enctype="multipart/form-data">';
																										
																											echo'<div class="add_test_title"><p>Modifier la mesure : '.$D_test['name'].'</p></div>';
															
																									
																									
																										
																									echo'<p class="main">La mesure  : <input type="text" name="add_test_name"';
																									
																									if(isset($_POST['add_test_name'])) echo 'value="'.$_POST['add_test_name'].'"';
																									else echo 'value="'.$D_test['name'].'"';
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
																									else echo 'value="'.$tab1[1].'"';
																									echo'>';
																									 echo' * ';
																									 echo ' <select name="add_test_period"><option value="jour"';
																									 if(isset($_POST['add_test_period']) && $_POST['add_test_period']=="jour") echo ' selected';
																									 else if(!isset($_POST['add_test_period']) && $tab1[0]=="jour") 	echo' selected';
																									 echo' > Jour </option><option value="semaine"  ';
																									 if(isset($_POST['add_test_period']) && $_POST['add_test_period']=="semaine") echo ' selected';
																									else if(!isset($_POST['add_test_period']) && $tab1[0]=="semaine") echo' selected';
																									echo'> Semaine </option><option value="mois" ';
																									 if(isset($_POST['add_test_period']) && $_POST['add_test_period']=="mois") echo ' selected';
																									 else if(!isset($_POST['add_test_period']) && $tab1[0]=="mois") echo' selected';
																									 echo'> Mois </option></select>';
																									if(isset($_POST['add_test']) && $_POST['add_test_period_nombre']<1)
																												{
																														echo'<span class="error" ><img src="images/waring2.png">Invalide valeur</span>' ;
																														$error=1;
																														
																												
																												}
																												echo'</p>';
																									 echo'<input type="submit" name="add_test" class="edit_user" value="">';
																									 
																									 echo'<p class="main">Attribut : ' ;
																									  echo ' <select name="Atribut"><option value="-1" ';
																									 if(isset($_POST['Atribut']) && $_POST['Atribut']=="-1") echo ' selected';
																									 else if(!isset($_POST['Atribut']) && $D_test['attribut']=="-1") echo' selected'; 
																									 echo' > Non </option><option value="1"  ';
																									 if(isset($_POST['Atribut']) && $_POST['Atribut']=="1") echo ' selected';
																									  else if(!isset($_POST['Atribut']) && $D_test['attribut']=="1") echo' selected'; 
																									 echo'>Oui  </option></select></p>';
																									  echo'<p class="main">Mesurable : ' ;
																									  echo ' <select name="mesurable"><option value="1" ';
																									 if(isset($_POST['mesurable']) && $_POST['mesurable']=="1") echo ' selected';
																									 else if(!isset($_POST['mesurable']) && $D_test['mesurable']=="1") echo' selected'; 
																									 
																									 echo' > Oui </option><option value="-1"  ';
																									 if(isset($_POST['mesurable']) && $_POST['mesurable']=="-1") echo ' selected';
																									 else if(!isset($_POST['mesurable']) && $D_test['mesurable']=="-1") echo' selected'; 
																									 echo'>Non  </option></select></p>';
																									 echo'<p class="main"> Nombre de fois :</p><p>qu\'il faut l\'effectuer pendant son fréquence   : ' ;
																									 echo'<input type="text" name="add_test_re_test"  class="nombre" ';
																									if(isset($_POST['add_test_re_test'])) echo 'value="'.$_POST['add_test_re_test'].'"';
																									else echo 'value="'.$D_test['re_test'].'"';
																									echo'></p>';
																									 
																									echo'<p class="main">La procédure :  ';
																									
																									if(isset($_POST['add_test']) && $_POST['add_test_parametre']==-1 && $_POST['add_test_creat_parametre']=="")
																											{
																													echo'<span class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</span>' ;
																													$error=1;
																													
																											
																											}
																									echo'		<p>Vous pouvez choisir une procédure déja exisiter : ';
																									echo'<select name="add_test_parametre">';
																									
																									
																									$R_add=$db->query('SELECT * FROM test GROUP BY parametre ');	
																									while($D_add=$R_add->fetch())
																									{
																									
																									echo'<option value="'.$D_add['parametre'].'" ';
																									 if(isset($_POST['add_test_parametre']) && $_POST['add_test_parametre']==$D_add['parametre']) echo ' selected';
																									  else if(!isset($_POST['add_test_parametre']) && $D_test['parametre']==$D_add['parametre']) echo' selected'; 
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
																										
																										
																										$R_add=$db->query('SELECT * FROM user ');	
																										while($D_add=$R_add->fetch())
																										{
																										
																										echo'<option value="'.$D_add['id_user'].'" ';
																									 if(isset($_POST['add_test_user']) && $_POST['add_test_user']==$D_add['id_user']) echo ' selected';
																									   else if(!isset($_POST['add_test_user']) && $D_test['id_user']==$D_add['id_user']) echo' selected'; 
																									 echo'> '.$D_add['first'].' '.$D_add['last'].' </option>';
																										
																										}
																										echo'</select></p> ';
																										
																											echo'<p class="main" > Information sure la mesure  :</p>';
																											echo'<textarea  name="add_test_info" COLS="80" ROWS="5"  WRAP="virtual">';
																									
																									if(isset($_POST['add_test_info'])) echo  $_POST['add_test_info'];
																									else echo $D_test['info'];
																									echo'</textarea>';
																								echo'</div>';
																						
																						
																						
																						
																						
																						
																						
																				
																									echo'<p id="clear"></p>';
																											if(isset($_POST['add_test']) && $error==0)
																											{
																						
																								
																														if($_POST['add_test_parametre']!=-1 && $_POST['add_test_creat_parametre']=="")
																																		$parametre=$_POST['add_test_parametre'];
																																	 else 
																																	$parametre=$_POST['add_test_creat_parametre'];
																																	
																																$period=$_POST['add_test_period'].'*'.$_POST['add_test_period_nombre'];
																																
																																if($D_test['re_test']!=$_POST['add_test_re_test'])
																																
																																{
																																		$update2=$db->prepare('UPDATE test SET re_test=:re_test WHERE id_test=:id');
																																		$update2->execute(array(
																																					're_test'=>$_POST['add_test_re_test'],
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																
																																if($D_test['name']!=$_POST['add_test_name'])
																																
																																{
																																		$update1=$db->prepare('UPDATE test SET name=:name WHERE id_test=:id');
																																		$update1->execute(array(
																																					'name'=>$_POST['add_test_name'],
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																
																																if($D_test['period']!=$period)
																																
																																{
																																		$update1=$db->prepare('UPDATE test SET period=:period WHERE id_test=:id');
																																		$update1->execute(array(
																																					'period'=>$period,
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																
																																
																																if($D_test['attribut']!=$_POST['Atribut'])
																																
																																{
																																		$update1=$db->prepare('UPDATE test SET attribut=:attribut WHERE id_test=:id');
																																		$update1->execute(array(
																																					'attribut'=>$_POST['Atribut'],
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																
																																
																																if($D_test['mesurable']!=$_POST['mesurable'])
																																
																																{
																																		$update1=$db->prepare('UPDATE test SET mesurable=:mesurable WHERE id_test=:id');
																																		$update1->execute(array(
																																					'mesurable'=>$_POST['mesurable'],
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																
																																
																																
																																
																																
																																
																																
																																if($D_test['parametre']!=$parametre)
																																
																																{
																																		$update1=$db->prepare('UPDATE test SET parametre=:parametre WHERE id_test=:id');
																																		$update1->execute(array(
																																					'parametre'=>$parametre,
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																
																																			
																														
																																if($D_test['id_user']!=$_POST['add_test_user'])
																																
																																{
																																		$update1=$db->prepare('UPDATE test SET id_user=:id_user WHERE id_test=:id');
																																		$update1->execute(array(
																																					'id_user'=>$_POST['add_test_user'],
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																		
																																		
																																if($D_test['info']!=$_POST['add_test_info'])
																																
																																{
																																		$update1=$db->prepare('UPDATE test SET info=:info WHERE id_test=:id');
																																		$update1->execute(array(
																																					'info'=>$_POST['add_test_info'],
																																					'id'=>$D_test['id_test']
																																					));
																																}
																																$update1=$db->prepare('UPDATE test SET add_time=NOW() WHERE id_test=:id');
																																$update1->execute(array(
																																					'id'=>$D_test['id_test']
																																					));
																																header('location:show_controle.php?type=test&operation=show&id='.$D_test['id_test']);
																																
																																		
																																					}
																						}
																				}
																				
																				
																				?>
																				<br><br><br>
																				</div> <!-- right_administrastion ---->
																				
																				
				
				
				
				<p id="clear"></p>
				
				</div> <!---------------- ALL -------------------->
		</body>
		
</html>