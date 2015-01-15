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
			
			else if (isset($_GET['id'])==false )
			echo ' <img src="images/add_detail_block.png" class="remove_block" >';
			else
			{
					$R=$db->prepare('SELECT * FROM test WHERE id_test=:id_test ');
					$R->execute(array(
								'id_test'=>$_GET['id']
								));
					
					$D=$R->fetch();
					
					if($D==false  )
						{
							echo ' <img src="images/add_detail_block.png" class="remove_block" >';
						}
					else
					{	
					
					echo'<div class="add_detail_frame">';
					if(!isset($_POST['add_detail_botton1']) &&  !isset($_POST['add_detail_botton2']) && !isset($_POST['add_detail_botton3'])  )
									{
							echo'<form method="post" action="add_detail.php?id='.$_GET['id'].'">';
							echo'<input type="submit" name="add_detail_botton1" id="add_detail_botton1" value="Ajouter" >';
							echo'</form>';
									}
							 if(isset($_POST['add_detail_botton1']) OR (isset($_POST['add_detail_botton2']) && ( $_POST['detail_name']=="" OR $_POST['value_number']=="" OR  $_POST['value_number']<=0 OR is_numeric($_POST['value_number'])==false ) ))
									{
											echo'<form method="post" action="add_detail.php?id='.$_GET['id'].'">';
											echo'<p>Division : ';
											echo'<input type="text" name="detail_name" id="detail_name" ';
												if(isset($_POST['detail_name'])) echo'value="'.$_POST['detail_name'].'"';
												echo'>';
										
											echo' Nombre : <input type="text" name="value_number" id="value_number" ';
												if(isset($_POST['value_number'])) echo'value="'.$_POST['value_number'].'"';
												echo'></p>';
										
											echo'<input type="submit" name="add_detail_botton2" id="add_detail_botton2" value="valider" >';
											echo'</form>';
									
									
									}
							if(isset($_POST['add_detail_botton2']) && $_POST['detail_name']!=""&& $_POST['value_number']!="" && $_POST['value_number']>0 && is_numeric($_POST['value_number'])!=false )
									{
										echo'<form method="post" action="add_detail.php?id='.$_GET['id'].'">';
										$number=$_POST['value_number'];
										$detail_name=$_POST['detail_name'];
										echo'<p class="detail_name"> '.$detail_name .':</p>';
										for($i=0;$i<$number;$i++)
										{
													$g=$i+1;
												echo '<p class="sous_div">Sous division '.$g.' <input type="text" name="value'.$i.'" id="value_input"  ';
												if(isset($_POST['value'.$i.''])) echo'value="'.$_POST['value'.$i.''].'"';
												echo'></p>';
										
										}
										
										echo '<input type="hidden" name="value_number" value="'.$_POST['value_number'].'" >';
										echo '<input type="hidden" name="detail_name" value="'.$_POST['detail_name'].'" ></p>';
										echo'<input type="submit" name="add_detail_botton3" id="add_detail_botton2" value="valider" >';
										echo'</form>';
									}
							if(isset($_POST['add_detail_botton3']))
							{
							
										$error=0;
										for($i=0;$i<$_POST['value_number'];$i++)
										{
												if($_POST['value'.$i.'']=="") $error=1;
										
										}
										
										if($error==0)
										{
												for($i=0;$i<$_POST['value_number'];$i++)
												{
														$R12=$db->prepare('INSERT INTO support  VALUES(\'\',:id_test,:detail,:valeur )');
																												
														$R12->execute(array(
																		'id_test'=>$_GET['id'],
																		'detail'=>$_POST['detail_name'],
																		'valeur'=>$_POST['value'.$i.'']
																		));
												
												}
										
										
													echo'<script language="JavaScript" type="text/javascript">
													top.location ="show_controle.php?type=test&operation=show&id='.$_GET['id'].'";				
													</script>
													';
										}
										
									 else
									 {
										echo'<form method="post" action="add_detail.php?id='.$_GET['id'].'">';
										$number=$_POST['value_number'];
										$detail_name=$_POST['detail_name'];
										echo'<p class="detail_name"> '.$detail_name .':</p>';
										for($i=0;$i<$number;$i++)
										{
												echo '<input type="text" name="value'.$i.'" id="value_input"  ';
												if(isset($_POST['value'.$i.''])) echo'value="'.$_POST['value'.$i.''].'"';
												echo'>';
										
										}
										
										echo '<input type="hidden" name="value_number" value="'.$_POST['value_number'].'" >';
										echo '<input type="hidden" name="detail_name" value="'.$_POST['detail_name'].'" ></p>';
										echo'<input type="submit" name="add_detail_botton3" id="add_detail_botton2" value="valider" >';
										echo'</form>';
									}
							
							
							}
							echo'<p class="clear"></p></div>';
					}
					
			
			
			
			}
			?>
			</body>
			
	</html>
	