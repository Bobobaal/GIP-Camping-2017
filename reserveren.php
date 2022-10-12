<?php
$ingestoken=False;
session_start();
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
if($_SESSION["iklantid"] == ""){
	header("location:geentoegang.php");
}
$datumscorrect = true;
$personenopgegeven = false;
$inklantid = 0;
$opgegevenklant = 0;
$algereserveerd = false;
if(isset($_POST["reserveren"])){
	if($_SESSION["igebruikersrol"] == "admin"){
		$inklantid = $_POST["klanten"];
		$opgegevenklant = $_POST["klanten"];
	}
	if($_SESSION["igebruikersrol"] != "admin"){
		$inklantid = $_SESSION["iklantid"];
	}
	if($_SESSION["igebruikersrol"] == "admin" && $_POST["klanten"] == 0){
		$inklantid = $_SESSION["iklantid"];
	}
}
if(isset($_POST["reserveren"])){
	if($_POST["volwassenen"] > 0){
		$personenopgegeven = true;
	}
	if($_POST["kinderen"] > 0){
		$personenopgegeven = true;
	}
}
									if(isset($_POST["reserveren"])){if($_POST["begindatum"] > $_POST["einddatum"]){$datumscorrect = false;}
										$mysqli = new MySQLi("localhost","root","","dietergip");
										if(mysqli_connect_errno()){
											trigger_error("Fout bij verbinding: ".$mysqli->error);
										}
										else{
											$sql = "SELECT begindatum, einddatum from tblreserveringen r1, tblreserveringenperplaats r2 where r1.reserveringsid = r2.reserveringsid and plaatsid = ? and annuleerd = 0";
											if($stmt = $mysqli->prepare($sql)){
												$stmt->bind_param("i", $plaatsid);
												$plaatsid = $_SESSION["plaatsid"];
												if(!$stmt->execute()){
													echo "Het uitvoeren van de query is mislukt: ".$stmt->error." in query: ".$sql;
												}
												else{
													$stmt->bind_result($sbegindatum, $seinddatum);
													while($stmt->fetch()){
														if($_POST["begindatum"] >= $sbegindatum and $_POST["begindatum"] <= $seinddatum){
															$algereserveerd = true;
														}
														if($_POST["einddatum"] >= $sbegindatum and $_POST["einddatum"] <= $seinddatum){
															$algereserveerd = true;
														}
														if($_POST["begindatum"] <= $sbegindatum and $_POST["einddatum"] >= $seinddatum){
															$algereserveerd = true;
														}
													}
												}
												$stmt->close();
											}
											else{
												echo "er zit een fout in de query: ".$msqli->error;
											}
										}
									}
if(isset($_POST["reserveren"]) && isset($_POST["volwassenen"]) && isset($_POST["kinderen"]) && isset($_POST["begindatum"]) && isset($_POST["einddatum"]) && $_POST["volwassenen"] != "" && $_POST["kinderen"] != "" && $_POST["begindatum"] != "" && $_POST["einddatum"] != "" && $datumscorrect == true && $algereserveerd == false && $personenopgegeven == true){
$mysqli= new MySQLi ("localhost","root","","dietergip");
 if(mysqli_connect_errno()) {trigger_error('Fout bij verbinding: '.$mysqli->error); }
else{
$sql = "INSERT INTO tblreserveringenperplaats (plaatsid, volwassenen, kinderen) VALUES (?,?,?)";
if($stmt=$mysqli->prepare($sql)){
	$stmt->bind_param("iii", $plaatsid, $volwassenen, $kinderen);
	$plaatsid = $_SESSION["plaatsid"];
	$volwassenen = $mysqli->real_escape_string($_POST["volwassenen"]);
	$kinderen = $mysqli->real_escape_string($_POST["kinderen"]);
	if(!$stmt->execute()){
	echo "Het uitvoeren van de query is mislukt: ".$stmt->error." in query: ".$sql;
	}
	else{
	}
	$stmt->close();
}
$sql = "SELECT reserveringsid FROM tblreserveringenperplaats";
if($stmt=$mysqli->prepare($sql)){
	if(!$stmt->execute()){
		echo "Het uitvoeren van de query is mislukt: ".$stmt->error." in query: ".$sql;
	}
	else{
	$stmt->bind_result($reserveringsid);
	while($stmt->fetch()){
		$onthoudID = $reserveringsid;
	}
	}
	$stmt->close();
}
$sql = "INSERT INTO tblreserveringen (klantid, reserveringsid, begindatum, einddatum) VALUES(?,?,?,?)";
if($stmt=$mysqli->prepare($sql)){
	$stmt->bind_param("iiss", $klantid, $reserveringsid, $begindatum, $einddatum);
	$klantid = $inklantid;
	$reserveringsid = $onthoudID;
	$begindatum = $mysqli->real_escape_string($_POST["begindatum"]);
	$einddatum = $mysqli->real_escape_string($_POST["einddatum"]);
	if(!$stmt->execute()){
	echo "Het uitvoeren van de query is mislukt: ".$stmt->error." in query: ".$sql;
	}
	else{
	}
	$stmt->close();
}
	if($inklantid == $_SESSION["iklantid"]){
		header("location:mijnprofiel.php");
	}
	else{
		header("location:reserveringen.php");
	}
	$_SESSION["resvoltooid"] = true;
}
}
?>
<head>
	<meta charset="UTF-8">
	<title>Reserveren - Camping Le Passage</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="icon" href="favicon.png">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
   $( "#begindatum" ).datepicker({
  showAnim: "slideDown",
  dateFormat: "yy-mm-dd",
  minDate: 0
});
  } );
    $( function() {
   $( "#einddatum" ).datepicker({
  showAnim: "slideDown",
  dateFormat: "yy-mm-dd",
  minDate: +1
});
  } );
  </script>
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
                <li class="selected">
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li <?php if($_SESSION["igebruikersrol"] == "user"){ echo "hidden";} ?>>
                	<a href="reserveringen.php"><span>R</span>eserveringen</a>
                </li>
                <li>
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
		<div class="reserveren">
			<div>
				<div>
					<div class="reserveren">
						<h2>Reservatie plaats nr. <?php echo $_SESSION["plaatsid"]; ?></h2>
						<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
							<div>
								<table width="780px">
                                <?php if(isset($_POST["reserveren"])){ if(!isset($_POST["volwassenen"]) or !isset($_POST["kinderen"]) or !isset($_POST["einddatum"]) or !isset($_POST["einddatum"]) or $_POST["volwassenen"] == "" or $_POST["kinderen"] == "" or $_POST["begindatum"] == "" or $_POST["einddatum"] == ""){ echo "<tr><td colspan='2'><p><span>G</span>elieve alle velden in te vullen!</p></td></tr>";}} ?>
                                <?php
								if($_SESSION["igebruikersrol"] != "user"){
									echo "<tr><td width='143'><label for='klanten'><span>N</span>aam klant:</label></td>
											<td width='415'><select name='klanten'>
											<option value='0'"; if($opgegevenklant == 0){ echo "selected";} echo "> </option>";
									$mysqli = new MySQLi("localhost","root","","dietergip");
									if(mysqli_connect_errno()){
										trigger_error("Fout bij verbinding: ".$mysqli->error);
									}
									else{
										$sql = "select klantid, voornaam, naam from tblklanten";
										if($stmt=$mysqli->prepare($sql)){
											if(!$stmt->execute()){
											}
											else{
												$stmt->bind_result($klantid,$voornaam,$naam);
												while($stmt->fetch()){
													echo "<option value=".$klantid." "; if($opgegevenklant == $klantid){ echo "selected";} echo ">".$voornaam." ".$naam."</option>";
												}
											}
										}
									}
									echo "</select></td></tr>";
								}
								?>
								<?php
									if($personenopgegeven == false && isset($_POST["reserveren"])){
										echo "<tr><td colspan='2'><p>Je kan niet reserveren als er niemand komt!</p></td></tr>";
									}
									?>
									<tr>
										<td width="143"><label for="volwassenen"><span>A</span>antal volwassenen:</label></td>
										<td width="415"><input type="number" id="volwassenen" name="volwassenen" min="0" value="<?php @$volwassenen=$_POST["volwassenen"]; if(isset($volwassenen)){$volwassenen=htmlspecialchars($volwassenen); $volwassenen=stripslashes($volwassenen); echo $volwassenen;}?>"></td>
									</tr>
									<tr>
										<td><label for="kinderen"><span>A</span>antal kinderen:</label></td>
										<td><input type="number" id="kinderen" name="kinderen" min="0" value="<?php @$kinderen=$_POST["kinderen"]; if(isset($kinderen)){$kinderen=htmlspecialchars($kinderen); $kinderen=stripslashes($kinderen); echo $kinderen;}?>"></td>
									</tr>
                                    <?php
									if($datumscorrect == False){
										echo "<tr><td colspan='2'><p>De begindatum mag niet later zijn dan de einddatum!</p></td></tr>";
									}
									if($algereserveerd == True){
										echo "<tr><td colspan='2'><p>Deze plaats is tijdens uw opgegeven periode al (gedeeltelijk) gereserveerd</p></td></tr>";
									}
									?>
									<tr>
										<td><label for="begindatum"><span>B</span>egindatum reservering:</label></td>
										<td><input type="text" id="begindatum" name="begindatum" value="<?php @$begindatum=$_POST["begindatum"]; if(isset($begindatum)){$begindatum=htmlspecialchars($begindatum); $begindatum=stripslashes($begindatum); echo $begindatum;}?>"></td>
									</tr>
									<tr>
										<td><label for="einddatum"><span>E</span>inddatum reservering:</label></td>
										<td><input type="text" id="einddatum" name="einddatum" value="<?php @$einddatum=$_POST["einddatum"]; if(isset($einddatum)){$einddatum=htmlspecialchars($einddatum); $einddatum=stripslashes($einddatum); echo $einddatum;}?>"></td>
									</tr>								
                                    </table>
                               <input type="submit" id="submit" value="Reserveren" name="reserveren">
							</div>
						</form>
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