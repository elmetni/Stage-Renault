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
																			
			require_once("fonctions.php");	

															
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//en"

       "http://www.w3.org/TR/html4/strict.dtd">




<html>
		<head>
		<link href="frame.css" rel="stylesheet" type="text/css" media="screen" >
		</head>
		
		<body>
				
						<div class="all_done">
				<?php
							$R=$db->prepare('SELECT * FROM done WHERE id_done=:id_done ');
							$R->execute(array(											
											'id_done'=>$_GET['id_done'],
												
											));
							
									$D=$R->fetch();
									$R2=$db->prepare('SELECT * FROM test WHERE id_test=:id_test');
									$R2->execute(array(
													'id_test'=>$D['id_test']
													));
									$D2=$R2->fetch();
									if($D==false) 
									
									header('location:not_done.php?id_test='.$D['id_test'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&id_support='.$D['id_support'].'&nombre='.$D['number']);
						
									echo'<div class="done_title"> ';
									
									
									
										echo '<strong>'.$D2['parametre'].':</strong> '.$D2['name'];
										
										
									if($D2['re_test']!=1) echo' ('.$D['number'].')';
									if($D['id_support']!=-1)
									{
													$R3=$db->prepare('SELECT * FROM support WHERE  id_support=:id_support');
													$R3->execute(array(
													'id_support'=>$D['id_support']
													));
													$D3=$R3->fetch();
													echo ' <br>par '.$D3['detail'].': '.$D3['valeur']; 
									}
									echo'<p class="clear"></p></div>';
									
									echo'<div class="done_content">';
									if($D['id_user']!=$_SESSION['id'] && $D_user['statue']=="user")
									echo ' <img src="images/edit_block.png" class="edit_block" >';
									
								
									else
									echo ' <a id="edit_done" href="edit_done.php?id='.$_GET['id_done'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'" > edit done </a> ';
									
									echo'<iframe id="delet_done" src="delet_done.php?id='.$_GET['id_done'].'&type=done&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'" height="40" width="80" SCROLLING="no" NORESIZE FRAMEBORDER="0"></iframe>';
										echo'<p> <span> Semain '.quel_semain($D['year'],$D['month'],$D['day']).' : '.$D['day'].' '.mois($D['month']).' '.$D['year'].'</span></p>';
										
										
										$tab=explode('*',$D2['period']);
										if($tab[1]==1)
										echo'<p> Cette mesure est effectué une fois par  <strong>'.$tab[0].'</strong></p>';
										else 
										{
										if($tab[0]=="mois")
										echo'<p> Cette mesure  est effectué une fois chaque <strong>'.$tab[1].' '.$tab[0].'</strong></p>';
										else 
										echo'<p> Cette mesure est effectué une fois chaque <strong>'.$tab[1].' '.$tab[0].'s</strong></p>';
										}
										echo'<p> <span> Informations sur le test : </span>'.$D2['info'].'</p>';
										$R5=$db->prepare('SELECT * FROM user WHERE  id_user=:id_user');
													$R5->execute(array(
													'id_user'=>$D['id_user']
													));
													$D5=$R5->fetch();
										if($D['rapport']!=-1)
										echo'<a href="'.$D['rapport'].'" target="_blank" class="rapport"> rapport  </a>';			
										echo'<p> <span>effectué par : </span><a id="show_user_page" href="show_controle.php?type=user&operation=show&id='.$D5['id_user'].' " target="_top">'.$D5['first'].' '.$D5['last'].'</a></p>';
										if($D['answer']!=-1)
										echo'<p> <span>répondre de l\'attribut :  </span>'.$D['answer'].'</p>';
										echo'<p> <span>De  </span>'.$D['from_done'].'<span>  a   </span>'.$D['to_done'].'</p>';
										echo'<p> <span>Le temp d\'ajout :  </span>'.$D['time'].'</p>';
									
										
										echo'<p><span>Commentaire sure le test</span> : '.$D['comment'].'</p>';

									
									
							
				
				?>
				<p class="clear"></p>
				</div>
				
		</body>
		
</html>