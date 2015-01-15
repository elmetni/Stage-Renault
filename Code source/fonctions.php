<?php
			
function mois($m)
{
		$month=0;
		if($m==1)$month="Janvier";
		else if($m==2) $month="Février";
		else if($m==3) $month="Mars";
		else if($m==4) $month="Avril";
		else if($m==5) $month="Mai";
		else if($m==6) $month="Juin";
		else if($m==7) $month="Juillet";
		else if($m==8) $month="août";
		else if($m==9) $month="Septembre";
		else if($m==10) $month="Octobre";
		else if($m==11) $month="Novembre";
		else if($m==12) $month="Décembre";
		return $month;
		}
function quel_semain($a,$m,$d)
{
			if (($a % 4) == 0){
					$nbrjour = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
				}else{
					$nbrjour = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
					
				
				}
				return week($m,$d,$nbrjour);


}
function count_all_months($a,$m)
{
		$weeks=$m+($a-2007)*12;
		return $weeks;

}			
function test_days($nombre1,$nombre2,$base)
				{
						$days1=$nombre1;
						$days2=$nombre2;
						$days1=$days1/($base);
						$days2=$days2/($base);
						$days1=variant_int($days1);
						$days2=variant_int($days2);
						if($days1==$days2) return true;
						else return false;
				
				
				
				}
		
function week($m_donne,$a_donne,$nbrjour){
																			if($m_donne==0)
																			$m_donne=12;
																				$week=0;
																			for($j=0;$j<$m_donne;$j++)
																			{
																				$week+=$nbrjour[$j];
																			}
																			$week+=$a_donne-1;
																			$week/=7;
																			$week=variant_int($week);
																			$week+=1;
																			
																			return $week;
																			}
function all_weeks($a,$m,$d){
										$weeks2=0;
										for($i=0;$i<($a-2007-1);$i++)
										{
											if(($i+2007)%4==0)
																						
																							$nbrjour = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
																						
																						else
																							$nbrjour = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
												$weeks2+=week(12,31,$nbrjour);
										
										}
										
										$weeks2+=week($m,$d,$nbrjour);
										return $weeks2;
		
									}
		
																				

																				
																	
function count_all_days($a,$m,$d)
{
											if($a%4==0)
														
															$nombre_jour = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
														
														else
															$nombre_jour = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
															
											$years=0;
											$temp=$a-2007;
											for($i=0;$i<$temp;$i++)
											{
														if(($i+2007)%4==0) $years+=366;
														else $years+=365;
											
														}
														
															
											
											
									
											$months=0;
											for($i=0;$i<$m;$i++)
											{
												
												$months+=$nombre_jour[$i];
											
											}
											
											$nbr_all_days=0;
											$nbr_all_days=$months+$years+$d;
											
											
											return $nbr_all_days;

}



function test_done($a,$m,$d,$type,$id_test,$id_support,$re_test)
		{ 
			try
			{
				$db=new PDO('mysql:host='.$_SERVER['HTTP_HOST'].';dbname=surveillance','root','');
			}
			catch(exception $e)
			{
				die('Error :'.$e->getmessage());
			}
			
				$tab=explode('*',$type);
				
						$R177=$db->prepare('SELECT * FROM done WHERE year=:year AND month=:month AND  day=:day AND id_test=:id_test AND  id_support=:id_support AND number=:number');
						$R177->execute(array( 
								'year'=>$a,
								'month'=>$m,								
								'day'=>$d,
								'id_test'=>$id_test,
								'id_support'=>$id_support,
								'number'=>$re_test,
								));
						$D177=$R177->fetch();
						if($D177!=false)
										return $D177['id_done'];
						else
						{				
			
											
											
											$count_all_days=count_all_days($a,$m,$d);											

											$R13=$db->prepare('SELECT * FROM done WHERE id_test=:id_test AND id_support=:id_support AND number=:number AND all_days<:all_days ORDER BY all_days DESC');
											$R13->execute(array(
													'id_test'=>$id_test,
													'id_support'=>$id_support,
													'number'=>$re_test,
													'all_days'=>$count_all_days
													));
											$D13=$R13->fetch();

											
											$R14=$db->prepare('SELECT * FROM done WHERE id_test=:id_test AND id_support=:id_support AND number=:number AND all_days>:all_days ORDER BY all_days ');
											$R14->execute(array(
													'id_test'=>$id_test,
													
													'id_support'=>$id_support,
													'number'=>$re_test,
													'all_days'=>$count_all_days
													));
											$D14=$R14->fetch();
											
											if($D13==false && $D14==false) return false ; 
											else if($D13!=false && $D14==false){
																			if($tab[0]=="jour")
																			{
																						if(($count_all_days-$D13['all_days'])>$tab[1]) return false;
																						else 
																							{
																								
																								if(test_days($count_all_days,$D13['all_days'],$tab[1])) return $D13['id_done'];
																								else return false;
																								
																								
																							}
																					
																					
																						
																						}
																			if($tab[0]=="semaine")
																			{
																								if(($count_all_days-$D13['all_days'])>$tab[1]*7) return false;
																								else
																								{
																								
																								
																								if(test_days(all_weeks($a,$m,$d),all_weeks($D13['year'],$D13['month'],$D13['day']),$tab[1])) return $D13['id_done'];
																								else return false;
																								
																								}
																								
																			}
																			if($tab[0]=="mois")
																			{
																								$temp=count_all_months($a,$m)-count_all_months($D13['year'],$D13['month']);
																								$temp=($m+($a-$D13['year'])*12)-$D13['month'];
																								if($temp>$tab[1]) return false;
																								else
																								{
																										if(test_days(count_all_months($a,$m),count_all_months($D13['year'],$D13['month']),$tab[1])) return $D13['id_done'];
																										else return false;
																								}
																								
																			}
																				
																			}
											else if($D13==false && $D14!=false){
																			if($tab[0]=="jour")
																			{
																						if(($D14['all_days']-$count_all_days)>=$tab[1]) return false;
																						else 
																							{
																								
																								if(test_days($count_all_days,$D14['all_days'],$tab[1])) return $D14['id_done'];
																								else return false;
																							
																								}
																						}
																			if($tab[0]=="semaine")
																			{
																								if(($D14['all_days']-$count_all_days)>$tab[1]*7) return false;
																								else
																								{
																									if(test_days(all_weeks($a,$m,$d),all_weeks($D14['year'],$D14['month'],$D14['day']),$tab[1])) return $D14['id_done'];
																									else return false;
																								}
																			}
																			
																			if($tab[0]=="mois")
																			{
																								$temp=count_all_months($D14['year'],$D14['month'])-count_all_months($a,$m);
																								
																								if($temp>$tab[1]) return false;
																								else
																								{
																										if(test_days(count_all_months($a,$m),count_all_months($D14['year'],$D14['month']),$tab[1])) return $D14['id_done'];
																										else return false;
																								}
																								
																								
																								}
																			}
											
											else 
											{
											
												$resultat_max=$D13['all_days'];
												$resultat_min=$D14['all_days'];
								
											if($tab[0]=="jour")
											{
															if(($count_all_days-$resultat_max)>$tab[1]&&($resultat_min-$count_all_days)>$tab[1]) return false;
																
															else if (($count_all_days-$resultat_max)>$tab[1]&&($resultat_min-$count_all_days)<=$tab[1])
																{
																	if(test_days($count_all_days,$D14['all_days'],$tab[1])) return $D14['id_done'];
																	else return false;
																}
															else
															{		if(test_days($count_all_days,$D13['all_days'],$tab[1])) return $D13['id_done'];
																	else return false;
															}
											
											}
											if($tab[0]=="semaine")
											{
																if(($count_all_days-$resultat_max)>$tab[1]*7&&($resultat_min-$count_all_days)>$tab[1]*7) return false;
																
																else if (($count_all_days-$resultat_max)>$tab[1]*7&&($resultat_min-$count_all_days)<=$tab[1]*7)
																{
																	if(test_days(all_weeks($a,$m,$d),all_weeks($D14['year'],$D14['month'],$D14['day']),$tab[1])) return $D14['id_done'];
																	else return false;
																}
																else
															{		if(test_days(all_weeks($a,$m,$d),all_weeks($D13['year'],$D13['month'],$D13['day']),$tab[1])) return $D13['id_done'];
																	else return false;
																}
											if($tab[0]=="mois")
																{
																		
																		$temp1=count_all_months($a,$m)-count_all_months($D13['year'],$D13['month']);
																		$temp2=count_all_months($D14['year'],$D14['month'])-count_all_months($a,$m);	
																		
																		if($temp1>$tab[1]&&$temp2>$tab[1]) return false;
																		
																		else if($temp1>$tab[1]&&$temp2<=$tab[1])
																		
																		{
																			if(test_days(count_all_months($a,$m),count_all_months($D14['year'],$D14['month']),$tab[1])) return $D14['id_done'];
																			else return false;
																		
																		}
																		else
																		{
																         if(test_days(count_all_months($a,$m),count_all_months($D13['year'],$D13['month']),$tab[1])) return $D13['id_done'];
																		 else return false;
																		}
																
																}
								
								}
							}
						}
				}		

function purcent_image($n)
{
	$p;
	if($n==0) $p="images/degre0.png";
	if($n>0 && $n<=10 ) $p="images/degre1.png";
	if($n>10 && $n<=20 ) $p="images/degre2.png";
	if($n>20 && $n<=30 ) $p="images/degre3.png";
	if($n>30 && $n<=40 ) $p="images/degre4.png";
	if($n>40 && $n<=50 ) $p="images/degre5.png";
	if($n>50 && $n<=60 ) $p="images/degre6.png";
	if($n>60 && $n<=70 ) $p="images/degre7.png";
	if($n>70 && $n<=80 ) $p="images/degre8.png";
	if($n>80 && $n<=90 ) $p="images/degre9.png";
	if($n>90 && $n<=100 ) $p="images/degre10.png";
		
		RETURN $p;
}

function purcent($a,$m,$d,$id_user)
{
					try
			{
				$db=new PDO('mysql:host='.$_SERVER['HTTP_HOST'].';dbname=surveillance','root','');
			}
			catch(exception $e)
			{
				die('Error :'.$e->getmessage());
			}

					$done=0;
					$not_done=0;
					$purcentage=0;
					$R_user=$db->prepare('SELECT * FROM user WHERE id_user=:id_user');
					$R_user->execute(array(
							'id_user'=>$id_user,
							));
					$D_user=$R_user->fetch();
		
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
					{		
							$re_test=$D['re_test'];
							for($i=1;$i<=$re_test;$i++)
							{
									$R2=$db->prepare('SELECT * FROM support WHERE id_test=:id_test');
									$R2->execute(array(
												'id_test'=>$D['id_test']
											));
									$D2=$R2->fetch();
							
							
							if($D2==false){
											$not_done++;
											if(test_done($a,$m,$d,$D['period'],$D['id_test'],-1,$i)!=false) $done++;
											}
							else {
									while($D2!=false)
									{
										
											$not_done++;
											if(test_done($a,$m,$d,$D['period'],$D['id_test'],$D2['id_support'],$i)!=false) $done++;
											$D2=$R2->fetch();
											
									
									}
								
								
								}
								}
								}
								
						
					
					if($not_done == 0)
					{
						$purcentage=100;
						
					}
					else $purcentage=($done/$not_done)*100;
					
					return $purcentage;



}

				
?>






