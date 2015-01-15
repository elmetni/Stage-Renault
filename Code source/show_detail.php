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
		
?>

<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
	<html>
			<head>
			<link href="frame.css" rel="stylesheet" type="text/css" media="screen" >
			</head>
			<?php
			
			if(!isset($_SESSION['loged']) or $_SESSION['loged']!=1 OR $D_user['statue']=="user" )
			{
				echo ' <img src="images/add_detail_block.png" class="remove_block" >';
			
			}
			
			else if (isset($_GET['id'])==false OR  isset($_GET['detail'])==false OR  isset($_GET['type'])==false)
			echo ' <img src="images/add_detail_block.png" class="remove_block" >';
			else
			{
					$R=$db->prepare('SELECT * FROM support WHERE id_test=:id_test AND detail=:detail ');
					$R->execute(array(
								'id_test'=>$_GET['id'],
								'detail'=>$_GET['detail']
								));
					
					$D=$R->fetch();
					
					if($D==false  )
						{
							echo ' <img src="images/add_detail_block.png" class="remove_block" >';
						}
					else
					{
							if($_GET['type']=="show")
							{
								echo '<div class="show_detail1" >';
								
								echo'<form method="post" action="show_detail.php?id='.$_GET['id'].'&type=show&detail='.$_GET['detail'].'">';
								
								
								
								echo'<input type="submit" name="add_detail" id="add_valeur" >';
								echo'</form>';
								echo ' <a href="show_detail.php?id='.$_GET['id'].'&type=edit&detail='.$_GET['detail'].'" id="edit_done_detail"> edit </a>';
								echo'<iframe id="delet_done" src="delet_detail.php?id='.$D['id_test'].'&detail='.$D['detail'].'" height="40" width="80" SCROLLING="no" NORESIZE FRAMEBORDER="0"></iframe>';
								echo'<p id="show_detail_detail"> Par '.$D['detail'].' :</p>';
								
								
								echo'<ul>';
								while($D)
								{
										
									echo'<li> <iframe id="delet_done_valeur" src="delet_controle.php?id='.$D['id_support'].'&type=valeur" height="15" width="50" SCROLLING="no" NORESIZE FRAMEBORDER="0"></iframe>';
									echo''.$D['valeur'].' </li>';
									$D=$R->fetch();
								}
								echo'</ul>';	
								echo'</div>';
								if(isset($_POST['add_detail']))
								{
										echo'<form method="post" action="show_detail.php?id='.$_GET['id'].'&type=show&detail='.$_GET['detail'].'">';
										
										echo'<input type="text" name="new_detail" id="new_detail" >';
										echo'<input type="submit" name="new_detail_botton" id="new_detail_botton" ></form>';
								
								}
								
								if(isset($_POST['new_detail_botton']) && $_POST['new_detail']!="" )
								{
										$R12=$db->prepare('INSERT INTO support  VALUES(\'\',:id_test,:detail,:valeur )');
																												
														$R12->execute(array(
																		'id_test'=>$_GET['id'],
																		'detail'=>$_GET['detail'],
																		'valeur'=>$_POST['new_detail']
																		));
												
												
										
										
													header('location:show_detail.php?id='.$_GET['id'].'&type=show&detail='.$_GET['detail']);
								
								}
							
							
							}
							
							if($_GET['type']=="edit")
							{
												
											$R_value=$db->prepare('SELECT *  FROM support WHERE id_test=:id_test AND detail=:detail');
											$R_value->execute(array(
														'id_test'=>$_GET['id'],
														'detail'=>$_GET['detail']
														)
														);
										$D_value=$R_value->fetch();
										echo'<form method="post" action="show_detail.php?id='.$_GET['id'].'&type=edit&detail='.$_GET['detail'].'">';
										echo '<input type="text" name="detail_edit" id="detail_edit"  ';
										if(isset($_POST['detail_edit'])) echo'value="'.$_POST['detail_edit'].'"';
										
										else echo'value="'.$D_value['detail'].'"';
										echo'>';
										$i=0;
										
										echo'<ul id="edit_value">';
										while($D_value)
										{
													echo'<li>';
												echo '<input type="text" name="value'.$i.'" id="value_input_edit"  ';
												if(isset($_POST['value'.$i.''])) echo'value="'.$_POST['value'.$i.''].'"';
												else echo'value="'.$D_value['valeur'].'"';
												echo'>';
												echo'</li>';
												$i++;
												$D_value=$R_value->fetch();
										}
										echo'</ul>';
										echo '<input type="hidden" name="detail_nombre" value="'.$i.'" ></p>';
										
										echo'<input type="submit" name="edit_detail2" id="add_detail_botton2" value="valider" >';
										echo'</form>';
										
										if(isset($_POST['edit_detail2']))
										{
												$R_value=$db->prepare('SELECT *  FROM support WHERE id_test=:id_test AND detail=:detail');
											$R_value->execute(array(
														'id_test'=>$_GET['id'],
														'detail'=>$_GET['detail']
														)
														);
													$D_value=$R_value->fetch();	
										
												$error=0;
												for($i=0;$i<$_POST['detail_nombre'];$i++)
												{
														if($_POST['value'.$i.'']=="") $error=1;
												
												}
												if($_POST['detail_edit']=="") $error=1;
										
												if($error==0)
												{
														
														if($_POST['detail_edit']!=$D_value['detail'])
														{
														
														
														
																$update1=$db->prepare('UPDATE support SET detail=:detailt WHERE id_test=:id AND detail=:detaill');
																$update1->execute(array(
																			'detailt'=>$_POST['detail_edit'],
																			'id'=>$D_value['id_test'],
																			'detaill'=>$D_value['detail'],
																			));
														
														
														}
												
														$i=0;
														while($D_value)
														{
																if($_POST['value'.$i.'']!=$D_value['valeur']) 
																{
																		$update1=$db->prepare('UPDATE support SET valeur=:valeur WHERE id_test=:id AND valeur=:valeur2 AND  detail=:detail');
																		$update1->execute(array(
																					'valeur'=>$_POST['value'.$i.''],
																					'id'=>$D_value['id_test'],
																					'valeur2'=>$D_value['valeur'],
																					'detail'=>$D_value['detail']
																					));
																
																
																
																}
																$D_value=$R_value->fetch();	
																$i++;
																
														}
												
														header('location:show_detail.php?type=show&id='.$_GET['id'].'&detail='.$_POST['detail_edit']);
												}
										
										
										}
							
							
							}


							
					
					
					
					
					}
					
			
			
			
			}
			?>
			</body>
			
	</html>