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
			
			if(isset($_GET['year'])==false OR isset($_GET['month'])==false OR isset($_GET['id_support'])==false OR isset($_GET['id_test'])==false OR isset($_GET['nombre'])==false  OR isset($_GET['day'])==false OR is_numeric($_GET['day'])==false OR is_numeric($_GET['id_support'])==false OR is_numeric($_GET['nombre'])==false  OR  is_numeric($_GET['id_test'])==false  OR is_numeric($_GET['month'])==false OR is_numeric($_GET['year'])==false OR (isset($_GET['month']) && $_GET['month']>12) OR (isset($_GET['month']) && $_GET['month']<1)OR (isset($_GET['year']) && $_GET['year']<2009) OR (isset($_GET['year']) && $_GET['year']>date('Y')) OR (isset($_GET['year']) && isset($_GET['month'])&& $_GET['year']==date('Y') && $_GET['month']>date('n') ) OR (isset($_GET['year']) && isset($_GET['month'])&& isset($_GET['day'])  && $_GET['year']==date('Y') && $_GET['month']==date('n') && $_GET['day']>date('d') )  )
			header('location:show.php?year='.date('Y').'&month='.date('n').'&day='.date('d').'&week='.week(date('n'),date('d'),$nbrjour).'type=jour*1');

			
			
			?>
<html>
		<head>
		<link href="frame.css" rel="stylesheet" type="text/css" media="screen" >
		</head>
		
		<body>
			<div class="all_done">
				<?php
				$ereur=0;
								echo'<div class="not_done_title"> ';
								$R=$db->prepare('SELECT * FROM test WHERE id_test=:id_test');
									$R->execute(array(
													'id_test'=>$_GET['id_test']
													));
									$D=$R->fetch();
									
									echo' <form action="not_done.php?id_test='.$_GET['id_test'].'&year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&id_support='.$_GET['id_support'].'&nombre='.$_GET['nombre'].'" method="POST"  enctype="multipart/form-data"> ';
										echo '<strong>'.$D['parametre'].':</strong> '.$D['name'];
										if($D['re_test']!=1) echo' ('.$_GET['nombre'].')';
										if($_GET['id_support']!=-1)
												{
													$R3=$db->prepare('SELECT * FROM support WHERE  id_support=:id_support');
													$R3->execute(array(
													'id_support'=>$_GET['id_support']
													));
													$D3=$R3->fetch();
													echo '<br> par '.$D3['detail'].': '.$D3['valeur']; 
												}
												
										echo'</div><div class="not_done_content">';
										if($D['mesurable']!=-1)
										{
												if(isset($_POST['upload']) && $_FILES['rapport']['size']==0  && $_FILES['rapport']['error']!=0)
												{
														echo'<p class="error" ><img src="images/waring2.png">Nous avons eu une erreur dans le téléchargement ... s\'il vous plaît essayer à nouveau plus tard</p>' ;
														$ereur=1;
														
												
												}
										}
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
										echo'<input type="submit" name="upload" class="upload" value="">';
										echo'<p> <span>effectue par : </span><a id="show_user_page" href="show_controle.php?type=user&operation=show&id='.$D5['id_user'].' " target="_top">'.$D5['first'].' '.$D5['last'].'</a></p>';
										
											 if(isset($_POST['upload']) && $_POST['from_hour']==0 &&  $_POST['from_min']==0 && $_POST['to_hour']==0 && $_POST['to_min']==0 ) 
												{
												echo'<p class="error" ><img src="images/waring2.png" > Vous ne pouvez pas laisser ce champ vide</p>' ;
												$ereur=1;
												}
											echo'<p> <span>De  </span><select name="from_hour">';
											
											for($j=0;$j<24;$j++)
											{
											if($j<10)
											{
											echo'<option value="0'.$j.'"';
											if(isset($_POST['from_hour']) && $j==$_POST['from_hour'] ) echo 'selected';
													
													else if($j==(date('H')-1)) echo 'selected';
											
											echo'>0'.$j.'</option>';
											
											}
											else
											{
											
											echo'<option value="'.$j.'"';
											if(isset($_POST['from_hour']) && $j==$_POST['from_hour'] ) echo 'selected';
													
													else if($j==(date('H')-1)) echo 'selected';
											
											echo'>'.$j.'</option>';
											}							
											}
											echo'</select>:<select name="from_min">';
											
											for($j=0;$j<60;$j++)
											{
													
													if($j<10)
													{
													echo'<option value="0'.$j.'"';
													if(isset($_POST['from_min']) && $j==$_POST['from_min'] ) echo 'selected';
													
													else if($j==date(('i')+20)) echo 'selected';
													
													echo'>0'.$j.'</option>';
													
													}
													else
													{
													
													echo'<option value="'.$j.'"';
													if(isset($_POST['from_min']) && $j==$_POST['from_min'] ) echo 'selected';
													
													else if($j==(date('i')+20)) echo 'selected';
													
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
											if(isset($_POST['to_hour']) && $j==$_POST['to_hour'] ) echo 'selected';
													
													else if($j==(date('H'))) echo 'selected';
											
											echo'>0'.$j.'</option>';
											
											}
													else
													{
													
													echo'<option value="'.$j.'"';
													if(isset($_POST['to_hour']) && $j==$_POST['to_hour'] ) echo 'selected';
													
													else if($j==(date('H'))) echo 'selected';
													
													echo'>'.$j.'</option>';
													}							
											}
											echo'</select>:<select name="to_min">';
											
											for($j=0;$j<60;$j++)
											{
													
													if($j<10)
													{
													echo'<option value="0'.$j.'"';
													if(isset($_POST['to_min']) && $j==$_POST['to_min'] ) echo 'selected';
													
													else if($j==(date('i')-5)) echo 'selected';
													
													echo'>0'.$j.'</option>';
													
													}
													else
													{
													
													echo'<option value="'.$j.'"';
													if(isset($_POST['to_min']) && $j==$_POST['to_min'] ) echo 'selected';
													
													else if($j==(date('i')-5)) echo 'selected';
													
													echo'>'.$j.'</option>';
											}
																			
											}
											echo'</select> </p>';
										if($D['mesurable']==1)
										{
										if(isset($_POST['upload']) && $_FILES['rapport']['size']==0) 
												{
												echo'<p class="error" ><img src="images/waring2.png"> Vous ne pouvez pas laisser ce champ vide</p>' ;
												$ereur=1;
												}
										
										echo '<p> <span>Votre rapport :</span> ';
										echo'<input type="file" name="rapport"></p>';
										}
										if($D['attribut']==1)
										{
										
										if(isset($_POST['upload']) && $_POST['attribut']==-1) 
												{
												echo'<p class="error" ><img src="images/waring2.png" > Vous ne pouvez pas laisser ce champ vide</p>' ;
												$ereur=1;
												}
										
										echo '<p> <span>Attribut :</span> ';
										echo'<select name="attribut" > <option value="-1" > choisir </option>  <option value="oui" > Oui </option> <option value="Non" > Non </option> </select></p>';
										
										
										}
										echo '<p> <span>Le  PJI :</span> <input type="text" name="pji" id="pji" ></p>';
										echo '<p> <span>Votre commentaire</span></p>';
										echo'<textarea  name="comment" COLS="80" ROWS="5"   WRAP="virtual">';
										if(isset($_POST['comment'])) echo htmlspecialchars($_POST['comment']); 
										echo'</textarea>';
									echo'<div >';
									
									
									
									if(isset($_POST['upload']) && $ereur==0)
											{
											
												$rapport=0;
												if($D['mesurable']==-1)
												$rapport=-1;
												else
												{
														$infosfichier = pathinfo($_FILES['rapport']['name']);
														if($_GET['id_support']==-1)
														{
															$rapport='rapports/'.$_GET['year'].'/'.mois($_GET['month']).'/'.$_GET['day'].'/'.$D['parametre'].' '.$D['name'].'    '.$_POST['pji'].' '.$_GET['day'].' '.$_GET['year'].' '.mois($_GET['month']).'   '.$D5['first'].' '.$D5['last'].'   '. date('H').'h '.date('i').'min.'.$infosfichier['extension'];
														
														}		
														
														else	
														{
															$rapport='rapports/'.$_GET['year'].'/'.mois($_GET['month']).'/'.$_GET['day'].'/'.$D['parametre'].' '.$D['name'].'   '.$_POST['pji'].' '.$D3['detail'].'='.$D3['valeur'].' '.$_GET['day'].' '.$_GET['year'].' '.mois($_GET['month']).'   '.$D5['first'].' '.$D5['last'].'   '. date('H').'h '.date('i').'min.'.$infosfichier['extension'];
															
															
														}
												}
												
												move_uploaded_file($_FILES['rapport']['tmp_name'],$rapport);
												
												
												$from=0;
												$to=0;
												$from=$_POST['from_hour'].':'.$_POST['from_min'];
												$to=$_POST['to_hour'].':'.$_POST['to_min'];
												
												if($D['attribut']==1)
												{
												$attribut=$_POST['attribut'];
												}
												else $attribut=-1;
																	
																$R12=$db->prepare('INSERT INTO done VALUES(\'\',:day,:month,:year,:id_test,:comment,:rapport,NOW(),:id_user,:from,:to,:id_support,:all_days,:number,:answer,:pji)');
																											
																$R12->execute(array(
																				
																				'day'=>$_GET['day'],
																				'month'=>$_GET['month'],
																				'year'=>$_GET['year'],
																				'id_test'=>$D['id_test'],
																				'comment'=>$_POST['comment'],
																				'rapport'=>$rapport,
																				'id_user'=>$D5['id_user'],
																				'from'=>$from,
																				'to'=>$to,
																				'id_support'=>$_GET['id_support'],
																				'all_days'=>count_all_days($_GET['year'],$_GET['month'],$_GET['day']),
																				'number'=>$_GET['nombre'],
																				'answer'=>$attribut,
																				'pji'=>$_POST['pji']
																			)
																			);
																	
																	echo'<script language="JavaScript" type="text/javascript">
																	parent.location ="show.php?year='.$_GET['year'].'&month='.$_GET['month'].'&day='.$_GET['day'].'&type='.$D['period'].'";
																	</script>
																	';
															
												}
												
													
													
															
													
												
										
				?>
				<div> <!-------------- aLL done -------------->
</body>

</html>