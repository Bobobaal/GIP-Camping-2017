<?php
session_start();
if(!isset($_SESSION["nietingelogd"])){
	$_SESSION["nietingelogd"] = false;
}
if(isset($_POST["aanmelden"]) && $_SESSION["nietingelogd"] == true){
	$_SESSION["doorsturen"] = true;
	$_SESSION["nietingelogd"] = false;
}
$infoklopt = False;
if(isset($_POST["aanmelden"]) && isset($_POST["email"]) && isset($_POST["wachtwoord"]) && $_POST["email"] != "" && $_POST["wachtwoord"] != ""){
	$mysqli= new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
	trigger_error("Fout bij verbinding:".$mysqli->error);
	}
	else{
		$sql="select klantid, email, wachtwoord, gebruikersrol from tblklanten where actief = 1";
			if($stmt = $mysqli->prepare($sql)){
				if(!$stmt->execute()){
					echo "Het uitvoeren van de query is mislukt:".$stmt->error."in query:".$sql;
				}
				else{
					$stmt->bind_result($klantid,$email, $wachtwoord, $gebruikersrol);
					while($stmt->fetch()){
						if($email == $_POST["email"] and md5($_POST["wachtwoord"]) == $wachtwoord){
							$infoklopt = True;
							$_SESSION["iklantid"] = $klantid;
							$_SESSION["igebruikersrol"] = $gebruikersrol;
						}
					}
				}
					$stmt->close();
			}
			else{
				echo "Er zit een fout in de query:".$mysqli->error;
		}
	}
	if($infoklopt == True){
		if($_SESSION["doorsturen"] == true){
			$_SESSION["doorsturen"] = false;
			header("location:reserveren.php");
		}
		else{
			header("location:index.php");
		}
	}
}
?>
<head>
	<meta charset="UTF-8">
	<title>Aanmelden - Camping Le Passage</title>
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
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li class="selected">
                	<a href="login.php"><span>A</span>anmelden</a>
                </li>
			</ul>
		</div>
	</div>
	<div class="body">
		<div class="aanmelden">
			<div>
				<div>
					<div class="aanmelden">
						<h2>AANMELDEN</h2>
						<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
							<div>
								<table width="780px">
                                <?php
								if($_SESSION["nietingelogd"] == true){
									echo "<tr>
									<td colspan='2'><p style='color:red'>Om een plaats te kunnen reserveren moet u ingelogd zijn!</p></td>
									</tr>";
								}
								if(isset($_POST["aanmelden"]) && $infoklopt == False){
									echo "<tr>
									<td colspan='2'><p style='color:red'>E-mail en wachtwoord komen niet overeen, gelieve je gegevens na te kijken</p></td>
									</tr>";
								}
								?>
									<tr>
										<td width="143"><label for="email"><span>E</span>-mail:</label></td>
										<td width="415"><input type="text" id="email" name="email"></td>
									</tr>
									<tr>
										<td><label for="wachtwoord"><span>W</span>achtwoord:</label></td>
										<td><input type="password" id="wachtwoord" name="wachtwoord"></td>
									</tr>
                                    <tr>
										<td colspan="2"><p><span>N</span>og niet geregistreerd? <a href="register.php">Klik hier!</a></p></td>
									</tr>
								</table>
								<input type="submit" id="submit" value="Aanmelden" name="aanmelden">
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