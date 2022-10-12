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
if($_SESSION["igebruikersrol"] == "user"){
	header("location:geentoegang.php");
}
$onthoumail = "";
$duplicatemail= False;
$onthouPostID = "";
$geldigePostcode = False;
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
if(isset($_POST["aanpassen"]) && isset($_POST["email"]) && $_POST["email"] != ""){
	$emailcheck = new MySQLi ("localhost","root","","dietergip"); 
	if(mysqli_connect_errno()){ 
		trigger_error("Fout bij verbinding:".$emailcheck->error);
	}
	else{
		$checksql="select email from tblklanten where actief = 1 and klantid <> ?"; 
		if($checkstmt = $emailcheck->prepare($checksql)){
			$checkstmt->bind_param("i",$klantid);
				$klantid = $_SESSION["klantid"];
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
if(isset($_POST["aanpassen"]) && isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["email"]) && isset($_POST["adres"]) && isset($_POST["gemeente"]) && isset($_POST["postcode"]) && isset($_POST["gebruikersrol"]) && $_POST["voornaam"] != "" && $_POST["naam"] != "" && $_POST["email"] != "" && $_POST["adres"] != "" && $_POST["gemeente"] != "" && $_POST["postcode"] != "" && $_POST["gebruikersrol"] != "" && $geldigePostcode == True && $duplicatemail == False && $geldigemail == true){
$mysqli= new MySQLi ("localhost","root","","dietergip");
 if(mysqli_connect_errno()) {trigger_error('Fout bij verbinding: '.$mysqli->error); }
else
{$sql = " UPDATE tblklanten SET voornaam = ?, naam = ?, email = ?, adres = ?, PostcodeId = ?, gebruikersrol = ?, actief = ? WHERE klantid = ?";
if($stmt = $mysqli->prepare($sql)) {
 $stmt->bind_param('ssssisii',$voornaam,$naam,$email,$adres,$postcodeid,$gebruikersrol,$actief,$klantid);
 $klantid = $_SESSION["klantid"];
 $voornaam = $mysqli->real_escape_string($_POST['voornaam']);
 $naam = $mysqli->real_escape_string($_POST['naam']);
 $email = $mysqli->real_escape_string($_POST['email']);
 $adres = $mysqli->real_escape_string($_POST['adres']);
 $postcodeid = $mysqli->real_escape_string($onthouPostID);
 $gebruikersrol = $mysqli->real_escape_string($_POST['gebruikersrol']);
	if(isset($_POST["actief"])){
		$actief = 1;
	}
	else{
		$actief = 0;
	}
 if(!$stmt->execute()){
 }
 else
 { header("location:klanten.php");
 $_SESSION["klantid"] = "";
 $_SESSION["naam"] = "";
 $_SESSION["voornaam"] = "";
 $_SESSION["email"] = "";
 $_SESSION["adres"] = "";
 $_SESSION["gemeente"] = "";
 $_SESSION["postcode"] = "";
 $_SESSION["gebruikersrol"] = "";
 
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
		<div class="klantaanpassen">
			<div>
				<div>
					<div class="klantaanpassen">
						<h2>Klant aanpassen</h2>
						<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" id="form">
							<div>
								<table width="780px">
									<tr>
										<td width="143"><label for="voornaam"><span>V</span>oornaam:</label></td>
										<td width="415"><input type="text" id="voornaam" required pattern="^[a-zA-Z][a-zA-Z-]{1,}$" oninvalid="setCustomValidity('De voornaam kan geen speciale tekens bevatten, op - na!')" onchange="try{setCustomValidity('')}catch(e){}" name="voornaam" value="<?php @$voornaam=$_POST["voornaam"]; if(isset($voornaam)){$voornaam=htmlspecialchars($voornaam); $voornaam=stripslashes($voornaam); echo $voornaam;}  else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["voornaam"];}}?>"></td>
									</tr>
									<tr>
										<td><label for="naam"><span>N</span>aam:</label></td>
										<td><input type="text" id="naam" required attern="^[a-zA-Z][a-zA-Z -]{1,}$" oninvalid="setCustomValidity('De familienaam kan geen speciale tekens bevatten, op een spatie en een - na!')" onchange="try{setCustomValidity('')}catch(e){}" name="naam" value="<?php @$naam=$_POST["naam"]; if(isset($naam)){$naam=htmlspecialchars($naam); $naam=stripslashes($naam); echo $naam;}  else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["naam"];}}?>"></td>
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
										<td><input type="email" id="email" required name="email" value="<?php @$email=$_POST["email"]; if(isset($email)){$email=htmlspecialchars($email); $email=stripslashes($email); echo $email;}  else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["email"];}}?>"></td>
									</tr>
									<tr>
										<td><label for="adres"><span>A</span>dres:</label></td>
										<td><input type="text" id="adres" required name="adres" value="<?php @$adres=$_POST["adres"]; if(isset($adres)){$adres=htmlspecialchars($adres); $adres=stripslashes($adres); echo $adres;}  else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["adres"];}}?>"></td>
									</tr>
                                   <?php
									if(isset($_POST["aanpassen"]) && $geldigePostcode == False){
										echo "<tr><td colspan='2'><p>Gemeente komt niet overeen met de opgegeven postcode!</p></td></tr>";
									}
									?>
                                    <tr>
                                        <td><label for="postcode"><span>P</span>ostcode:</label></td>
										<td><input type="number" id="postcode" required name="postcode" min="1000" value="<?php @$postcode=$_POST["postcode"]; if(isset($postcode)){$postcode=htmlspecialchars($postcode); $postcode=stripslashes($postcode); echo $postcode;} else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["postcode"];}}?>"></td>
									</tr>
									<tr>
										<td><label for="gemeente"><span>G</span>emeente:</label></td>
										<td><input type="text" id="gemeente" required name="gemeente" value="<?php @$gemeente=$_POST["gemeente"]; if(isset($gemeente)){$gemeente=htmlspecialchars($gemeente); $gemeente=stripslashes($gemeente); echo $gemeente;} else{ if(!isset($_POST["aanpassen"])){ echo $_SESSION["gemeente"];}}?>"></td>
                                    </tr>
                                    <tr <?php if($_SESSION["klantid"] == $_SESSION["iklantid"]){ echo "hidden";}?>>
                                    	<td><label for="gebruikersrol"><span>G</span>ebruikersrol:</label></td>
                                        <td><select name="gebruikersrol" id="gebruikersrol"><option value="user" <?php @$gebruikersrol=$_POST["gebruikersrol"]; if(isset($gebruikersrol) && $gebruikersrol == "user"){ echo "selected";}else{ if(!isset($_POST["aanpassen"]) && $_SESSION["gebruikersrol"] == "user"){ echo "selected";}}?>>user</option>
  <option value="admin" <?php @$gebruikersrol=$_POST["gebruikersrol"]; if(isset($gebruikersrol) && $gebruikersrol == "admin"){ echo "selected";}else{ if(!isset($_POST["aanpassen"]) && $_SESSION["gebruikersrol"] == "admin"){ echo "selected";}}?>>admin</option></select></td>
									</tr>
									<tr>
										<td><label for="actief"><span>A</span>ctief</label></td>
										<td><input type="checkbox" name="actief" <?php
										$mysqli= new MySQLi("localhost","root","","dietergip");
										if(mysqli_connect_errno()){
											trigger_error("Fout bij verbinding:".$mysqli->error);
										}
										else{
											$sql="select actief from tblklanten where klantid = ?";
										if($stmt = $mysqli->prepare($sql)){
											$stmt->bind_param("i", $klantid);
											$klantid = $_SESSION["klantid"];
											if(!$stmt->execute()){
												echo "Het uitvoeren van de query is mislukt:".$stmt->error."in query:".$sql;
											}
											else{
												$stmt->bind_result($actief);
												while($stmt->fetch()){
													if($actief == 1){
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