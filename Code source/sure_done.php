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
			
			</body>
			<?php
			if(!isset($_SESSION['loged']) or $_SESSION['loged']!=1 )
			{
						echo ' <img src="images/remove_done_block.png" class="remove_block" >';
					
					}
					else if(isset($_GET['type'])==false OR isset($_GET['id'])==false )
						{
						
						echo ' <img src="images/remove_done_block.png" class="remove_block" >';
					
						}
			else
			{
					if($_GET['type']=="done" OR $_GET['type']=="news" )
					{
					
						
								try
								{
									$db=new PDO('mysql:host='.$_SERVER['HTTP_HOST'].';dbname=surveillance','root','');
								}
								catch(exception $e)
								{
									die('Error :'.$e->getmessage());
								}
								
								$R=$db->prepare('SELECT * FROM done WHERE id_done=:id');
								$R->execute(array(
											'id'=>$_GET['id']
											));
								$D=$R->fetch();
								if($D==false OR ($D['id_user']!=$_SESSION['id'] && $D_user['statue']=="user" )  )
								{
									echo ' <img src="images/remove_done_block.png" class="remove_block" >';
								}
								
								else
								{
									echo'<form action="sure_done.php?id='.$_GET['id'].'&type='.$_GET['type'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'"    method="post">			
									<input type="submit" name="sure" id="sure" title="remove">
									</form>';
									
									if(isset($_POST['sure']))
									{
											$R_test=$db->prepare('SELECT * FROM test WHERE id_test=:id');
											$R_test->execute(array(
											'id'=>$D['id_test']
											));
											$D_test=$R_test->fetch();
											
									
											$id_support=$D['id_support'];
											$nombre=$D['number'];
											$R=$db->prepare('DELETE  FROM done WHERE id_done= :done');
											$R->execute(array(
														'done'=>$D['id_done']
														)
														);
												if($_GET['type']=="done")
													echo'<script language="JavaScript" type="text/javascript">
													top.location ="show.php?year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&type='.$D_test['period'].'";S													
													</script>
													';
												
												else if($_GET['type']=="news")
													
													echo'<script language="JavaScript" type="text/javascript">
													top.location ="Nouvelles.php?limit=30";												
													</script>
													';
									
									
									
									}
								
								}
				
					
					
					
					}
					
					
			}
			
			
			?>
			
			
			
			
			
			
			
			</body>
		</html>