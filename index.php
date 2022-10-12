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
	<title>Index - Camping Le Passage</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="icon" href="favicon.png">
</head>
<body>
	<div class="header">
		<div>
			<a href="index.php" id="logo"><img src="./images/logo.png" alt="logo" hidden></a>
			<ul>
				<li class="selected">
					<a href="index.php"><span>H</span>ome</a>
				</li>
				<li>
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
			<div class="featured">
				<img src="./images/camping.jpg" alt="">
			  <div>
					<div>
						<h3 style="padding-left:70px"><span>R</span>eserveren?</h3>
						<p>
							Als je bij ons plaatsen wilt reserveren moet je jezelf registreren als klant bij ons.
                            Dat kan je doen door op de knop hieronder te klikken.
						</p>
						<a href="register.php">Registreer hier!</a>
					</div>
				</div>
			</div>
			<div>
				<div>
					<div>
						<div class="section">
							<h2>Camping Le Passage</h2>
							<p>
								Camping Le Passage staat voor een propere, gezellige, nieuwe (2010) en verzorgde familiecamping aan de oevers van de rivier de Ourthe. Voorzien met een nieuw water- en elektriciteitsnet waarbij elke caravan zijn aparte meters heeft. Wij hebben ook een volledig nieuw afvoernet aangelegd die aangesloten is op ons eigen zuiveringsstation.
							</p>
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