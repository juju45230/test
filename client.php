<?php 
require_once("includes/config.php");
require_once("includes/functions.php");
require_once('app/class/database.php');

// Services
$db = new Database();

// Vérification connecté
session_start();
if(!isset($_SESSION['login'])) {
	header("Location: $configBaseUrL/index");
	exit();
}
else
{	
	if(!isset($_COOKIE['session'])) {
		header("Location: $configBaseUrL/logout");
		exit();
	}
	else
	{
		if($_SESSION['login'] != $_COOKIE['session'])
		{
			header("Location: $configBaseUrL/logout");
			exit();
		}
	}
	
	$userInfo = $db->executeQuery('SELECT * FROM users WHERE id=?', array($_SESSION['login']));
	if(!isset($_SESSION['pin'])) {
		header("Location: $configBaseUrL/pin");
		exit();
	}
	else if($_SESSION['pin'] != $userInfo[0]["pin"]) {
		header("Location: $configBaseUrL/logout");
		exit();
	}
	
	if($userInfo[0]["online"] == 0)
	{
		$ssoTicket = "BOBBARP-".getSecretKey(20);
		$db->executeInsert('UPDATE users SET auth_ticket = ? WHERE id = ?', array($ssoTicket, $userInfo[0]['id']));
	}
	else
	{
		header("Location: $configBaseUrL/home?error=logged");
		exit();
	}
	
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title><?php echo $configName; ?></title>
		<link rel="icon" type="image/png" href="<?php echo $configAssetsUrL; ?>/images/favicon.png" />
		<meta property="og:image" content="<?php echo $configAssetsUrL; ?>/images/logo_info.png" />
		<meta charset="UTF-8"/>
		<link type="text/css" rel="stylesheet" href="<?php echo $configAssetsUrL; ?>/css/client.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo $configAssetsUrL; ?>/css/color.css"/>
		<link rel="stylesheet" href="<?php echo $configAssetsUrL; ?>/css/animate.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto|Anton|Ubuntu:400,500,700" rel="stylesheet">

		<script src="<?php echo $configAssetsUrL; ?>/flashclient/js/libs2.js?2" type="text/javascript"></script>
		<script src="<?php echo $configAssetsUrL; ?>/flashclient/js/visual.js?2" type="text/javascript"></script>
		<script src="<?php echo $configAssetsUrL; ?>/flashclient/js/libs.js?2" type="text/javascript"></script>
		<script src="<?php echo $configAssetsUrL; ?>/flashclient/js/common.js?2" type="text/javascript"></script>
		<script src="<?php echo $configAssetsUrL; ?>/flashclient/js/habboflashclient.js?2" type="text/javascript"></script>
		
		<script type="text/javascript"> 
			var andSoItBegins = (new Date()).getTime();
			var ad_keywords = "";
			document.habboLoggedIn = true;
			var habboName = "<?php echo $userInfo[0]["username"]; ?>";
			var habboId = "<?php echo $userInfo[0]["id"]; ?>";
			var habboFigure = "<?php echo $userInfo[0]["look"]; ?>";
			var habboReqPath = "<?php echo $configBaseUrL; ?>";
			var habboStaticFilePath = "<?php echo $configBaseUrL; ?>/web-gallery";
			var habboImagerUrl = "http://habbo.fr/habbo-imaging//habbo-imaging/";
			var habboPartner = "";
			var habboDefaultClientPopupUrl = "<?php echo $configBaseUrL; ?>/client";
			window.name = "habboMain";
			if (typeof HabboClient != "undefined") { HabboClient.windowName = "uberClientWnd"; }
		</script> 
	</head> 
	
	<body> 
		<div id="loading">
			<div class="resize">
				<img src="<?php echo $configAssetsUrL; ?>/images/client_loading1.png" class="banner">
				<h2>Bienvenue <?php echo $userInfo[0]["username"]; ?>...</h2>
				<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
			</div>
		</div>
		
		<div id="myPerso" class="hided">
			<div id="tabIcon"><i class="fas fa-bars"></i></div>
			<div class="username"><img src="<?php echo $configAssetsUrL; ?>/images/myhabbo.gif"> <?php echo $userInfo[0]["username"]; ?> </div>
			<hr />
			<div class="container">
			
			</div>
		</div>
		
		<div id="my_stats">
			<div class="argent_propre">
				<span id="statsPropreCount">0</span> 
				<div class="icon"><img src="<?php echo $configAssetsUrL; ?>/images/propre.png" style="margin-top: 5px"></div>
			</div>
			<div class="argent_sale">
				<span id="statsSaleCount">0</span> 
				<div class="icon"><img src="<?php echo $configAssetsUrL; ?>/images/sale.png"></div>
			</div>
			<div class="event_points">
				<span id="statsEventCount">0</span> 
				<div class="icon"><img src="<?php echo $configAssetsUrL; ?>/images/gang_noel.png"></div>
			</div>
		</div>
		
		<div id="eventMission">
			<h2><img src="<?php echo $configAssetsUrL; ?>/images/gang_noel.png"> Nouvelle mission</h2>
			<div class="eventMissionText">
				<img src="" id="eventMissionImage">
				<h3 id="eventMissionName"></h3>
				<p id="eventMissionDesc"></p>
				
				<div class="clearfix"></div>
			</div>
		</div>
		
		<div id="eventRecompense">
			<div class="close"><i class="fas fa-times"></i></div>
			<h2><img src="<?php echo $configAssetsUrL; ?>/images/gang_noel.png"> Félicitations, vous avez recolté <span id="gantsCount"></span> gants</h2>
			<hr>
			
			<h3>Vous avez gagné<h3>
			<div class="item" id="eventRecompenseCredits"><img src="<?php echo $configAssetsUrL; ?>/images/credits.gif"> <div class="number">0</div></div>
			<div class="item" id="eventRecompenseCoca"><img src="<?php echo $configAssetsUrL; ?>/images/consommables/coca.png"> <div class="number">5</div></div>
			<div class="item" id="eventRecompenseSucette"><img src="<?php echo $configAssetsUrL; ?>/images/consommables/sucette.png"> <div class="number">15</div></div>
			<div class="item" id="eventRecompenseJetons"><img src="<?php echo $configAssetsUrL; ?>/images/consommables/jetons.png"> <div class="number">10</div></div>
			<div class="item" id="eventRecompenseHoverBoard"><img src="<?php echo $configAssetsUrL; ?>/images/vehicules/whitehover.png"></div>
			<div class="item" id="eventRecompenseAudi"><img src="<?php echo $configAssetsUrL; ?>/images/vehicules/audia3.png"></div>
		</div>
		
		<div id="quizzQuestion">
			<h2><i class="fas fa-question-circle"></i> Nouvelle question</h2>
			<div class="quizzQuestionText"></div>
		</div>
		
		<div id="footballScore">
			BLEU <div class="blue"></div>
			<div class="blueScore">0</div>
			-
			<div class="greenScore">0</div>
			<div class="green"></div> VERT
		</div>
		
		<div id="radio">
			<i class="fas fa-music"></i> RADIO
			<select id="radioChoose">
				<option>Aucune</option>
				<option>NRJ</option>
				<option>Fun Radio</option>
				<option>Skyrock</option>
				<option>Virgin Radio</option>
			</select>
		</div>
		
		<div id="capture">
			<div class="progress_bar"><div class="pourcent">98%</div></div>
		</div>
		
		<div id="videoYoutube">
		</div>
		
		<div id="last_action">
		</div>
		
		<div id="map_content">
			<div class="close"><i class="fas fa-times"></i></div>
			<iframe src=""></iframe>
		</div>
		
		<div id="hem">
			<div class="infosLook">
			</div>
		</div>
		
		<div id="changeLook">
			<div class="infosLook">
			</div>
		</div>
		
		<div id="foutain">
			<button id="foutainJetter" class="jeter"><img src="<?php echo $configAssetsUrL; ?>/images/credits.gif"> Jeter</button>
			<button id="foutainRecuperer" class="recuperer"><img src="<?php echo $configAssetsUrL; ?>/images/hand.gif"> Récupérer</button>
		</div>
				
		<div id="slot_machine">
			<div id="slot_machine_jetons">0</div>
			<div id="slot_machine_turn"></div>
			
			<div class="slot_case" id="slot_one">
				<div class="list">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/orange.png" class="orange">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/cherry.png" class="cerise">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/jackpot.png" class="jackpot">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/apple.png" class="pomme">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/raspberry.png" class="framboise">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/orange.png" class="orange">
				</div>
			</div>
			
			<div class="slot_case" id="slot_two">
				<div class="list">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/orange.png" class="orange">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/cherry.png" class="cerise">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/jackpot.png" class="jackpot">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/apple.png" class="pomme">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/raspberry.png" class="framboise">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/orange.png" class="orange">
				</div>
			</div>
			
			<div class="slot_case" id="slot_three">
				<div class="list list3">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/orange.png" class="orange">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/cherry.png" class="cerise">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/jackpot.png" class="jackpot">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/apple.png" class="pomme">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/raspberry.png" class="framboise">
					<img src="<?php echo $configAssetsUrL; ?>/images/slot/orange.png" class="orange">
				</div>
			</div>
		</div>
		
		<div id="computer" class="animated fadeInUp">
			<div id="topComputer"></div>
			
			<div class="overlay">
				<div class="orpi" >
					<div class="header">
						<div class="logo"><img src="<?php echo $configAssetsUrL; ?>/images/orpi_logo.png"></div>
						
						<ul class="menu">
							<li id="accueil" class="active">Accueil</li>
							<li id="apparts">Apparts</li>
						</ul>
						
						<div class="clearfix"></div>
					</div>
					
					<div class="home">
						<div class="desc">
							<span>Orpi</span> c'est :<br />
							<span id="orpiAppartLoued"></span> appartements loués<br />
							<span id="orpiAppartALoued"></span> appartements à louer<br />
							et... <span>une équipe engagée</span> !
						</div>
					</div>
					
					<div class="apparts">
						<h2>Appartements à louer</h2>
						<table>
							<thead>
								<tr>
									<th>ID</th>
									<th>Nom de l'appartement</th>
									<th>Prix</th> 
									<th style="text-align: right">Action</th>
								</tr>
							</thead>
							<tbody id="appartsRow">
							</tbody>
						</table>
					</div>
					
					<div class="louer_appart">
						<i class="fas fa-times"></i>
						
						<input type="hidden" id="louerAppartFormId" value="" disabled></input>
						<input type="text" id="louerAppartFormNickname" placeholder="Pseudonyme" maxlength="20"></input>
						<input type="submit" id="louerAppartFormButton" value="Louer"></input>
					</div>
				</div>
			</div>
		</div>
		
		<div id="phone" class="hided close">
			<div id="home"></div>
			<div id="top"></div>
			
			<div id="overlay">
				<div class="header">
					<i class="fas fa-circle" id="reseau1"></i>
					<i class="fas fa-circle" id="reseau2"></i>
					<i class="fas fa-circle" id="reseau3"></i>
					<i class="fas fa-circle" id="reseau4"></i>
					<i class="far fa-circle" id="reseau5"></i>
					BOUYGUES 
					<div id="tel_wifi">3G</div>
					
					<div class="eteindre"><i class="fas fa-power-off"></i></div>
					<div class="heure"><?php echo date("H:i"); ?></div>
				</div>
				
				<div id="menu">
					<ul>
						<li id="tel_phone"><img src="<?php echo $configAssetsUrL; ?>/images/phone_icon.png"></li>
						<li id="tel_contact"><img src="<?php echo $configAssetsUrL; ?>/images/contact.png"></li>
						<li id="tel_sms"><img src="<?php echo $configAssetsUrL; ?>/images/sms.png"><div class="notifications">0</div></li>
						<li id="tel_banque"><img src="<?php echo $configAssetsUrL; ?>/images/banquepopulaire.png"></li>
						<li id="tel_youtube"><img src="<?php echo $configAssetsUrL; ?>/images/youtube.png"></li>
						<li id="tel_bouygues"><img src="<?php echo $configAssetsUrL; ?>/images/bouygues.png"></li>
						<li id="tel_flappy"><img src="<?php echo $configAssetsUrL; ?>/images/flappy.png"></li>
						<li id="tel_calculatrice"><img src="<?php echo $configAssetsUrL; ?>/images/calculatrice.png"></li>
					</ul>
				</div>
				
				<div id="flappy">
					<canvas id="flappyBirdGame"></canvas>
				</div>
				
				<div id="calculatrice">
					<table>
						<tr>
							<td colspan="4" align="right" id="case">
								<span id="resultat"></span>
							</td>
						</tr>
					</table>
					<table style="margin-top: 5px">
						<tr>
							<td>
								<input class="number" type="button" value="1" id="1"/>
							</td>
							<td>
								<input class="number" type="button" value="2" id="2"/>
							</td>
							<td>
								<input class="number" type="button" value="3" id="3"/>
							</td>
							<td>
								<input class="operator clean_button" type="button" value="C"/>
							</td>
						</tr>

						<tr>
							<td>
								<input class="number" type="button" value="4" id="4"/>
							</td>
							<td>
								<input class="number" type="button" value="5" id="5"/>
							</td>
							<td>
								<input class="number" type="button" value="6" id="6"/>
							</td>
							<td>
								<input class="operator btn_plus" type="button" value="+"/>
							</td>
						</tr>

						<tr>
							<td>
								<input class="number" type="button" value="7" id="7"/>
							</td>
							<td>
								<input class="number" type="button" value="8" id="8"/>
							</td>
							<td>
								<input class="number" type="button" value="9" id="9"/>
							</td>
							<td>
								<input class="operator btn_moins" type="button" value="-"/>
							</td>
						</tr>

						<tr>
							<td>
								<input class="operator btn_multi" type="button" value="x"/>
							</td>
							<td>
								<input class="number"type="button" value="0" id="0"/>
							</td>
							<td>
								<input class="operator btn_division" type="button" value="÷"/>
							</td>
							<td>
								<input class="operator btn_egal" type="button" value="="/>
							</td>
						</tr>

					</table>
				</div>
				
				<div id="bouygues">
					<img src="<?php echo $configAssetsUrL; ?>/images/bouygues.png">
					<div class="desc">
						Forfait <span id="forfaitName"></span><br/>
						IL VOUS RESTE :<br />
						<span id="smsCount"></span> sms et <span id="appelCount"></span> minutes d'appel
					</div>
					<input type="submit" id="resetForfait" value="Renouveler"></input>
				</div>
				
				<div id="contacts">
				</div>
				
				<div id="sms">
				</div>
				
				<div id="sms_user">
				</div>
				
				<div id="appel_user">
					<h1>Appeler un utilisateur</h1>
					<input type="text" id="appel_user_userId" placeholder="Pseudonyme"></input>
					<input type="submit" id="appel_user_button" value="Appeler"></input>
				</div>
				
				<div id="banque">
					<img src="<?php echo $configAssetsUrL; ?>/images/banquepopulaire.png">
					<div class="clearfix"></div>
					Bienvenue <b><?php echo $userInfo[0]["username"]; ?></b>
					<div class="clearfix"></div>
					<span>Votre solde bancaire est de <b id="banque_solde">0</b> crédits.</span>
				</div>
				
				<div id="appel">
					<div class="avatar"><img src="//habbo.fr/habbo-imaging/avatarimage?figure=<?php echo $userInfo[0]["look"]; ?>&head_direction=3&gesture=sml&size=l&headonly=1"></div>
					<h1></h1>
					
					<div id="repondre">
						<i class="fas fa-phone"></i>
					</div>
					
					<div id="raccrocher">
						<i class="fas fa-phone-slash"></i>
					</div>
					
				</div>
				
				<div id="youtube">
					<input id="searchYtInput" type="text" onkeyup="searchYoutube()" onkeyDown="searchYoutube()" placeholder="Rechercher une vidéo..."></input>
					
					<div class="search_result">
						<h1>Résultats de la recherche</h1>
						<ul id="yt_results">
						</ul>
					</div>
					
					<div class="video">
						<iframe width="190" height="140" src="https://www.youtube.com/embed/?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen id="yt_iframe"></iframe>
						
						<div class="video_informations">
							<h1 id="yt_title"></h1>
							<div class="clearfix"></div>
							<hr>
							<p id="yt_description"><p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="clientContainer">
			<div id="client-ui" > 
				<div id="flash-wrapper"> 
					<div id="WantedIcon"><i class="fas fa-star"></i></div>
					<div id="RouletteIcon"><i class="fas fa-trophy"></i></div>
					
					<div id="roulette_casino">
						<div class="plate" id="plate">
							<ul class="inner">
								<li class="number"><label><input type="radio" name="pit" value="32" /><span class="pit">32</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="15" /><span class="pit">15</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="19" /><span class="pit">19</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="4" /><span class="pit">4</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="21" /><span class="pit">21</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="2" /><span class="pit">2</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="25" /><span class="pit">25</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="17" /><span class="pit">17</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="34" /><span class="pit">34</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="6" /><span class="pit">6</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="27" /><span class="pit">27</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="13" /><span class="pit">13</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="36" /><span class="pit">36</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="11" /><span class="pit">11</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="30" /><span class="pit">30</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="8" /><span class="pit">8</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="23" /><span class="pit">23</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="10" /><span class="pit">10</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="5" /><span class="pit">5</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="24" /><span class="pit">24</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="16" /><span class="pit">16</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="33" /><span class="pit">33</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="1" /><span class="pit">1</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="20" /><span class="pit">20</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="14" /><span class="pit">14</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="31" /><span class="pit">31</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="9" /><span class="pit">9</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="22" /><span class="pit">22</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="18" /><span class="pit">18</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="29" /><span class="pit">29</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="7" /><span class="pit">7</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="28" /><span class="pit">28</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="12" /><span class="pit">12</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="35" /><span class="pit">35</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="3" /><span class="pit">3</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="26" /><span class="pit">26</span></label></li>
								<li class="number"><label><input type="radio" name="pit" value="0" /><span class="pit">0</span></label></li>
							</ul>
							<div class="data">
								<div class="data-inner">
									<div class="mask">Faites vos jeux</div>
									<div class="result">
										<div class="result-number">0</div>
										<div class="result-color">rouge</div>        
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div id="quizzClassement">
						<h2><i class="fas fa-trophy"></i> Classement - <span id="quizzClassementPoints">0</span> points</h2>
						<div class="quizzClassementContent">
						</div>
					</div>
					
					<!-- START BOX -->
					<div class="containerBox" id="rankTravail" style="width: 295px;">
						<h1>Modifier le rang de <span id="rankUsername"></span> <div class="close"><i class="fas fa-times"></i></div></h1>
						<div class="boxContent">
							<div class="edit_rank">
								<div class="avatar">
									<img src="" id="avatarRank">
								</div>
								<div class="buttons">
									<div id="user_rank"></div>
									<button id="promouvoir">Promouvoir</button>
									<button id="retrograder">Rétrograder</button>
								</div>
							</div>
							
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="containerBox" id="tradeUser" style="width: 448px;">
						<h1>Échange avec <span class="tradeUserUsername"></span> <div class="close"><i class="fas fa-times"></i></div></h1>
						<div class="boxContent" style="max-height: 700px;">
							<div class="my_items">
								<ul>
									<li id="credits" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/credits.gif">
										<div class="number">0</div>
									</li>
									
									<li id="telephone">
										<img src="<?php echo $configAssetsUrL; ?>/images/telephone.png">
										<div class="number">iPhone XS</div>
									</li>
									
									<li id="gps">
										<img src="<?php echo $configAssetsUrL; ?>/images/gps.png">
										<div class="number">GPS</div>
									</li>
									
									<li id="coca" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/coca.png">
										<div class="number">0</div>
									</li>
									
									<li id="fanta" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/fanta.png">
										<div class="number">0</div>
									</li>
									
									<li id="pain" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/pain.png">
										<div class="number">0</div>
									</li>
									
									<li id="sucette" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/sucette.png">
										<div class="number">0</div>
									</li>
									
									<li id="savon" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/savon.png">
										<div class="number">0</div>
									</li>
									
									<li id="doliprane" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/doliprane.png">
										<div class="number">0</div>
									</li>
									
									<li id="weed" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/cannabis_color.png">
										<div class="number">0</div>
									</li>
									
									<li id="cigarette" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/cigarette.png">
										<div class="number">0</div>
									</li>
									
									<li id="clipper" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/consommables/clipper.png">
										<div class="number">0</div>
									</li>
									
									<li id="hoverboardBlanc">
										<img src="<?php echo $configAssetsUrL; ?>/images/vehicules/whitehover.png">
									</li>

									<li id="porsche911">
										<img src="<?php echo $configAssetsUrL; ?>/images/vehicules/porsche911.png">
									</li>
									
									<li id="fiatPunto">
										<img src="<?php echo $configAssetsUrL; ?>/images/vehicules/fiatpunto.png">
									</li>
									
									<li id="volkswagenJetta">
										<img src="<?php echo $configAssetsUrL; ?>/images/vehicules/volkswagenjetta.png">
									</li>
									
									<li id="bmwI8">
										<img src="<?php echo $configAssetsUrL; ?>/images/vehicules/bmwi8.png">
									</li>
									
									<li id="audiA8">
										<img src="<?php echo $configAssetsUrL; ?>/images/vehicules/audia8.png">
									</li>
									
									<li id="audiA3">
										<img src="<?php echo $configAssetsUrL; ?>/images/vehicules/audia3.png">
									</li>
									
									<li id="batte">
										<img src="<?php echo $configAssetsUrL; ?>/images/armes/batte.png">
									</li>
									
									<li id="sabre">
										<img src="<?php echo $configAssetsUrL; ?>/images/armes/sabre.png">
									</li>
									
									<li id="ak47">
										<img src="<?php echo $configAssetsUrL; ?>/images/armes/ak47.png">
										<div class="number">0</div>
									</li>
									
									<li id="uzi">
										<img src="<?php echo $configAssetsUrL; ?>/images/armes/uzi.png">
										<div class="number">0</div>
									</li>
									
									<li id="cocktail" class="choose">
										<img src="<?php echo $configAssetsUrL; ?>/images/armes/cocktail.png">
										<div class="number">0</div>
									</li>
								</ul>
								<div style="margin-top: -20px"></div>
								<div class="clearfix"></div>
							</div>
							
							<div class="montantTrade">
								<input type="text" id="montantTradeInputMontant" placeholder="Montant"></input>
								<input type="submit" id="montantTradeButton" value="Valider"></input>
								<div class="clearfix"></div>
							</div>
							
							<div class="trade">
								<div class="myProposition">
									<div class="title"><img src=""> vous proposez...</div>
									<div class="valideTrade"><i class="fas fa-check"></i></div>
									
									<ul id="myPropositionTrade">
									</ul>
								</div>
								
								<div class="otherProposition">
									<div class="title"><img src=""> <span class="tradeUserUsername"></span>  propose...</div>
									<div class="valideTrade"><i class="fas fa-check"></i></div>
									
									<ul id="otherPropositionTrade">
									</ul>
								</div>
								<div class="clearfix"></div>
							</div>
							
							<button id="ValideTradeButton">Confirmer</button>
							<button id="CancelTradeButton">Annuler</button>
							
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="containerBox" id="casierUser" style="width: 240px;">
						<h1><i class="fas fa-lock"></i> Mon casier</h1>
						<div class="boxContent">
							<div class="casier">
								<input type="hidden" id="casierTypeItem"></input>
								
								<div class="action_casier">
									<input type="submit" id="casierDepotButton" value="Déposer"></input> 
									<input type="submit" id="casierRetirerButton" value="Retirer"></input>
								</div>
								
								<div class="deposer">
									<input type="text" id="casierMontantDepotItem" placeholder="Déposer"></input>
									<input type="submit" id="casierDepotItemButton" value="Valider"></input>
								</div>
								
								<div class="retirer">
									<input type="text" id="casierMontantRetirerItem" placeholder="Retirer"></input>
									<input type="submit" id="casierRetirerItemButton" value="Valider"></input>
								</div>
								
								<div class="clearfix"></div>
								
								<div class="row" id="weed">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/cannabis_color.png">
									<div class="number">0</div>
								</div>
								
								<div class="row" id="cocktail">
									<img src="<?php echo $configAssetsUrL; ?>/images/armes/cocktail.png">
									<div class="number">0</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="containerBox" id="appartInfo" style="width: 255px;">
						<h1><span id="appartTitle"></span> <div class="close"><i class="fas fa-times"></i></div></h1>
						<div class="boxContent">
							<div class="noloyer">
								<input type="text" id="loyerAppartId" placeholder="ID de l'appartement"></input>
								<input type="submit" value="Définir" id="loyerAppartButton"></input>
							</div>
							
							<div class="loyer">
								<h2>Appartement appartenant à :</h2>
								<span id="AppartLoyerUsername"></span>
								<br /><br />
								<h2>Loyer payé jusqu'au :</h2>
								<span id="AppartLoyerDate"></span>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="containerBox" id="profilUser" style="width: 500px;">
						<h1>Profil de <span id="profilUsernameTitle"></span> <div class="close"><i class="fas fa-times"></i></div></h1>
						<div class="boxContent">
							<div class="profil">
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="containerBox" id="wanted" style="width: 225px;">
						<h1>Civils recherchés <div class="close"><i class="fas fa-times"></i></div></h1>
						<div class="boxContent">
							<div class="wanted">
							</div>
							
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="containerBox" id="coiffures" style="width: 500px;">
						<h1><img src="<?php echo $configSwfUrL; ?>/habbo-imaging/badges/COIFFURE.gif" height="25" align="left"> Coiffures disponibles</h1>
						<div class="boxContent">
							<div class="coiffures">
								<h3>Choisissez une coiffure</h3>
								<ul id="listCoiffure">
								</ul>
							</div>
							
							<div class="colors animated fadeInDown">
								<h3>Choisissez une couleur</h3>
								<ul>
									<?php 
									$colorList = explode(";", "40;34;35;36;31;32;37;38;43;46;47;48;44;39;45;42;61;1394;1395;33;1396;1397;1398;49;1342;1343;1399;1344;1400;1401;59;1345;1348;54;1346;1347;55;1349;56;1350;1351;1352;1402;1403;1404;1405;1353;57;60;58;1354;1355;1356;53;52;1406;51;1316;50;41;1407");
									foreach($colorList as $colorListRow) {
										$colorListRow = trim($colorListRow);
										echo '<li class="colorItem'.$colorListRow.'" id="'.$colorListRow.'"></li> ';
									}
									?>
								</ul>
							</div>
							
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="containerBox" id="panier" style="width: 295px">
						<h1>Mon panier</h1>
						<div class="boxContent">
							<div class="list">
								<div class="item" id="eau">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/eau.png">
									<div class="number">0</div>
								</div>
								
								<div class="item" id="coca">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/coca.png">
									<div class="number">0</div>
								</div>
								
								<div class="item" id="fanta">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/fanta.png">
									<div class="number">0</div>
								</div>
								
								<div class="item" id="sucette">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/sucette.png">
									<div class="number">0</div>
								</div>
								
								<div class="item" id="pain">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/pain.png">
									<div class="number">0</div>
								</div>
								
								<div class="item" id="doliprane">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/doliprane.png">
									<div class="number">0</div>
								</div>
								
								<div class="item" id="savon">
									<img src="<?php echo $configAssetsUrL; ?>/images/consommables/savon.png">
									<div class="number">0</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="containerBox" id="atm" style="width: 300px;">
						<h1>Distributeur</h1>
						<div class="boxContent">
							<div class="atm">
								<div id="AtmAlertCode" class="info"></div>
								
								<div class="code">
									<input type="password" id="atm_code1" maxlength="1" placeholder="•">
									<input type="password" id="atm_code2" maxlength="1" placeholder="•">
									<input type="password" id="atm_code3" maxlength="1" placeholder="•">
									<input type="password" id="atm_code4" maxlength="1" placeholder="•">
								</div>
								
								<div class="home">
									<div class="button" id="deposer">
										<i class="fas fa-dollar-sign"></i>
										<div class="desc">Déposer des crédits dans mon compte</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="button" id="retirer">
										<i class="fas fa-money-bill"></i>
										<div class="desc">Retirer des crédits</div>
										<div class="clearfix"></div>
									</div>
								</div>
								
								<div class="deposer">
									<div class="back"><img src="<?php echo $configAssetsUrL; ?>/images/back.png"> Retour à l'accueil</div>
									<div class="form">
										<input type="text" id="deposerMontant" placeholder="Montant" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57"></input>
										<input type="submit" id="deposerButton" value="Déposer"></input>
									</div>
								</div>
								
								<div class="retirer">
									<div class="back"><img src="<?php echo $configAssetsUrL; ?>/images/back.png"> Retour à l'accueil</div>
									<div class="form">
										<input type="text" id="retirerMontant" placeholder="Montant" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57"></input>
										<input type="submit" id="retirerButton" value="Retirer"></input>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="containerBox" id="transaction" style="width: 300px;">
						<h1>Transaction</h1>
						<div class="boxContent">
							<div class="transaction">
								<div class="transaction_choice">
									<h2></h2>
									<button id="accepter">Accepter</button> 
									<button id="refuser">Refuser</button>
								</div>
								
								<div class="transaction_moyen">
									<h2>Choisissez votre moyen de paiement</h2>
									<img src="<?php echo $configAssetsUrL; ?>/images/cb.png" id="moyen_cb">
									<img src="<?php echo $configAssetsUrL; ?>/images/credits_moyen.png" id="moyen_credits">
								</div>
								
								<div class="code">
									<div id="alertCode" class="info"></div>
									
									<input type="password" id="transaction_code1" maxlength="1" placeholder="•">
									<input type="password" id="transaction_code2" maxlength="1" placeholder="•">
									<input type="password" id="transaction_code3" maxlength="1" placeholder="•">
									<input type="password" id="transaction_code4" maxlength="1" placeholder="•">
								</div>
							</div>
						</div>
					</div>
					
					<div class="containerBox" id="gang" style="width: 260px;">
						<h1><img src="<?php echo $configAssetsUrL; ?>/images/gang_icon.png"> <span id="GangTitle">Créer un gang</span>  <div class="close"><i class="fas fa-times"></i></div> <button id="QuitGang">Quitter le gang</button></h1>
						<div class="boxContent">
							<div class="gang">
								<div class="creategang">
									<input type="text" id="nomGang" placeholder="Nom du gang"></input>
									<input type="submit" id="createGang" value="Créer le gang"></input>
								</div>
								
								<div class="mygang">
								</div>
							</div>
						</div>
					</div>
					
					<div class="containerBox" id="about" style="width: 450px;">
						<h1>Informations <?php echo $configName; ?> <div class="close"><i class="fas fa-times"></i></div></h1>
						<div class="boxContent">
							<div class="about_content">
								<img src="<?php echo $configAssetsUrL; ?>/images/logo_info.png">
								<h3>BobbaRP Emulator</h3><br/>
								<p>
									Version: <span id="about_version"></span><br/>
									Démarré depuis: <span id="about_demarre"></span><br/>
									Civils en ligne: <span id="about_onlines"></span><br/>
									Appartements actifs: <span id="about_apparts"></span><br/>
								</p>
											
								<h3>Copyrights</h3><br/>
								<p>
									BobbaRP Emulator: <span>OvB</span><br/>
									Plus Emulator: <span>Sledmore, Ash</span><br/>
								</p>
							</div>
						</div>
					</div>
					<!-- END BOX -->
					
					<div id="flash-container"> 
						<div id="content"> 
							<div class="logo"><img src="<?php echo $configAssetsUrL; ?>/images/logo.png"></div>
							<a href="//www.adobe.com/go/getflashplayer">
								<div class="flashImage"><img src="<?php echo $configAssetsUrL; ?>/images/frank.gif"></div>
								<h2>Autoriser Flash Player</h2>
							</a>
						</div> 
					
						<script type="text/javascript"> 
						$('content').show();
						</script> 
						<noscript> 
						<div style="width: 400px; margin: 20px auto 0 auto; text-align: center"> 
						<p>If you are not automatically redirected, please <a href="/client/nojs">click here</a></p> 
						</div> 
						</noscript> 
					</div> 
				</div>
			</div>
		</div>
		
		<script type="text/javascript"> 
			RightClick.init("flash-wrapper", "flash-container");
		</script> 

		<script type="text/javascript"> 
			FlashExternalInterface.loginLogEnabled = true;
			FlashExternalInterface.logLoginStep("web.view.start");
			 
			if (top == self) {
			FlashHabboClient.cacheCheck();
			}
			var flashvars = {
			"client.allow.cross.domain" : "1", 
			"client.notify.cross.domain" : "0", 
			"connection.info.host" : "91.236.239.102", 
			"connection.info.port" : "3000", 
			"site.url" : "<?php echo $configBaseUrL; ?>", 
			"url.prefix" : "<?php echo $configBaseUrL; ?>", 
			"client.reload.url" : "<?php echo $configBaseUrL; ?>/client", 
			"client.fatal.error.url" : "<?php echo $configBaseUrL; ?>/home?error=client", 
			"client.connection.failed.url" : "<?php echo $configBaseUrL; ?>/home?error=client", 
			"external.variables.txt" : "<?php echo $configSwfUrL; ?>/gamedata/external_variables.txt",
			"external.texts.txt" : "<?php echo $configSwfUrL; ?>/gamedata/external_flash_texts.txt",
			"external.override.variables.txt" : "<?php echo $configSwfUrL; ?>/gamedata/override/external_flash_override_texts.txt",
			"productdata.load.url" : "<?php echo $configSwfUrL; ?>/gamedata/productdata.txt",
			"furnidata.load.url" : "<?php echo $configSwfUrL; ?>/gamedata/furnidata9.xml",
			"hotelview.banner.url" : "",
			"use.sso.ticket" : "1",
			"sso.ticket" : "<?php if(isset($ssoTicket)) { echo $ssoTicket; } ?>", 
			"processlog.enabled" : "0", 
			"account_id" : "<?php echo $userInfo[0]["id"]; ?>", 
			"client.starting" : "test d\351marre.", 
			"flash.client.url" : "<?php echo $configSwfUrL; ?>/gordon/PRODUCTION-201602082203-712976078/", 
			"user.hash" : "", 
			"has.identity" : "0", 
			"flash.client.origin" : "popup" 
			 };
			var params = {
				"base" : "<?php echo $configSwfUrL; ?>/gordon/PRODUCTION-201602082203-712976078/",
				"allowScriptAccess" : "always",
				"menu" : "false"                
			};
		 
				if (!(HabbletLoader.needsFlashKbWorkaround())) {
					params["wmode"] = "opaque";
				}
		 
			FlashExternalInterface.signoutUrl = "<?php echo $configBaseUrL; ?>/account/logout";
		 
			var clientUrl = "<?php echo $configSwfUrL; ?>/gordon/PRODUCTION-201602082203-712976078/Habbowiz.swf";
			swfobject.embedSWF(clientUrl, "flash-container", "100%", "100%", "10.0.0", "<?php echo $configBaseUrL; ?>/web-gallery/flashclient/flash/expressInstall.swf", flashvars, params);
		 
			window.onbeforeunload = unloading;
			function unloading() {
				var clientObject;
				if (navigator.appName.indexOf("Microsoft") != -1) {
					clientObject = window["flash-container"];
				} else {
					clientObject = document["flash-container"];
				}
				try {
					clientObject.unloading();
				} catch (e) {}
			}
		</script> 
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo $configAssetsUrL; ?>/js/shortcuts.js"></script>
		<script src="<?php echo $configAssetsUrL; ?>/js/client.js"></script>
		<script src="https://apis.google.com/js/client.js?onload=onClientLoad" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo $configAssetsUrL; ?>/js/websockets/app.user.js?<?= time(); ?>"></script>
		<script id="app" type="text/javascript" src="<?php echo $configAssetsUrL; ?>/js/websockets/app.main.js?<?= time(); ?>"></script>
		
		<script>
			app.initialize(habboId, habboName, habboFigure);
			var number = 1 + Math.floor(Math.random() * 2);
			if(number == 1)
			{
				$("#loading .banner").attr('src', '<?php echo $configAssetsUrL; ?>/images/client_loading1.png');
			}
			else
			{
				$("#loading .banner").attr('src', '<?php echo $configAssetsUrL; ?>/images/client_loading2.png');
			}
		</script>
	</body> 
</html>
