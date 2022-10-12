<?php
session_start();
?>
<script>
$(document).ready(function() {
	$("#form").validate();
});
</script>
<?php
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
if($_SESSION["iklantid"] == ""){
	header("location:geentoegang.php");
}
$onthouPostID = "";
$anaam = "";
$avoornaam = "";
$aemail = "";
$aadres = "";
$agemeente = "";
$apostcode = "";
$duplicatemail= False;
$geldigePostcode = False;
$geldigOudWW = False;
$geldigemail = false;
if(isset($_POST["aanpassen"])){
	if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$geldigemail = true;
	}
}
if(isset($_POST["aanpassen"]) && isset($_POST["postcode"]) && isset($_POST["gemeente"]) && $_POST["postcode"] != "" && $_POST["gemeente"] != ""){
	$mysqli = new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding: ".$mysqli->error);
	}
	else{
		$sql = "select PostcodeId, Gemeente from tblgemeente where Postcode = ?";
		if($stmt = $mysqli->prepare($sql)){
			$stmt->bind_param("i",$postcode);
			$postcode = $_POST["postcode"];
			if(!$stmt->execute()){
			}
			else{
				$stmt->bind_result($postcodeid,$gemeente);
				while($stmt->fetch()){
					$gemeente = strtoupper($gemeente);
					$_POST["gemeente"] = strtoupper($_POST["gemeente"]);
					if($gemeente == $_POST["gemeente"]){
						$onthouPostID = $postcodeid;
						$geldigePostcode = True;
					}
				}
			}
			$stmt->close();
		}
	}
}
if(isset($_POST["aanpassen"]) && isset($_POST["owachtwoord"]) && $_POST["owachtwoord"] != ""){
	$mysqli = new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding: ".$mysqli->error);
	}
	else{
		$sql = "select wachtwoord from tblklanten where klantid = ?";
		if($stmt = $mysqli->prepare($sql)){
			$stmt->bind_param("i",$klantid);
			$klantid = $_SESSION["iklantid"];
			if(!$stmt->execute()){
			}
			else{
				$stmt->bind_result($wachtwoord);
				while($stmt->fetch()){
					if($wachtwoord == md5($_POST["owachtwoord"])){
						$geldigOudWW = True;
					}
				}
			}
			$stmt->close();
		}
	}
}
if(isset($_POST["aanpassen"]) && isset($_POST["email"]) && $_POST["email"] != ""){
	$emailcheck = new MySQLi ("localhost","root","","dietergip"); 
	if(mysqli_connect_errno()){ 
		trigger_error("Fout bij verbinding:".$emailcheck->error);
	}
	else{
		$checksql="select email from tblklanten where actief = 1 and klantid <> ?"; 
		if($checkstmt = $emailcheck->prepare($checksql)){
			$checkstmt->bind_param("i",$klantid);
				$klantid = $_SESSION["iklantid"];
			if(!$checkstmt->execute()){ 
				echo "Het uitvoeren van de query is mislukt:".$checkstmt->error."in query:".$checksql;
			}
			else{
				$checkstmt->bind_result($email);
				$onthoumail = $_POST["email"];
				$_POST["email"] = strtoupper($_POST["email"]);
				while($checkstmt->fetch()){ 
					$email = strtoupper($email);
					if($email == $_POST["email"]){
						$duplicatemail = True;
					}
				}
				$_POST["email"] = $onthoumail;
			}	
			$checkstmt->close();
		}
		else{
			echo "Er zit een fout in de query:".$emailcheck->error;
		}
	}
} 
if(!isset($_POST["aanpassen"])){
	$mysqli= new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding:".$mysqli->error);
	}
	else{
	$sql= " SELECT naam, voornaam, email, adres, g.Gemeente, g.Postcode from tblklanten k, tblgemeente g
			WHERE g.PostcodeId = k.PostcodeId and klantid = ?";
	if($stmt=$mysqli->prepare($sql)){
		$stmt->bind_param("i",$klantid);
		$klantid = $_SESSION["iklantid"];
		if(!$stmt->execute()){
			echo "het uitvoeren van de query is mislukt:";
		}
		else {
		$stmt->bind_result($naam,$voornaam,$email,$adres,$gemeente,$postcode);
		while($stmt->fetch()){
			$anaam = $naam;
			$avoornaam = $voornaam;
			$aemail = $email;
			$aadres = $adres;
			$apostcode = $postcode;
			$agemeente = $gemeente;
		}
		}
		$stmt->close();
	}
	else{
		echo "Er zit een fout in de query";
	}
}
}
if(isset($_POST["aanpassen"]) && isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["email"]) && isset($_POST["adres"]) && isset($_POST["gemeente"]) && isset($_POST["postcode"]) && $_POST["voornaam"] != "" && $_POST["naam"] != "" && $_POST["email"] != "" && $_POST["adres"] != "" && $_POST["gemeente"] != "" && $_POST["postcode"] != "" && $_POST["owachtwoord"] == "" && $geldigePostcode == True && $duplicatemail == False && $geldigemail == true){
	$mysqli= new MySQLi ("localhost","root","","dietergip");
 if(mysqli_connect_errno()) {trigger_error('Fout bij verbinding: '.$mysqli->error); }
else{
	$sql = " UPDATE tblklanten SET voornaam = ?, naam = ?, email = ?, adres = ?, PostcodeId = ? WHERE klantid = ?";
if($stmt = $mysqli->prepare($sql)) {
 $stmt->bind_param('ssssii',$voornaam,$naam,$email,$adres,$postcodeid,$klantid);
 $klantid = $_SESSION["iklantid"];
 $voornaam = $mysqli->real_escape_string($_POST['voornaam']);
 $naam = $mysqli->real_escape_string($_POST['naam']);
 $email = $mysqli->real_escape_string($_POST['email']);
 $adres = $mysqli->real_escape_string($_POST['adres']);
 $postcodeid = $mysqli->real_escape_string($onthouPostID);
 if(!$stmt->execute()){
 }
 else{
	 header("location:mijnprofiel.php");
 }
$stmt->close();
}
}
}

if(isset($_POST["aanpassen"]) && isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["email"]) && isset($_POST["adres"]) && isset($_POST["gemeente"]) && isset($_POST["postcode"]) && isset($_POST["nwachtwoord"]) && isset($_POST["bnwachtwoord"]) && $_POST["voornaam"] != "" && $_POST["naam"] != "" && $_POST["email"] != "" && $_POST["adres"] != "" && $_POST["gemeente"] != "" && $_POST["postcode"] != "" && $_POST["nwachtwoord"] != "" && $_POST["bnwachtwoord"] != "" && $_POST["nwachtwoord"] == $_POST["bnwachtwoord"] && $geldigePostcode == True && $duplicatemail == False && $geldigOudWW == True && $geldigemail == true){
	$mysqli= new MySQLi ("localhost","root","","dietergip");
 if(mysqli_connect_errno()) {trigger_error('Fout bij verbinding: '.$mysqli->error); }
else{
	$sql = " UPDATE tblklanten SET voornaam = ?, naam = ?, email = ?, adres = ?, PostcodeId = ?, wachtwoord = ? WHERE klantid = ?";
if($stmt = $mysqli->prepare($sql)) {
 $stmt->bind_param('ssssisi',$voornaam,$naam,$email,$adres,$postcodeid,$wachtwoord,$klantid);
 $klantid = $_SESSION["iklantid"];
 $voornaam = $mysqli->real_escape_string($_POST['voornaam']);
 $naam = $mysqli->real_escape_string($_POST['naam']);
 $email = $mysqli->real_escape_string($_POST['email']);
 $adres = $mysqli->real_escape_string($_POST['adres']);
 $wachtwoord = $mysqli->real_escape_string(md5($_POST["nwachtwoord"]));
 $postcodeid = $mysqli->real_escape_string($onthouPostID);
 if(!$stmt->execute()){
 }
 else{
	 header("location:mijnprofiel.php");
 }
$stmt->close();
}
}
}
?>
<head>
	<meta charset="UTF-8">
	<title>Aanpassen klant - Camping Le Passage</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="icon" href="favicon.png">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="js/jquery.validate.min.js"></script>
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
		<div class="profielaanpassen">
			<div>
				<div>
					<div class="profielaanpassen">
						<h2>Profiel aanpassen</h2>
						<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="form">
							<div>
								<table width="780px">
                                <?php if(isset($_POST["aanpassen"])){ if(!isset($_POST["voornaam"]) or !isset($_POST["naam"]) or !isset($_POST["email"]) or !isset($_POST["adres"]) or !isset($_POST["gemeente"]) or !isset($_POST["postcode"]) or $_POST["voornaam"] == "" or $_POST["naam"] == "" or $_POST["email"] == "" or $_POST["adres"] == "" or $_POST["gemeente"] == "" or $_POST["postcode"] == ""){ echo "<tr><td colspan='2'><p><span>G</span>elieve alle velden in te vullen!</p></td></tr>";}}
									?>
                                	<tr>
                                    	<td colspan='2'><p id='verplicht'>Alle velden behalve de wachtwoordvelden zijn verplicht.<br>
                                    	Vul alleen de wachtwoordvelden in als je je wachtwoord wilt veranderen maar dan moet die wel aan volgende eisen voldoen:<br>
                                    	Minimum 6 karakters, 1 hoofdletter en 1 cijfer.</p></td>
                                    </tr>
									<tr>
										<td width="143"><label for="voornaam"><span>V</span>oornaam:</label></td>
										<td width="415"><input type="text" id="voornaam" required pattern="^[a-zA-Z][a-zA-Z-]{1,}$" oninvalid="setCustomValidity('Je voornaam kan geen speciale tekens bevatten, op - na!')" onchange="try{setCustomValidity('')}catch(e){}" name="voornaam" value="<?php @$voornaam=$_POST["voornaam"]; if(isset($voornaam)){$voornaam=htmlspecialchars($voornaam); $voornaam=stripslashes($voornaam); echo $voornaam;} else{ echo $avoornaam;}?>"></td>
									</tr>
									<tr>
										<td><label for="naam"><span>N</span>aam:</label></td>
										<td><input type="text" id="naam" name="naam" required attern="^[a-zA-Z][a-zA-Z -]{1,}$" oninvalid="setCustomValidity('Je familienaam kan geen speciale tekens bevatten, op een spatie en een - na!')" onchange="try{setCustomValidity('')}catch(e){}" value="<?php @$naam=$_POST["naam"]; if(isset($naam)){$naam=htmlspecialchars($naam); $naam=stripslashes($naam); echo $naam;} else{ echo $anaam;}?>"></td>
									</tr>
									<?php
									if(isset($_POST["aanpassen"]) && $duplicatemail == True){
										echo "<tr><td colspan='2'><p>Er is al een account geregistreerd met het mailadres dat u heeft opgegeven!</p></td></tr>";
									}
									if(isset($_POST["aanpassen"]) && $geldigemail == false){
										echo "<tr><td colspan='2'><p>U heeft geen geldig e-mailadres opgegeven!</p></td></tr>";
									}
									?>
									<tr>
										<td><label for="email"><span>E</span>-Mail:</label></td>
										<td><input type="email" id="email" required name="email" value="<?php @$email=$_POST["email"]; if(isset($email)){$email=htmlspecialchars($email); $email=stripslashes($email); echo $email;} else{ echo $aemail;}?>"></td>
									</tr>
									<tr>
										<td><label for="adres"><span>A</span>dres:</label></td>
										<td><input type="text" id="adres" required name="adres" value="<?php @$adres=$_POST["adres"]; if(isset($adres)){$adres=htmlspecialchars($adres); $adres=stripslashes($adres); echo $adres;} else{ echo $aadres;}?>"></td>
									</tr>
                                   <?php
									if(isset($_POST["aanpassen"]) && $geldigePostcode == False){
										echo "<tr><td colspan='2'><p>Gemeente komt niet overeen met de opgegeven postcode!</p></td></tr>";
									}
									?>
                                    <tr>
                                        <td><label for="postcode"><span>P</span>ostcode:</label></td>
										<td><input type="number" id="postcode" required name="postcode" min="1000" value="<?php @$postcode=$_POST["postcode"]; if(isset($postcode)){$postcode=htmlspecialchars($postcode); $postcode=stripslashes($postcode); echo $postcode;} else{ echo $apostcode;}?>"></td>
									</tr>
									<tr>
										<td><label for="gemeente"><span>G</span>emeente:</label></td>
										<td><input type="text" id="gemeente" required name="gemeente" value="<?php @$gemeente=$_POST["gemeente"]; if(isset($gemeente)){$gemeente=htmlspecialchars($gemeente); $gemeente=stripslashes($gemeente); echo $gemeente;} else{ echo $agemeente;}?>"></td>
                                    </tr>
                                    <tr>
										<td><label for="owachtwoord">Oud wachtwoord:</label></td>
										<td><input type="password" name="owachtwoord" id="owachtwoord"></td>
                                    </tr>
                                    <tr>
										<td><label for="nwachtwoord">Nieuw wachtwoord:</label></td>
										<td><input type="password" pattern="(?=^.{6,}$)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" name="nwachtwoord" id="nwachtwoord" oninvalid="setCustomValidity('Je wachtwoord moet minstens 6 karakters lang zijn, 1 hoofdletter en cijfer bevatten!')" onchange="try{setCustomValidity('')}catch(e){}"></td>
                                    </tr>
                                    <tr>
	  									<td><label for="bnwachtwoord">Bevestig nieuw wachtwoord:</label></td>
	  									<td><input type="password" name="bnwachtwoord" id="bnwachtwoord"></td>
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