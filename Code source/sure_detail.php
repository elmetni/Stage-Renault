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
				echo ' <img src="images/remove_done_block.png" class="remove_block" >';
			
			}
			else if (!isset($_GET['detail']) OR !isset($_GET['id']) )
			echo ' <img src="images/remove_done_block.png" class="remove_block" >';
			else
			{	
					
							$R=$db->prepare('SELECT * FROM support WHERE id_test=:id_test  AND detail=:detail');
							$R->execute(array(
										'id_test'=>$_GET['id'],
										'detail'=>$_GET['detail']
										));
							
							$D=$R->fetch();
							
							if($D==false  )
								{
									echo ' <img src="images/remove_done_block.png" class="remove_block" >';
								}
							else
								{
									echo'<form action="sure_detail.php?detail='.$_GET['detail'].'&id='.$_GET['id'].'"    method="post">			
									<input type="submit" name="sure" id="sure_detail" title="remove">
									</form>';
									
									if(isset($_POST['sure']))
									{
											
											$R3=$db->prepare('SELECT *  FROM support  WHERE id_test= :id_test AND detail=:detail');
											$R3->execute(array(
														'id_test'=>$D['id_test'],
														'detail'=>$D['detail']
														)
														);
												
											while($D3=$R3->fetch())
											{
														
														
														
												$R=$db->prepare('DELETE  FROM done WHERE id_support= :id_support');
												$R->execute(array(
															'id_support'=>$D3['id_support']
															)
															);
												
												$R=$db->prepare('DELETE  FROM support WHERE id_support= :id_support');
												$R->execute(array(
															'id_support'=>$D3['id_support']
															)
															);												
												
												
											}	
													echo'<script language="JavaScript" type="text/javascript">
													top.location ="show_controle.php?type=test&operation=show&id='.$_GET['id'].'";				
													</script>
													';
												
												
									
									
									
									}
								
								}
					
					
					
			
			
			}
			?>
			</body>
			
	</html>