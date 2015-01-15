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
			
			if(isset($_GET['year'])==false OR isset($_GET['day'])==false OR  isset($_GET['month'])==false OR is_numeric($_GET['month'])==false OR is_numeric($_GET['year'])==false OR is_numeric($_GET['day'])==false OR (isset($_GET['month']) && $_GET['month']>12) OR (isset($_GET['month']) && $_GET['month']<1)OR (isset($_GET['year']) && $_GET['year']<2009) OR (isset($_GET['year']) && $_GET['year']>date('Y')) OR (isset($_GET['year']) && isset($_GET['month'])&& $_GET['year']==date('Y') && $_GET['month']>date('n') ) )

			echo'<script language="JavaScript" type="text/javascript">
																	parent.location ="show.php?year='.date('Y').'&month='.date('n').'&day='.date('d').'type=jour*1";
																	</script>
																	';
							
	
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
				$ereur=0;
								echo'<div class="edit_done_title"> ';
								$R_done=$db->prepare('SELECT * FROM done WHERE id_done=:id_done');
									$R_done->execute(array(
													'id_done'=>$_GET['id']
													));
									$D_done=$R_done->fetch();
									
									if($D_done['id_user']!=$_SESSION['id'] && $D_user['statue']=="user")
									{
									
									header('location:agenda.php');
									
									}
									
								$R=$db->prepare('SELECT * FROM test WHERE id_test=:id_test');
									$R->execute(array(
													'id_test'=>$D_done['id_test']
													));
									$D=$R->fetch();
									
									echo' <form action="edit_done.php?id='.$_GET['id'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'" method="POST"  enctype="multipart/form-data"> ';
										echo '<strong>'.$D['parametre'].':</strong> '.$D['name'];
										if($D['re_test']!=1) echo' ('.$D_done['number'].')';
										if($D_done['id_support']!=-1)
												{
													$R3=$db->prepare('SELECT * FROM support WHERE  id_support=:id_support');
													$R3->execute(array(
													'id_support'=>$D_done['id_support']
													));
													$D3=$R3->fetch();
													echo '<br> par '.$D3['detail'].': '.$D3['valeur']; 
												}
												
										echo'</div><div class="edit_done_content">';
										
										echo'<p> <span> Semain '.quel_semain($_GET['year'],$_GET['month'],$_GET['day']).' : '.$_GET['day'].' '.mois($_GET['month']).' '.$_GET['year'].'</span></p>';
										
										$tab=explode('*',$D['period']);
										if($tab[1]==1)
										echo'<p> Ce test est effectué une fois par  <strong>'.$tab[0].'</strong></p>';
										else 
										{
										if($tab[0]=="mois")
										echo'<p> Ce test est effectué une fois chaque <strong>'.$tab[1].' '.$tab[0].'</strong></p>';
										else 
										echo'<p> Ce test est effectué une fois chaque <strong>'.$tab[1].' '.$tab[0].'s</strong></p>';
										}
										
										echo'<p> <span> Informations sur le test : </span>'.$D['info'].'</p>';
										$R5=$db->prepare('SELECT * FROM user WHERE  id_user=:id_user');
													$R5->execute(array(
													'id_user'=>$D['id_user']
													));
													$D5=$R5->fetch();
										echo'<input type="submit" name="edit_done" class="edit_done" value="">';
										echo'<p> <span>effectue par : </span>'.$D5['first'].' '.$D5['last'].'</p>';
										
											echo'<p> <span>De  </span><select name="from_hour">';
											$from_done=explode(':',$D_done['from_done']);
											$to_done=explode(':',$D_done['to_done']);
											
											
											for($j=0;$j<24;$j++)
											{
											if($j<10)
											{
											echo'<option value="0'.$j.'"';
											if($j==$from_done[0]) echo 'selected';
											
											echo'>0'.$j.'</option>';
											
											}
											else
											{
											
											echo'<option value="'.$j.'"';
											if($j==$from_done[0]) echo 'selected';
											
											echo'>'.$j.'</option>';
											}							
											}
											echo'</select>:<select name="from_min">';
											
											for($j=0;$j<60;$j++)
											{
													
													if($j<10)
													{
													echo'<option value="0'.$j.'"';
													 if($j==$from_done[1]) echo 'selected';
													
													echo'>0'.$j.'</option>';
													
													}
													else
													{
													
													echo'<option value="'.$j.'"';
													 if($j==$from_done[1]) echo 'selected';
													
													echo'>'.$j.'</option>';
											}
																			
											}
											echo'</select>';
											
											
											echo' <span> &nbsp;&nbsp; jusqu\'à  </span><select name="to_hour">';
											
											for($j=0;$j<24;$j++)
											{
											if($j<10)
											{
											echo'<option value="0'.$j.'"';
											if($j==$to_done[0]) echo ' selected ';
											
											echo'>0'.$j.'</option>';
											
											}
													else
													{
													
													echo'<option value="'.$j.'"';
													if($j==$to_done[0]) echo ' selected ';
													
													echo'>'.$j.'</option>';
													}							
											}
											echo'</select>:<select name="to_min">';
											
											for($j=0;$j<60;$j++)
											{
													
													if($j<10)
													{
													echo'<option value="0'.$j.'"';
													 if($j==$to_done[1]) echo ' selected ';
													
													echo'>0'.$j.'</option>';
													
													}
													else
													{
													
													echo'<option value="'.$j.'"';
													 if($j==$to_done[1]) echo ' selected ';
													
													echo'>'.$j.'</option>';
											}
																			
											}
											echo'</select> </p>';
										
										
										echo '<p> <span>Votre rapport :</span> ';
										echo'<input type="file" name="rapport" value="'.$D_done['rapport'].'"></p>';
										
										if($D['attribut']==1)
										{
										
										
										echo '<p> <span>Attribut :</span> ';
										echo'<select name="attribut" >  <option value="oui"';
										if($D_done['answer']=="oui") echo"selected";
										else if(isset($_POST['attribut']) && $_POST['attribut']=="oui") echo" selected ";
										echo'> Oui </option> <option value="non"';
										if($D_done['answer']=="non") echo"selected";
										else if(isset($_POST['attribut']) && $_POST['attribut']=="non") echo" selected ";
										echo' > Non </option> </select></p>';
										
										
										}
										echo '<p> <span>Le  PJI :</span> <input type="test" name="pji" id="pji" ';
										echo 'value="'.$D_done['pji'].'"';
										echo'></p>';
										echo '<p> <span>Votre commentaire</span></p>';
										echo'<textarea  name="comment" COLS="80" ROWS="5"   WRAP="virtual">'.$D_done['comment'].'</textarea>';
									echo'<div >';
									
									
									
									if(isset($_POST['edit_done']))
											{
											
												$rapport=0;
												if($_FILES['rapport']['size']!=0)
												{
														$infosfichier = pathinfo($_FILES['rapport']['name']);
														
														if($D_done['id_support']==-1)
														{
															$rapport='rapports/'.$_GET['year'].'/'.mois($_GET['month']).'/'.$_GET['day'].'/'.$D['parametre'].' '.$D['name'].'    '.$_POST['pji'].' '.$_GET['day'].' '.$_GET['year'].' '.mois($_GET['month']).'   '.$D5['first'].' '.$D5['last'].'   '. date('H').'h '.date('i').'min.'.$infosfichier['extension'];
														
														}		
														
														else	
														{
															$rapport='rapports/'.$_GET['year'].'/'.mois($_GET['month']).'/'.$_GET['day'].'/'.$D['parametre'].' '.$D['name'].'   '.$_POST['pji'].' '.$D3['detail'].'='.$D3['valeur'].' '.$_GET['day'].' '.$_GET['year'].' '.mois($_GET['month']).'   '.$D5['first'].' '.$D5['last'].'   '. date('H').'h '.date('i').'min.'.$infosfichier['extension'];
															
															
														}
														move_uploaded_file($_FILES['rapport']['tmp_name'],$rapport);
												}
												
												else $rapport=$D_done['rapport'];
												
												
												
												
												$from2=0;
												$to2=0;
												$from2=$_POST['from_hour'].':'.$_POST['from_min'];
												$to2=$_POST['to_hour'].':'.$_POST['to_min'];
												
												
												ECHO $from2.' '.$to2;
												if($D['attribut']==1)
												{
												$attribut=$_POST['attribut'];
												}
												else $attribut=-1;
																	
													
												$update1=$db->prepare('UPDATE done SET from_done=:from_done WHERE id_done=:id');
												$update1->execute(array(
															'from_done'=>$from2,
															'id'=>$D_done['id_done']
															));
												$update1=$db->prepare('UPDATE done SET to_done=:to_done WHERE id_done=:id');
												$update1->execute(array(
														'to_done'=>$to2,
														'id'=>$D_done['id_done']
														));
												
												$update1=$db->prepare('UPDATE done SET rapport=:rapport WHERE id_done= :id');
												$update1->execute(array(
												'rapport'=>$rapport,
												'id'=>$D_done['id_done']
												));
												
												$update1=$db->prepare('UPDATE done SET answer=:answer WHERE id_done= :id');
												$update1->execute(array(
												'answer'=>$attribut,
												'id'=>$D_done['id_done']
												));
												
												$update1=$db->prepare('UPDATE done SET pji=:pji WHERE id_done= :id');
												$update1->execute(array(
												'pji'=>$_POST['pji'],
												'id'=>$D_done['id_done']
												));
												
												$update1=$db->prepare('UPDATE done SET comment=:comment WHERE id_done= :id');
												$update1->execute(array(
												'comment'=>$_POST['comment'],
												'id'=>$D_done['id_done']
												));
												$update1=$db->prepare('UPDATE done SET time=NOW() WHERE id_done= :id');
												$update1->execute(array(
												
												'id'=>$D_done['id_done']
												));
													
													echo'<script language="JavaScript" type="text/javascript">
																	parent.location ="show.php?year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&type='.$D['period'].'";
																	</script>
																	';		
															
												}
												
													
													
															
													
												
										
				?>
				<div> <!-------------- aLL done -------------->
</body>

</html>