<?php
session_start();
$aantalreserveringen = 0;
$tabelgemaakt = false;
if(!isset($_SESSION["resvoltooid"])){
	$_SESSION["resvoltooid"] = false;
}
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
if((isset($_GET["actie"]) && $_GET["actie"] == "annuleer") && (isset($_GET["reserveringannuleren"]))){
	$mysqli= new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding:".$mysqli->error);
	}
	else{
	$sql= " UPDATE tblreserveringen SET annuleerd = 1
			WHERE reserveringsid = ?";
	if($stmt=$mysqli->prepare($sql)){
		$stmt->bind_param("i", $reserveringsid);
		$reserveringsid = $_GET["reserveringannuleren"];
		if(!$stmt->execute()){
			echo "het uitvoeren van de query is mislukt:";
		}
		header("location:mijnprofiel.php");
		$stmt->close();
	}
	else{
		echo "Er zit een fout in de query";
	}
}
}
if((isset($_GET["actie"]) && $_GET["actie"] == "aanpassen") && (isset($_GET["reserveringaanpassen"]))){
	$mysqli= new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding:".$mysqli->error);
	}
	else{
	$sql= " SELECT volwassenen, kinderen, begindatum, einddatum, plaatsid from tblreserveringen r1, tblreserveringenperplaats r2
			WHERE r1.reserveringsid = ? AND r1.reserveringsid = r2.reserveringsid";
	if($stmt=$mysqli->prepare($sql)){
		$stmt->bind_param("i",$reserveringsid);
		$reserveringsid = $_GET["reserveringaanpassen"];
		if(!$stmt->execute()){
			echo "het uitvoeren van de query is mislukt:";
		}
		else {
		$stmt->bind_result($volwassenen,$kinderen,$begindatum,$einddatum,$plaatsid);
		while($stmt->fetch()){
			$_SESSION["reserveringsid"] = $reserveringsid;
			$_SESSION["volwassenen"] = $volwassenen;
			$_SESSION["kinderen"] = $kinderen;
			$_SESSION["begindatum"] = $begindatum;
			$_SESSION["einddatum"] = $einddatum;
			$_SESSION["plaatsid"] = $plaatsid;
			$_SESSION["bronresaanpassing"] = "Profiel";
		}
			header("location:reserveringaanpassen.php");
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
                <li class="selected">
                	<a href="mijnprofiel.php"><span>M</span>ijn profiel</a>
                </li>
                <li>
                	<a href="afmelden.php"><span>A</span>fmelden</a>
                </li>
			</ul>
		</div>
	</div>
	<div class="body">
		<div class="profiel">
			<div>
				<div>
					<div class="profiel">
						<div>
							<h2>Overzicht profiel</h2>
							<div>
							<h3>Mijn contact details</h3>
							<hr>
							<?php
								$mysqli = new MySQLi("localhost","root","","dietergip");
								if(mysqli_connect_errno()){
									trigger_error("Fout bij verbinding: ".$mysqli->error);
								}
								else{
									$sql = "select voornaam, naam, email, adres, Postcode, gemeente from tblklanten k, tblgemeente g where g.PostcodeId = k.PostcodeId and k.klantid = ?";
									if($stmt = $mysqli->prepare($sql)){
										$stmt->bind_param("i",$klantid);
										$klantid = $_SESSION["iklantid"];
										if(!$stmt->execute()){
											echo "het uitvoeren van de query is mislukt: ".$mysqli->error;
										}
										else{
											$stmt->bind_result($voornaam, $naam, $email, $adres, $postcode, $gemeente);
											while($stmt->fetch()){
												echo "<p><span>V</span>oornaam: ".$voornaam."</p>";
												echo "<p><span>N</span>aam: ".$naam."</p>";
												echo "<p><span>E</span>-mail: ".$email."</p>";
												echo "<p><span>A</span>dres: ".$adres."</p>";
												echo "<p><span>P</span>ostcode: ".$postcode."</p>";
												echo "<p><span>G</span>emeente: ".$gemeente."</p>";
											}
										}
										$stmt->close();
									}
									else{
										echo "Er zit een fout in de query: ".$mysqli->error;
									}
								}
							?>
							<a href="profielaanpassen.php"><button type="button">Profiel aanpassen</button></a>
							<h3>Mijn reserveringen</h3>
							<hr>
							<?php
								if($_SESSION["resvoltooid"] == true){
									echo "<p>Het plaatsen van uw reservering is goed verlopen.</p>";
									$_SESSION["resvoltooid"] = false;
								}
							$mysqli= new MySQLi("localhost","root","","dietergip");
							if(mysqli_connect_errno()){
								trigger_error("Fout bij verbinding:".$mysqli->error);
							}
							else{
								$sql="select r1.reserveringsid, plaatsid, voornaam, naam, volwassenen, kinderen, begindatum, einddatum from tblklanten k, tblreserveringen r1, tblreserveringenperplaats r2 where k.klantid = r1.klantid AND r1.reserveringsid = r2.reserveringsid AND annuleerd = 0 AND r1.klantid = ? AND einddatum > curdate()";
								if($stmt = $mysqli->prepare($sql))
								{
									$stmt->bind_param("i",$klantid);
									$klantid = $_SESSION["iklantid"];
								if(!$stmt->execute())
								{
									echo "Het uitvoeren van de query is mislukt:".$stmt->error."in query:".$sql;
								}
								else{
									$stmt->bind_result($reserveringsid ,$plaatsid, $voornaam, $naam, $volwassenen, $kinderen, $begindatum, $einddatum);
									while($stmt->fetch()){
										$aantalreserveringen += 1;
										if($aantalreserveringen > 0 ){
											if($tabelgemaakt == false){
												$tabelgemaakt = true;
												echo "<table width='780px'>
                        						<tr>
                            						<th hidden>Reservatie Nr.</th>
                            						<th>Plaats Nr.</th>
                            						<th>Voornaam</th>
                            						<th>Naam</th>
													<th>Volwassenen</th>
                            						<th>Kinderen</th>
                            						<th>Begindatum</th>
                            						<th>Einddatum</th>
                         						</tr>";
											}
											$reserveringsnr=$reserveringsid;
											echo "<tr>
													<td hidden>".$reserveringsid."</td>
													<td>".$plaatsid."</td>
													<td>".$voornaam."</td>
													<td>".$naam."</td>
													<td>".$volwassenen."</td>
													<td>".$kinderen."</td>
													<td>".$begindatum."</td>
													<td>".$einddatum."</td>
													<td><a href =".$_SERVER["PHP_SELF"]."?actie=aanpassen&reserveringaanpassen=".$reserveringsnr."><img src='./images/edit.png' alt='Reservering aanpassen'></a></td>
													<td><a href =".$_SERVER["PHP_SELF"]."?actie=annuleer&reserveringannuleren=".$reserveringsnr."><img src='./images/delete.png' alt='Reservering annuleren'></a</td>
											  		</tr>";
										}
									}
									if($aantalreserveringen == 0){
											echo "<p>U heeft geen open reserveringen staan</p>";
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
	</div>
	<div class="footer">
		<div>
			<p>
				Camping Le Passage &#169; 2016 <?php if(2016 < date("Y")){ echo "- ".date("Y");} ?> | Alle Rechten Gereserveerd
			</p>
		</div>
	</div>
</body>