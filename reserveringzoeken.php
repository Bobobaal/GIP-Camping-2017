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
$ietsingevuld = False;
if(isset($_POST["voornaam"]) and $_POST["voornaam"] != ""){$ietsingevuld = True;}
if(isset($_POST["naam"]) and $_POST["naam"] != ""){$ietsingevuld = True;}
if(isset($_POST["begindatum"]) and $_POST["begindatum"] != ""){$ietsingevuld = True;}
if(isset($_POST["einddatum"]) and $_POST["einddatum"] != ""){$ietsingevuld = True;}
if(isset($_POST["plaatsnr"]) and $_POST["plaatsnr"] != ""){$ietsingevuld = True;}
if(isset($_POST["zoeken"])){ if($ietsingevuld == True){
$_SESSION["SQL"] = "select r1.reserveringsid, plaatsid, voornaam, naam, begindatum, einddatum, annuleerd from tblklanten k, tblreserveringen r1, tblreserveringenperplaats r2 where k.klantid = r1.klantid AND r1.reserveringsid = r2.reserveringsid AND annuleerd = 0 ";
if(isset($_POST["zoeken"])){
	if(isset($_POST["voornaam"]) && $_POST["voornaam"] != ""){ $voornaam=$_POST["voornaam"]; if(isset($voornaam)){$voornaam=htmlspecialchars($voornaam); $voornaam=stripslashes($voornaam);} $_SESSION["SQL"] .= " AND voornaam like \"%".$voornaam."%\"";}
	if(isset($_POST["naam"]) && $_POST["naam"] != ""){ $naam=$_POST["naam"]; if(isset($naam)){$naam=htmlspecialchars($naam); $naam=stripslashes($naam);} $_SESSION["SQL"] .= " AND naam like \"%".$naam."%\"";}
	if(isset($_POST["begindatum"]) && $_POST["begindatum"] != ""){ $_SESSION["SQL"] .= " AND begindatum = \"".$_POST["begindatum"]."\"";}
	if(isset($_POST["einddatum"]) && $_POST["einddatum"] != ""){ $_SESSION["SQL"] .= " AND einddatum = \"".$_POST["einddatum"]."\"";}
	if(isset($_POST["plaatsnr"]) && $_POST["plaatsnr"] != ""){ $_SESSION["SQL"] .= " AND plaatsid = \"".$_POST["plaatsnr"]."\"";}
header("location:reserveringenzoekresultaat.php");
}
}
}
?>
<head>
	<meta charset="UTF-8">
	<title>Reservaties zoeken - Camping Le Passage</title>
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
		<div class="zoeken">
			<div>
				<div>
					<div class="zoeken">
						<h2>reservering zoeken</h2>
						<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
						  <div>
								<table width="780px">
                                <?php if(isset($_POST["zoeken"])){ if($ietsingevuld == False){ echo "<tr><td colspan='2'><p><span>G</span>elieve een zoekopdracht in te vullen!</p></td></tr>";}} ?>
									<tr>
										<td width="143"><label for="voornaam"><span>V</span>oornaam:</label></td>
										<td width="415"><input type="text" id="voornaam" name="voornaam"></td>
									</tr>
									<tr>
										<td><label for="naam"><span>N</span>aam:</label></td>
										<td><input type="text" id="naam" name="naam"></td>
									</tr>
									<tr>
										<td><label for="begindatum"><span>B</span>egindatum:</label></td>
										<td><input type="text" id="begindatum" name="begindatum"></td>
									</tr>
									<tr>
										<td><label for="einddatum"><span>E</span>inddatum:</label></td>
										<td><input type="text" id="einddatum" name="einddatum"></td>
                                    </tr>
                                    <tr>
                                    	<td><label for="plaatsnr"><span>P</span>laats Nr.:</label></td>
                                        <td><select name="plaatsnr" id="plaatsnr"><option disabled selected value=""> -- selecteer een optie -- </option>
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
													echo "<option value=".$plaatsid.">".$plaatsid."</option>";
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
                                    <tr>
								</table>
                             <input type="submit" id="submit" value="Zoeken" name="zoeken">
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