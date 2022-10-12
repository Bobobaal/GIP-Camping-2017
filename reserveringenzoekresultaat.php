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
		header("location:reservaties.php");
		$stmt->close();
	}
	else{
		echo "Er zit een fout in de query";
	}
}
}
?>
<?php
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
	<title>Resultaat zoekopdracht - Camping Le Passage</title>
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
                <li>
					<a href="klanten.php"><span>K</span>lanten</a>
				</li>
                <li>
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li class="selected">
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
		<div class="reserveringen">
			<div>
				<div>
					<div class="reserveringen">
						<h2>Resultaat zoekopdracht</h2>
							<div>
                            <a href="reserveringzoeken.php"><button type="button">Reservering zoeken</button></a>
                        <table width="780px">
                        <tr>
                            <th hidden>Reservering Nr.</th>
                            <th>Plaats Nr.</th>
                            <th>Voornaam</th>
                            <th>Naam</th>
                            <th>Begindatum</th>
                            <th>Einddatum</th>
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
									$stmt->bind_result($reserveringsid ,$plaatsid, $voornaam, $naam, $begindatum, $einddatum, $annuleerd);
									while($stmt->fetch()){
										$reserveringsnr=$reserveringsid;
										echo "<tr>
												<td hidden>".$reserveringsid."</td>
												<td>".$plaatsid."</td>
												<td>".$voornaam."</td>
												<td>".$naam."</td>
												<td>".$begindatum."</td>
												<td>".$einddatum."</td>
												<td><a href =".$_SERVER["PHP_SELF"]."?actie=aanpassen&reserveringaanpassen=".$reserveringsnr."><img src='./images/edit.png' alt='Klant aanpassen'></a></td>";
												if($annuleerd == 0){
												echo "<td><a href =".$_SERVER["PHP_SELF"]."?actie=annuleer&reserveringannuleren=".$reserveringsnr."><img src='./images/delete.png' alt='Reservering annuleren'></a></td>
											  </tr>";
												}
												else{
													echo "<td>Geannuleerd</td>
											  </tr>";
												}
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