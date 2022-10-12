<?php
session_start();
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
?>
<head>
	<meta charset="UTF-8">
	<title>Geen Toegang - Camping Le Passage</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="icon" href="favicon.png">
</head>
<body onload="setInterval(doorsturen, 10000);">
	<div class="header">
		<div>
			<a href="index.php" id="logo"><img src="images/logo.png" alt="logo" hidden></a>
			<ul>
				<li>
					<a href="index.php"><span>H</span>ome</a>
				</li>
				<li>
					<a href="about.php"><span>O</span>ver</a>
				</li>
                <li>
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
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
		<div class="geentoegang">
			<div>
				<div>
					<div class="geentoegang">
						<h2>ERROR 403: GEEN TOEGANG!</h2>
							<div>
                             <img src="./images/geentoegang.png" alt="Geen Toegang!"><p>Het lijkt erop dat u naar een pagina probeerde te gaan waar u geen toegang tot heeft!<br>
                             Dit omdat u waarschijnlijk niet bent aangemeld of niet beschikt over de juiste gebruikersrol.<br>
							Als u niet bent ingelogd wordt u automatisch naar de inlogpagina gestuurd in 10 seconden.
                            Indien u wel ingelogd bent wordt u doorgestuurd naar de homepagina.</p>
                            <?php
							if($_SESSION["iklantid"] == ""){
                                echo "<script>
								function doorsturen(){
									location.href = './login.php';
								}
								</script>";
							}
							if($_SESSION["iklantid"] != "" && $_SESSION["igebruikersrol"] == "user"){
                                echo "<script>
								function doorsturen(){
									location.href = './index.php';
								}
								</script>";
							}
							?>
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