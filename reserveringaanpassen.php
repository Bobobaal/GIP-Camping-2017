<?php
session_start();
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["bronresaanpassing"])){
	$_SESSION["bronresaanpassingleeg"] = true;
	header("location:reserveringen.php");
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
if($_SESSION["igebruikersrol"] == "user" && $_SESSION["bronresaanpassing"] != "Profiel"){
	header("location:geentoegang.php");
}
$datumscorrect = True;
$algereserveerd = False;
$personenopgegeven = false;
$inklantid = 0;
if(isset($_POST["aanpassen"])){
	if($_SESSION["igebruikersrol"] == "admin"){
		$inklantid = $_POST["klanten"];
	}
	if($_SESSION["igebruikersrol"] != "admin"){
		$inklantid = $_SESSION["iklantid"];
	}
	if($_SESSION["igebruikersrol"] == "admin" && $_POST["klanten"] == 0){
		$inklantid = $_SESSION["iklantid"];
	}
}
if(isset($_POST["aanpassen"])){
	if($_POST["volwassenen"] > 0){
		$personenopgegeven = true;
	}
	if($_POST["kinderen"] > 0){
		$personenopgegeven = true;
	}
}
									if(isset($_POST["aanpassen"])){
										if($_POST["begindatum"] > $_POST["einddatum"]){
											$datumscorrect = False;
										}
										$mysqli = new MySQLi("localhost","root","","dietergip");
										if(mysqli_connect_errno()){
											trigger_error("Fout bij verbinding: ".$mysqli->error);
										}
										else{
											$sql = "SELECT begindatum, einddatum from tblreserveringen r1, tblreserveringenperplaats r2 where r1.reserveringsid = r2.reserveringsid and plaatsid = ? and r1.reserveringsid != ?";
											if($stmt = $mysqli->prepare($sql)){
												$stmt->bind_param("ii", $plaatsid, $reserveringsid);
												$plaatsid = $_SESSION["plaatsid"];
												$reserveringsid = $_SESSION["reserveringsid"];
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
if(isset($_POST["aanpassen"]) && isset($_POST["volwassenen"]) && isset($_POST["kinderen"]) && isset($_POST["begindatum"]) && isset($_POST["einddatum"]) && isset($_POST["plaatsnr"]) && $_POST["volwassenen"] != "" && $_POST["kinderen"] != "" && $_POST["begindatum"] != "" && $_POST["einddatum"] != "" && $_POST["plaatsnr"] != "" && $datumscorrect == True && $algereserveerd == False && $personenopgegeven == true){
$mysqli= new MySQLi ("localhost","root","","dietergip");
 if(mysqli_connect_errno()) {trigger_error('Fout bij verbinding: '.$mysqli->error); }
else{
$sql = " UPDATE tblreserveringenperplaats SET volwassenen = ?, kinderen = ?, plaatsid = ? WHERE reserveringsid = ?";
$sql2 = " UPDATE tblreserveringen SET begindatum = ?, einddatum = ?, klantid = ?, annuleerd = ? WHERE reserveringsid = ?";
if($stmt = $mysqli->prepare($sql)) {
 $stmt->bind_param('iiii',$volwassenen,$kinderen,$plaatsid,$reserveringsid);
 $reserveringsid = $_SESSION["reserveringsid"];
 $volwassenen = $mysqli->real_escape_string($_POST['volwassenen']);
 $kinderen = $mysqli->real_escape_string($_POST['kinderen']);
 $plaatsid = $mysqli->real_escape_string($_POST['plaatsnr']);
 if(!$stmt->execute()){
 }
 else{
 if($stmt2 = $mysqli->prepare($sql2)) {
 $stmt2->bind_param('ssiii',$begindatum,$einddatum,$klantid, $annuleerd, $reserveringsid);
 $reserveringsid = $_SESSION["reserveringsid"];
 $begindatum = $mysqli->real_escape_string($_POST['begindatum']);
 $einddatum = $mysqli->real_escape_string($_POST['einddatum']);
 $klantid = $mysqli->real_escape_string($inklantid);
	 if(isset($_POST["annuleerd"])){
		 $annuleerd = 1;
	 }
	 if(!isset($_POST["annuleerd"])){
		 $annuleerd = 0;
	 }
 if(!$stmt2->execute()){
 }
 else{
	 $_SESSION["reserveringsid"] = "";
	 $_SESSION["volwassenen"] = "";
	 $_SESSION["kinderen"] = "";
	 $_SESSION["begindatum"] = "";
	 $_SESSION["einddatum"] = "";
	 $_SESSION["plaatsid"] = "";
	 if($_SESSION["bronresaanpassing"] == "Profiel"){
		 $_SESSION["bronresaanpassing"] = "";
		 header("location:mijnprofiel.php");
	 }
	 if($_SESSION["bronresaanpassing"] == "Reserveringen"){
		 $_SESSION["bronresaanpassing"] = "";
		 header("location:reserveringen.php");
	 }
 }
 $stmt2->close();
 }
 }
$stmt->close();
}
}
}

?>
<head>
	<meta charset="UTF-8">
	<title>Aanpassen reservering - Camping Le Passage</title>
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
                <li <?php if($_SESSION["igebruikersrol"] != "admin"){ echo "hidden";}?>>
					<a href="klanten.php"><span>K</span>lanten</a>
				</li>
                <li>
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li <?php if($_SESSION["bronresaanpassing"] == "Reserveringen"){ echo "class='selected'";} if($_SESSION["igebruikersrol"] != "admin"){ echo "hidden";}?>>
                	<a href="reserveringen.php"><span>R</span>eserveringen</a>
                </li>
                <li <?php if($_SESSION["bronresaanpassing"] == "Profiel"){ echo "class='selected'";} ?>>
                	<a href="mijnprofiel.php"><span>M</span>ijn profiel</a>
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
						<h2>Reservering aanpassen</h2>
						<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
							<div>
								<table width="780px">
                                <?php if(isset($_POST["reserveren"])){ if(!isset($_POST["volwassenen"]) or !isset($_POST["kinderen"]) or !isset($_POST["einddatum"]) or !isset($_POST["einddatum"]) or $_POST["volwassenen"] == "" or $_POST["kinderen"] == "" or $_POST["begindatum"] == "" or $_POST["einddatum"] == ""){ echo "<tr><td colspan='2'><p><span>G</span>elieve alle velden in te vullen!</p></td></tr>";}} ?>
                                <?php
                                if($_SESSION["igebruikersrol"] != "user"){
									echo "<tr><td width='143'><label for='klanten'><span>N</span>aam klant:</label></td>
											<td width='415'><select name='klanten'>
											<option value='0'> </option>";
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
													echo "<option value=".$klantid." ";if($klantid == $_SESSION["klantid"]){echo "selected";} echo ">".$voornaam." ".$naam."</option>";
												}
											}
										}
									}
									echo "</select></td></tr>";
								}
									?>
									<?php
									if($personenopgegeven == false && isset($_POST["aanpassen"])){
										echo "<tr><td colspan='2'><p>Je kan niet reserveren als er niemand komt!</p></td></tr>";
									}
									?>
									<tr>
										<td width="143"><label for="volwassenen"><span>A</span>antal volwassenen:</label></td>
										<td width="415"><input type="number" id="volwassenen" name="volwassenen" min="0" value="<?php @$volwassenen=$_POST["volwassenen"]; if(isset($volwassenen)){$volwassenen=htmlspecialchars($volwassenen); $volwassenen=stripslashes($volwassenen); echo $volwassenen;}else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["volwassenen"];}}?>"></td>
									</tr>
									<tr>
										<td><label for="kinderen"><span>A</span>antal kinderen:</label></td>
										<td><input type="number" id="kinderen" name="kinderen" min="0" value="<?php @$kinderen=$_POST["kinderen"]; if(isset($kinderen)){$kinderen=htmlspecialchars($kinderen); $kinderen=stripslashes($kinderen); echo $kinderen;}else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["kinderen"];}}?>"></td>
									</tr>
                                    <?php
									if($datumscorrect == False){
										echo "<tr><td><p>De begindatum mag niet later zijn dan de einddatum!</p></td></tr>";
									}
									if($algereserveerd == True){
										echo "<tr><td colspan='2'><p>Deze plaats is tijdens uw opgegeven periode al (gedeeltelijk) gereserveerd</p></td></tr>";
									}
									?>
									<tr>
										<td><label for="begindatum"><span>B</span>egindatum reservering:</label></td>
										<td><input type="text" id="begindatum" name="begindatum" value="<?php @$begindatum=$_POST["begindatum"]; if(isset($begindatum)){$begindatum=htmlspecialchars($begindatum); $begindatum=stripslashes($begindatum); echo $begindatum;}else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["begindatum"];}}?>"></td>
									</tr>
									<tr>
										<td><label for="einddatum"><span>E</span>inddatum reservering:</label></td>
										<td><input type="text" id="einddatum" name="einddatum" value="<?php @$einddatum=$_POST["einddatum"]; if(isset($einddatum)){$einddatum=htmlspecialchars($einddatum); $einddatum=stripslashes($einddatum); echo $einddatum;}else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["einddatum"];}}?>"></td>
									</tr>
                                   	<tr>
                                    	<td><label for="plaatsnr"><span>P</span>laats Nr. <a href="./images/plattegrondnummers.jpg" alt="plattegrond met nummers" target="_blank">(Plattegrond)</a>:</label></td>
                                        <td><select name="plaatsnr" id="plaatsnr">
										<?php
										$mysqli= new MySQLi("localhost","root","","dietergip");
										if(mysqli_connect_errno()){
											trigger_error("Fout bij verbinding:".$mysqli->error);
										}
										else{
											$sql="select plaatsid from tblplaatsen where gekochte_caravan = 0";
										if($stmt = $mysqli->prepare($sql)){
											if(!$stmt->execute()){
												echo "Het uitvoeren van de query is mislukt:".$stmt->error."in query:".$sql;
											}
											else{
												$stmt->bind_result($plaatsid);
												while($stmt->fetch()){
													echo "<option value=\"".$plaatsid."\"";
													if($plaatsid == $_SESSION["plaatsid"]){
														echo "selected";
													}
													echo ">".$plaatsid."</option>";
												}
											}
										$stmt->close();
										}
										else{
											echo "Er zit een fout in de query:".$mysqli->error;
										}
										}
										?>
                                        </select></td>
									</tr>
                                    <tr>
                                        <td><label for="annuleerd"><span>G</span>eannuleerd:</label></td>
                                        <td><input type="checkbox" name="annuleerd" <?php
												   $mysqli= new MySQLi("localhost","root","","dietergip");
										if(mysqli_connect_errno()){
											trigger_error("Fout bij verbinding:".$mysqli->error);
										}
										else{
											$sql="select annuleerd from tblreserveringen where reserveringsid = ?";
										if($stmt = $mysqli->prepare($sql)){
											$stmt->bind_param("i", $reserveringsid);
											$reserveringsid = $_SESSION["reserveringsid"];
											if(!$stmt->execute()){
												echo "Het uitvoeren van de query is mislukt:".$stmt->error."in query:".$sql;
											}
											else{
												$stmt->bind_result($annuleerd);
												while($stmt->fetch()){
													if($annuleerd == 1){
														echo "checked";
													}
												}
											}
										$stmt->close();
										}
										else{
											echo "Er zit een fout in de query:".$mysqli->error;
										}
										}
												   ?>></input></td>
                                   </tr>
                                    </table>
                               <input type="submit" id="submit" value="Aanpassen" name="aanpassen">
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