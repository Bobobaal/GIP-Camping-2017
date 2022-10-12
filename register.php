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
$onthoumail = "";
$duplicatemail= False;
$onthouPostID = "";
$geldigePostcode = False;
$geldigemail = false;
if(isset($_POST["registreer"])){
	if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$geldigemail = true;
	}
}
if(isset($_POST["registreer"]) && isset($_POST["postcode"]) && isset($_POST["gemeente"]) && $_POST["postcode"] != "" && $_POST["gemeente"] != ""){
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
if(isset($_POST["registreer"]) && isset($_POST["email"]) && $_POST["email"] != ""){
	$emailcheck = new MySQLi ("localhost","root","","dietergip"); 
	if(mysqli_connect_errno()){ 
		trigger_error("Fout bij verbinding:".$emailcheck->error);
	}
	else{
		$checksql="select email from tblklanten where actief = 1"; 
		if($checkstmt = $emailcheck->prepare($checksql)){
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
					$_POST["email"] = $onthoumail;
				}
			}	
			$checkstmt->close();
		}
		else{
			echo "Er zit een fout in de query:".$emailcheck->error;
		}
	}
} 
if(isset($_POST["registreer"]) && isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["email"]) && isset($_POST["adres"]) && isset($_POST["gemeente"]) && isset($_POST["postcode"]) && isset($_POST["wachtwoord"]) && $_POST["voornaam"] != "" && $_POST["naam"] != "" && $_POST["email"] != "" && $_POST["adres"] != "" && $_POST["gemeente"] != "" && $_POST["postcode"] != "" && $_POST["wachtwoord"] != "" && $duplicatemail == False && $_POST["wachtwoord"] == $_POST["bwachtwoord"] && $geldigePostcode == True && $geldigemail == true){
$mysqli= new MySQLi ("localhost","root","","dietergip");
 if(mysqli_connect_errno()) {
	 trigger_error('Fout bij verbinding: '.$mysqli->error);
 }
else{
	$sql = " INSERT INTO tblklanten ( voornaam, naam, email, adres, PostcodeId, wachtwoord, gebruikersrol, actief ) VALUES ( ?,?,?,?,?,?,?,?)";
	if($stmt = $mysqli->prepare($sql)) {
		$stmt->bind_param('ssssissi',$voornaam,$naam,$email,$adres,$postcodeid,$wachtwoord,$gebruikersrol,$actief);
		$voornaam = $mysqli->real_escape_string($_POST['voornaam']);
		$naam = $mysqli->real_escape_string($_POST['naam']);
		$email = $mysqli->real_escape_string($_POST['email']);
		$adres = $mysqli->real_escape_string($_POST['adres']);
		$postcodeid = $mysqli->real_escape_string($onthouPostID);
		$wachtwoord = $mysqli->real_escape_string(md5($_POST['wachtwoord']));
		$gebruikersrol = "user";
		$actief = 1;
		if(!$stmt->execute()){
		}
		else{
			header("location:login.php");
		}
		$stmt->close();
	}
	else{
		echo "Er zit een fout in de query: ".$mysqli->error; 
	}
}
}
?>
<head>
	<meta charset="UTF-8">
	<title>Register - Camping Le Passage</title>
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
                <li>
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li class="selected">
                	<a href="login.php"><span>A</span>anmelden</a>
                </li>
			</ul>
		</div>
	</div>
	<div class="body">
		<div class="register">
			<div>
				<div>
					<div class="register">
						<h2>REGISTREER HIER!</h2>
					  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="form">
							<div>
								<table width="780px">
                                <?php if(isset($_POST["registreer"])){ if(!isset($_POST["voornaam"]) or !isset($_POST["naam"]) or !isset($_POST["email"]) or !isset($_POST["adres"]) or !isset($_POST["gemeente"]) or !isset($_POST["postcode"]) or !isset($_POST["wachtwoord"]) or $_POST["voornaam"] == "" or $_POST["naam"] == "" or $_POST["email"] == "" or $_POST["adres"] == "" or $_POST["gemeente"] == "" or $_POST["postcode"] == "" or $_POST["wachtwoord"] == ""){ echo "<tr><td colspan='2'><p><span>G</span>elieve alle velden in te vullen!</p></td></tr>";}}
								?>
                                	<tr>
                                    	<td colspan='2'><p id='verplicht'>Zorg ervoor dat je wachtwoord aan de volgende vereisten voldoet:<br>
                                    	Minimum 6 karakters, 1 hoofdletter en 1 cijfer.</p></td>
                                    </tr>

									<tr>
										<td width="143"><label for="voornaam"><span>V</span>oornaam:</label></td>
										<td width="415"><input type="text" required id="voornaam" pattern="^[a-zA-Z][a-zA-Z-]{1,}$" oninvalid="setCustomValidity('Je voornaam kan geen speciale tekens bevatten, op - na!')" onchange="try{setCustomValidity('')}catch(e){}" name="voornaam" value="<?php @$voornaam=$_POST["voornaam"]; if(isset($voornaam)){$voornaam=htmlspecialchars($voornaam); $voornaam=stripslashes($voornaam); echo $voornaam;}?>"></td>
									</tr>
									<tr>
										<td><label for="naam"><span>N</span>aam:</label></td>
										<td><input type="text" id="naam" required pattern="^[a-zA-Z][a-zA-Z -]{1,}$" oninvalid="setCustomValidity('Je familienaam kan geen speciale tekens bevatten, op een spatie en een - na!')" onchange="try{setCustomValidity('')}catch(e){}" name="naam" value="<?php @$naam=$_POST["naam"]; if(isset($naam)){$naam=htmlspecialchars($naam); $naam=stripslashes($naam); echo $naam;}?>"></td>
									</tr>
									<?php
									if(isset($_POST["registreer"]) && $duplicatemail == True){
										echo "<tr><td colspan='2'><p>Er is al een account geregistreerd met het mailadres dat u heeft opgegeven!</p></td></tr>";
									}
									if(isset($_POST["registreer"]) && $geldigemail == false){
										echo "<tr><td colspan='2'><p>U heeft geen geldig e-mailadres opgegeven!</p></td></tr>";
									}
									?>
									<tr>
										<td><label for="email"><span>E</span>-Mail:</label></td>
										<td><input type="email" id="email" name="email" required value="<?php @$email=$_POST["email"]; if(isset($email)){$email=htmlspecialchars($email); $email=stripslashes($email); echo $email;}?>"></td>
									</tr>
									<tr>
										<td><label for="adres"><span>A</span>dres:</label></td>
										<td><input type="text" id="adres" name="adres" required value="<?php @$adres=$_POST["adres"]; if(isset($adres)){$adres=htmlspecialchars($adres); $adres=stripslashes($adres); echo $adres;}?>"></td>
									</tr>
                                    <tr>
                                        <td><label for="postcode"><span>P</span>ostcode:</label></td>
										<td><input type="number" id="postcode" required name="postcode" min="1000" value="<?php @$postcode=$_POST["postcode"]; if(isset($postcode)){$postcode=htmlspecialchars($postcode); $postcode=stripslashes($postcode); echo $postcode;}?>"></td>
									</tr>
									<?php
									if(isset($_POST["registreer"]) && $geldigePostcode == False){
										echo "<tr><td colspan='2'><p>Gemeente komt niet overeen met de opgegeven postcode!</p></td></tr>";
									}
									?>
									<tr>
										<td><label for="gemeente"><span>G</span>emeente:</label></td>
										<td><input type="text" id="gemeente" required name="gemeente" value="<?php @$gemeente=$_POST["gemeente"]; if(isset($gemeente)){$gemeente=htmlspecialchars($gemeente); $gemeente=stripslashes($gemeente); echo $gemeente;}?>"></td>
                                    </tr>
                                    <?php if(isset($_POST["registreer"])){ if($_POST["wachtwoord"] != $_POST["bwachtwoord"]){ echo "<tr><td colspan='2'><p><span>B</span>eide wachtwoorden komen niet overeen</p></td></tr>";}} ?>
                                    <tr>
										<td><label for="wachtwoord">Wachtwoord:</label></td>
										<td><input type="password" required pattern="(?=^.{6,}$)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" name="wachtwoord" id="wachtwoord" oninvalid="setCustomValidity('Je wachtwoord moet minstens 6 karakters lang zijn, 1 hoofdletter en cijfer bevatten!')" onchange="try{setCustomValidity('')}catch(e){}"></td>
                                    </tr>
                                    <tr>
	  									<td><label for="bwachtwoord">Bevestig wachtwoord:</label></td>
	  									<td><input type="password" required name="bwachtwoord" id="bwachtwoord"></td>
								  </tr>
								</table>
                               <input type="submit" id="submit" value="Registreer" name="registreer">
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