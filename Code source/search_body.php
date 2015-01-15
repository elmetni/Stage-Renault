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
			
		
			

?>
																			



	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head> 
		
		<link href="frame.css" rel="stylesheet" type="text/css" media="screen" >
		</head>
		
		<body>
<div id="all" >

<?php
if(isset($_GET['search_main_botton']))
{

											 
							$tab=explode('*',$_GET['test']);
							$inf=count_all_days($_GET['from_search_year'],$_GET['from_search_month'],$_GET['from_search_day']);
							$sub=count_all_days($_GET['to_search_year'],$_GET['to_search_month'],$_GET['to_search_day']);
							
						
							
											 echo'<table class="search_body"><tr class="th"><th></th><th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=parametre';
											if($_GET['nature']=="DESC") echo'&nature=ESC';
											else if($_GET['nature']=="ESC") echo'&nature=DESC';
											echo '"> Procédure ';
											if($_GET['order']=="parametre" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="parametre" && $_GET['nature']=="DESC") echo '<img src="images/desc.png" >';
											echo'</a> </th>';
											
											echo' <th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=name';
											if($_GET['nature']=="DESC") echo'&nature=ESC';
											else if($_GET['nature']=="ESC") echo'&nature=DESC';
											echo '"> Mesure ';
											if($_GET['order']=="name" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="name" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
											
											
											if($tab[0]==-1)
											{
																				
											 echo'<th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=id_support';
											if($_GET['nature']=="DESC") echo'&nature=ESC';
											else if($_GET['nature']=="ESC") echo'&nature=DESC';
											echo '"> Caracteristique ';
											if($_GET['order']=="id_support" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="id_support" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
											}
											else
											{
												$Q_test=$db->prepare('SELECT * FROM support WHERE id_test=:id ');
												$Q_test->execute(array(
														'id'=>$tab[0]
														));
												$G_test=$Q_test->fetch();
												if($G_test!=false)
												{
													 echo'<th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=id_support';
													if($_GET['nature']=="DESC") echo'&nature=ESC';
													else if($_GET['nature']=="ESC") echo'&nature=DESC';
													echo '"> Caracteristique ';
											if($_GET['order']=="id_support" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="id_support" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
												
												}
																							
											}
											
									
											 echo'<th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=id_user';
											if($_GET['nature']=="DESC") echo'&nature=ESC';
											else if($_GET['nature']=="ESC") echo'&nature=DESC';
											echo '"> Utilisateur ';
											if($_GET['order']=="id_user" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="id_user" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
											
											 echo'<th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=pji';
											if($_GET['nature']=="DESC") echo'&nature=ESC';
											else if($_GET['nature']=="ESC") echo'&nature=DESC';
											echo '"> pji ';
											if($_GET['order']=="pji" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="pji" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
											if($tab[0]==-1)
											{
											echo'<th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=answer';
												if($_GET['nature']=="DESC") echo'&nature=ESC';
												else if($_GET['nature']=="ESC") echo'&nature=DESC';
												echo '"> Attribut ';
											if($_GET['order']=="answer" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="answer" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
											 
											}
											else
											{
												$Q_test=$db->prepare('SELECT * FROM test WHERE id_test=:id ');
												$Q_test->execute(array(
														'id'=>$tab[0]
														));
												$G_test=$Q_test->fetch();
												if($G_test['attribut']==1)
												{
												echo'<th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=answer';
												if($_GET['nature']=="DESC") echo'&nature=ESC';
												else if($_GET['nature']=="ESC") echo'&nature=DESC';
												echo '"> Attribut ';
											if($_GET['order']=="answer" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="answer" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
												}
											
											}
											 echo'<th> <a href="search_body.php?test='.$_GET['test'].'&from_search_day='.$_GET['from_search_day'].'&from_search_month='.$_GET['from_search_month'].'&from_search_year='.$_GET['from_search_year'].'&to_search_day='.$_GET['to_search_day'].'&to_search_month='.$_GET['to_search_month'].'&to_search_year='.$_GET['to_search_year'].'&search_main_botton=Submit+Query&limit=30&order=time';
											if($_GET['nature']=="DESC") echo'&nature=ESC';
											else if($_GET['nature']=="ESC") echo'&nature=DESC';
											echo '"> Date ';
											if($_GET['order']=="time" && $_GET['nature']=="ESC") echo '<img src="images/esc.png">';
											else if($_GET['order']=="time" && $_GET['nature']=="DESC") echo '<img src="images/desc.png">';
											echo'</a> </th>';
											if($tab[0]==-1)
											{
											 echo'<th> rapport </th>';
											 
											}
											else
											{		$Q_test=$db->prepare('SELECT * FROM test WHERE id_test=:id ');
													$Q_test->execute(array(
														'id'=>$tab[0]
														));
													$G_test=$Q_test->fetch();
													if($G_test['mesurable']==1)
												{
													echo'<th> rapport </th>';
												}
											 
											 }
											 echo'</tr>';
											if($_GET['nature']=="ESC") $nature=" ";
											else if($_GET['nature']=="DESC") $nature="DESC";
												
									if($_GET['order']=="name" OR $_GET['order']=="parametre" )
											{
											
																				
												if($tab[0]==-1 && $tab[1]==-1)
												$Q=$db->query('SELECT * FROM test ORDER BY '.$_GET['order'].' '.$nature.'');
									
												else
												{
												$Q=$db->prepare('SELECT * FROM test WHERE id_test=:id ORDER BY '.$_GET['order'].' '.$nature.'');
												$Q->execute(array(
														'id'=>$tab[0]
														));
												}$i=1;
												while($G=$Q->fetch())
												{
														if($tab[1]==0 OR $tab[1]==-1)
														{
														$Q3=$db->prepare('SELECT * FROM done WHERE id_test=:id  AND all_days>=:inf AND all_days<=:sub  ');
														$Q3->execute(array(
														'id'=>$G['id_test'],
														'inf'=>$inf,
														'sub'=>$sub
														
														));	
														
					
													
														
														}
														else
														{
															$Q3=$db->prepare('SELECT * FROM done WHERE id_test=:id AND id_support=:id_support AND all_days>=:inf AND all_days<=:sub ');
															$Q3->execute(array(
															'id'=>$G['id_test'],
															'id_support'=>$tab[1],
															'inf'=>$inf,
															'sub'=>$sub
															
															));
														
					
														
														}
														
														while($G3=$Q3->fetch())
														{
																echo '<tr> <td>'.$i.' </td><td>'.$G['parametre'].'</td><td> <a href="done.php?id_done='.$G3['id_done'].'&year='.$G3['year'].'&month='.$G3['month'].'&day='.$G3['day'].'" target="_blank" >'.$G['name'].'</a></td>';
															
																$Q_test2=$db->prepare('SELECT * FROM support WHERE id_support=:id ');
																$Q_test2->execute(array(
																		'id'=>$G3['id_support']
																		));
																$G_test2=$Q_test2->fetch();
																
																if($G_test2!=false OR $tab[0]==-1)
																{
																		echo '<td> '.$G_test2['detail'].' ';
																		echo '  '.$G_test2['valeur'].'</td>';
																
																}
																$Q4=$db->prepare('SELECT * FROM user WHERE id_user=:id ');
																$Q4->execute(array(
																			'id'=>$G3['id_user'],
																			
																			));	
																$G4=$Q4->fetch();		
																echo '<td>'.$G4['first'].' '.$G4['last'].'</td>';
																echo ' <td>'.$G3['pji'].' </td>';
																echo ' <td>';
																		if($G3['answer']==-1)
																		{
																			echo ' ';
																		}
																		else echo $G3['answer'];
																echo '</td>';
																echo'<td> '.$G3['time'].' </td>';
																if($G3['rapport']!="-1" OR $tab[0]==-1)
																{
																	if($G3['rapport']==-1) echo' <td> </td>';
																	else echo ' <td> <a href="'.$G3['rapport'].'" target="_blank" >Télécharger</a></td>';
																	
																}
																$i++;
														}
													}
												
											}
											else if($_GET['order']=="time" OR $_GET['order']=="pji"  OR $_GET['order']=="answer" OR $_GET['order']=="id_user" OR $_GET['order']=="id_support"  )
											{
											
																				
												if($tab[0]==-1 && $tab[1]==-1)
												{
														$Q3=$db->prepare('SELECT * FROM done WHERE  all_days>=:inf AND all_days<=:sub ORDER BY '.$_GET['order'].' '.$nature.' ');
														$Q3->execute(array(
														
														'inf'=>$inf,
														'sub'=>$sub
														
														));	
												
												}
												
												else if($tab[1]==0)
														{
														$Q3=$db->prepare('SELECT * FROM done WHERE id_test=:id   AND all_days>=:inf AND all_days<=:sub ORDER BY '.$_GET['order'].' '.$nature.' ');
														$Q3->execute(array(
														'id'=>$tab[0],
														'inf'=>$inf,
														'sub'=>$sub
														
														));	
														
														
													
														}
												else
												{
													$Q3=$db->prepare('SELECT * FROM done WHERE id_test=:id AND id_support=:id_support AND all_days>=:inf AND all_days<=:sub  ORDER BY '.$_GET['order'].' '.$nature.' ');
													$Q3->execute(array(
													'id'=>$tab[0],
													'id_support'=>$tab[1],
													'inf'=>$inf,
													'sub'=>$sub
													
													));
												
												
													
												
												}
												
												$i=1;
												while($G3=$Q3->fetch())
												{
														$Q4=$db->prepare('SELECT * FROM user WHERE id_user=:id ');
														$Q4->execute(array(
																	'id'=>$G3['id_user'],
																	
																	));	
														$G4=$Q4->fetch();	

														$Q=$db->prepare('SELECT * FROM test WHERE id_test=:id ');
														$Q->execute(array(
																	'id'=>$G3['id_test'],
																	
																	));	
														$G=$Q->fetch();
														
														echo '<tr><td>'.$i.'</td> <td> '.$G['parametre'].'</td><td> <a href="done.php?id_done='.$G3['id_done'].'&year='.$G3['year'].'&month='.$G3['month'].'&day='.$G3['day'].'" target="_blank" >'.$G['name'].'</a></td>';
														
														
														$Q_test2=$db->prepare('SELECT * FROM support WHERE id_support=:id ');
														$Q_test2->execute(array(
																'id'=>$G3['id_support']
																));
														$G_test2=$Q_test2->fetch();
														
														if($tab[0]==-1)
														{
																if($G_test2!=false )
																echo '<td> '.$G_test2['detail'].': '.$G_test2['valeur'].'</td>';
																else 
																echo'<td></td>';
														
														}
														else if ($G_test2!=false )
														{
																echo '<td> '.$G_test2['detail'].':'.$G_test2['valeur'].'</td>';
														}
														
														echo '<td> <a href="show_controle.php?type=user&operation=show&id='.$G4['id_user'].' " target="_top">'.$G4['first'].' '.$G4['last'].'</a></td>';
																					
														echo ' <td>'.$G3['pji'].' </td>';
														if($G3['answer']!=-1 OR $tab[0]==-1)
																{
																	if($G3['answer']==-1) echo' <td> </td>';
																	else echo ' <td>'.$G3['answer'].'</td>';
																	
																}
														
														echo'<td> '.$G3['time'].' </td>';
														if($G3['rapport']!="-1" OR $tab[0]==-1)
																{
																	if($G3['rapport']==-1) echo' <td> </td>';
																	else echo ' <td> <a href="'.$G3['rapport'].'" target="_blank" >Télécharger</a></td>';
																	
																}
														echo '</tr>';
														$i++;
												}
											}
												
											
											
							echo' </table>';
							
}
?>
<p class="clear"></p>
</div>
</body>
</html>