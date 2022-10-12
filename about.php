<?php
session_start();
if(!isset($_SESSION["nietingelogd"])){
	$_SESSION["nietingelogd"] = false;
}
if($_SESSION["nietingelogd"] == true){
	$_SESSION["nietingelogd"] = false;
}
if(!isset($_SESSION["doorsturen"])){
	$_SESSION["doorsturen"] = false;
}
if($_SESSION["doorsturen"] == true){
	$_SESSION["doorsturen"] = false;
}
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
?>
<head>
	<meta charset="UTF-8">
	<title>Over - Camping Le Passage</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="icon" href="favicon.png">
</head>
<body>
	<div class="header">
		<div>
			<a href="index.php" id="logo"><img src="images/logo.png" alt="logo" hidden></a>
			<ul>
				<li>
					<a href="index.php"><span>H</span>ome</a>
				</li>
				<li class="selected">
					<a href="about.php"><span>O</span>ver</a>
				</li>
                <li <?php if($_SESSION["igebruikersrol"] == "user"){ echo "hidden";} ?>>
					<a href="klanten.php"><span>K</span>lanten</a>
				</li>
                <li>
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li <?php if($_SESSION["igebruikersrol"] == "user"){ echo "hidden";} ?>>
                	<a href="reserveringen.php"><span>R</span>eserveringen</a>
                </li>
                <li <?php if($_SESSION["iklantid"] == ""){ echo "hidden";} ?>>
                	<a href="mijnprofiel.php"><span>M</span>ijn profiel</a>
                </li>
                <li <?php if($_SESSION["iklantid"] != ""){ echo "hidden";} ?>>
                	<a href="login.php"><span>A</span>anmelden</a>
                </li>
                <li <?php if($_SESSION["iklantid"] == ""){ echo "hidden";} ?>>
                	<a href="afmelden.php"><span>A</span>fmelden</a>
                </li>
			</ul>
		</div>
	</div>
	<div class="body">
		<div>
			<div>
				<div>
					<div class="about">
						<div>
							<h2>Over camping Le Passage</h2>
							<div>
								<h3><span>W</span>ie zijn wij?</h3>
								<p>
Joel Devuyst, Isabelle Declercq en zoon Cédric<br>
Als voormalige "frituuruitbaters" aan de Belgische kust wilden wij wel eens een andere uitdaging. Door er veel op uit te trekken met onze moterhome vonden wij toevallig deze verlaten camping in een prachtige omgeving.
Daar kwam veel hard werk bij kijken zoals: <br>
- Het braakliggend terrein campeervriendelijk aanleggen<br>
- Aanvragen indienen & vergunningen bemachtigen<br>
- Alles wettelijk in orde stellen<br>
- ... <br>
								</p>
								<h3><span>D</span>e voorzieningen</h3>
								<p>
Er is een nieuw water,- en elektriciteitsnet aangelegd met aparte meters per caravan. Ook een volledig nieuw afvoernet hebben wij aanlegt die aangesloten is op ons eigen zuiveringsstation. <br>
In 2014 is de volledig nieuwe sanitaire voorziening geopend (6 instapdouches met watervalkranen, 8 wastafels, 10 toiletten, een afwastafel. Wasmachine binnenkort).
Vandaag hebben we reeds 50 plaatsen waarvan 11 trek- of seizoen plaatsen en 39 vaste jaarplaatsen. Er zijn zowel zon- als schaduwrijke plaatsen. 
								</p>
                                <h3><span>A</span>lgemene info</h3>
								<p>
We hebben duidelijk gekozen voor een familiecamping en er is helaas geen plaats voor groepen.
Momenteel werken wij aan een uitbreiding die inhoud dat er in de toekomst een 60tal stacaravans zullen komen.
Voor de kleinste onder ons is er een speel- en zonneweide en er kan naar hartenlust een frisse duik genomen worden in de rivier (l'Ourthe) die langs de camping stroomt
Doe je het liever wat rustiger, dan neem je best een vislijn en wat goede lectuur voor aan de oevers te relaxen.
								</p>
                                <h3><span>A</span>lle belangrijke dingen dichtbij</h3>
								<p>
De warme bakker is juist naast de camping gelegen en een supermarkt, krantenwinkel, apotheek, dokter, politiepost en benzinestation
in de directe omgeving. (5 min.) Zin in een dagje shoppen, dan is Bastogne en Luxemburg vlakbij. <br>
								</p>
                                <h3><span>V</span>rije tijd en toerisme</h3>
                                <p>
                                In de wintermaanden is de ski- langlaufpiste van Samrée en Baraque Fraiture vlakbij.
De omgeving is een paradijs voor wandelaars, fietsers (VTT) en ook de actievelingen komen aan hun trekken met o.a. kajakken, paardrijden, rotsklimmen, speleologie en nog veel meer.
U vindt in de directe omgeving toeristische trekpleisters zoals: <br>
- La Roche-en-Ardenne,<br>- Houffalize,<br>- Durbuy<br>- Barvaux<br>- Hotton.<br>
Heeft u trek in een frisdrankje, ijsje , frietje of een menuutje, 
we hebben het allemaal ter plaatse in de frituur en in de kantine van de camping.
                                </p>
                                <h3><span>O</span>ns tarief</h3>
                                <p>
                                </p>
                                <h3><span>C</span>ontactdetails</h3>
                                <p>
                                Als u verdere vragen heeft kan u ons altijd contacteren:<br>
								- op ons adres: Rue Pont De Marcourt 1, 6987 Rendeux<br>
                                - via Facebook messenger of facebook zelf: Camping Le Passage<br>
                                - via e-mail: info@campinglepassage.be<br>
                                - via de telefoon: +32476 59 25 76
                                </p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">
		<div>
			<p>
				Camping Le Passage &#169; 2016 <?php if(2016 < date("Y")){ echo "- ".date("Y");} ?> | Alle Rechten Gereserveerd
			</p>
		</div>
	</div>
</body>