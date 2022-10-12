<?php
session_start();
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
if($_SESSION["igebruikersrol"] == "user"){
	header("location:geentoegang.php");
}
if((isset($_GET["actie"]) && $_GET["actie"] == "deactiveer") && (isset($_GET["klantdeactiveren"]))){
	$mysqli= new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding:".$mysqli->error);
	}
	$sql= " UPDATE tblklanten SET actief = 0
			WHERE klantid = ?";
	if($stmt=$mysqli->prepare($sql)){
		$stmt->bind_param("i", $klantid);
		$klantid = $_GET["klantdeactiveren"];
		if(!$stmt->execute()){
			echo "het uitvoeren van de query is mislukt:";
		}
		header("location:klanten.php");
		$stmt->close();
	}
	else{
		echo "Er zit een fout in de query";
	}
}
?>
<?php
if((isset($_GET["actie"]) && $_GET["actie"] == "aanpassen") && (isset($_GET["klantaanpassen"]))){
	$mysqli= new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding:".$mysqli->error);
	}
	else{
	$sql= " SELECT naam, voornaam, email, adres, g.Gemeente, g.Postcode, gebruikersrol from tblklanten k, tblgemeente g
			WHERE g.PostcodeId = k.PostcodeId and klantid = ?";
	if($stmt=$mysqli->prepare($sql)){
		$stmt->bind_param("i",$klantid);
		$klantid = $_GET["klantaanpassen"];
		if(!$stmt->execute()){
			echo "het uitvoeren van de query is mislukt:";
		}
		else {
		$stmt->bind_result($naam,$voornaam,$email,$adres,$gemeente,$postcode,$gebruikersrol);
		while($stmt->fetch()){
			$_SESSION["klantid"] = $klantid;
			$_SESSION["naam"] = $naam;
			$_SESSION["voornaam"] = $voornaam;
			$_SESSION["email"] = $email;
			$_SESSION["adres"] = $adres;
			$_SESSION["postcode"] = $postcode;
			$_SESSION["gemeente"] = $gemeente;
			$_SESSION["gebruikersrol"] = $gebruikersrol;
		}
			header("location:klantaanpassen.php");
		}
		$stmt->close();
	}
	else{
		echo "Er zit een fout in de query";
	}
}
}
?>
<head>
	<meta charset="UTF-8">
	<title>Zoekresultaat - Camping Le Passage</title>
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
				<li>
					<a href="about.php"><span>O</span>ver</a>
				</li>
                <li class="selected">
					<a href="klanten.php"><span>K</span>lanten</a>
				</li>
                <li>
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li>
                	<a href="reserveringen.php"><span>R</span>eserveringen</a>
                </li>
                <li>
                	<a href="mijnprofiel.php"><span>M</span>ijn profiel</a>
                </li>
                <li <?php if($_SESSION["iklantid"] == ""){ echo "hidden";} ?>>
                	<a href="afmelden.php"><span>A</span>fmelden</a>
                </li>
			</ul>
		</div>
	</div>
	<div class="body">
		<div class="klanten">
			<div>
				<div>
					<div class="klanten">
						<h2>Zoekresultaat</h2>
							<div>
                            <a href="klantzoeken.php"><button type="button">Nieuwe zoekactie</button></a>
                        <table width="780px">
                        <tr>
                            <th hidden>Klant Nr.</th>
                            <th>Voornaam</th>
                            <th>Naam</th>
                            <th>E-mail</th>
                         </tr>
							<?php
							$mysqli= new MySQLi("localhost","root","","dietergip");
							if(mysqli_connect_errno()){
								trigger_error("Fout bij verbinding:".$mysqli->error);
							}
							else{
								$sql=$_SESSION["SQL"];
								if($stmt = $mysqli->prepare($sql))
								{
								if(!$stmt->execute())
								{
									echo "Het uitvoeren van de query is mislukt:".$stmt->error."in query:".$sql;
								}
								else{
									$stmt->bind_result($klantid ,$voornaam, $naam, $email, $actief);
									while($stmt->fetch()){
										$klantnr=$klantid;
										echo "<tr>
												<td hidden>".$klantid."</td>
												<td>".$voornaam."</td>
												<td>".$naam."</td>
												<td>".$email."</td>
												<td><a href =".$_SERVER["PHP_SELF"]."?actie=aanpassen&klantaanpassen=".$klantnr."'><img src='./images/edit.png' alt='Klant aanpassen'></a></td>";
												if($actief == 1){
												echo "<td><a href =".$_SERVER["PHP_SELF"]."?actie=deactiveer&klantdeactiveren=".$klantnr."><img src='./images/delete.png' alt='Klant verwijderen'></a</td>";
										}
										else{
											echo "<td>Gedeactiveerd</td>";
										}
											 echo "</tr>";
									}
								}
								$stmt->close();
							}
							else{
								echo "Er zit een fout in de query:".$mysqli->error;
							}
							}
							?>
                            </table>
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