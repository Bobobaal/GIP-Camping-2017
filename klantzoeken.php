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
$zoektermgegeven = False;
$ietsingevuld = False;
if(isset($_POST["voornaam"]) and $_POST["voornaam"] != ""){$ietsingevuld = True;}
if(isset($_POST["naam"]) and $_POST["naam"] != ""){$ietsingevuld = True;}
if(isset($_POST["email"]) and $_POST["email"] != ""){$ietsingevuld = True;}
if(isset($_POST["adres"]) and $_POST["adres"] != ""){$ietsingevuld = True;}
if(isset($_POST["gemeente"]) and $_POST["gemeente"] != ""){$ietsingevuld = True;}
if(isset($_POST["postcode"]) and $_POST["postcode"] != ""){$ietsingevuld = True;}
if(isset($_POST["gebruikersrol"]) and $_POST["gebruikersrol"] != ""){$ietsingevuld = True;}
if(isset($_POST["zoeken"])){ if($ietsingevuld == True){
$_SESSION["SQL"] = "SELECT klantid, voornaam, naam, email, actief FROM tblklanten k ";
if(isset($_POST["zoeken"])){
	if(isset($_POST["gemeente"]) && $_POST["gemeente"] != ""){ if($zoektermgegeven == False){$gemeente=$_POST["gemeente"]; if(isset($gemeente)){$gemeente=htmlspecialchars($gemeente); $gemeente=stripslashes($gemeente);} $_SESSION["SQL"] .= ",tblgemeente g where g.postcodeid = k.postcodeid and gemeente like \"%".$gemeente."%\"";$zoektermgegeven = True;}else{$gemeente=$_POST["gemeente"]; if(isset($gemeente)){$gemeente=htmlspecialchars($gemeente); $gemeente=stripslashes($gemeente);} $_SESSION["SQL"] .= " AND tblgemeente.gemeente like \"%".$gemeente."%\"";}}
	if(isset($_POST["postcode"]) && $_POST["postcode"] != ""){ if($zoektermgegeven == False){$_SESSION["SQL"] .= ",tblgemeente g where g.postcodeid = k.postcodeid and postcode like \"%".$_POST["postcode"]."%\"";$zoektermgegeven = True;}else{$_SESSION["SQL"] .= " AND postcode like \"%".$_POST["postcode"]."%\"";}}
	if(isset($_POST["voornaam"]) && $_POST["voornaam"] != ""){ if($zoektermgegeven == False){ $voornaam=$_POST["voornaam"]; if(isset($voornaam)){$voornaam=htmlspecialchars($voornaam); $voornaam=stripslashes($voornaam);} $_SESSION["SQL"] .= "where voornaam like \"%".$voornaam."%\"";$zoektermgegeven = True;}else{ $voornaam=$_POST["voornaam"]; if(isset($voornaam)){$voornaam=htmlspecialchars($voornaam); $voornaam=stripslashes($voornaam);} $_SESSION["SQL"] .= " AND voornaam like \"%".$voornaam."%\"";}}
	if(isset($_POST["naam"]) && $_POST["naam"] != ""){ if($zoektermgegeven == False){ $naam=$_POST["naam"]; if(isset($naam)){$naam=htmlspecialchars($naam); $naam=stripslashes($naam);}$_SESSION["SQL"] .= "where naam like \"%".$naam."%\"";$zoektermgegeven = True;}else{ $naam=$_POST["naam"]; if(isset($naam)){$naam=htmlspecialchars($naam); $naam=stripslashes($naam);}$_SESSION["SQL"] .= " AND naam like \"%".$naam."%\"";}}
	if(isset($_POST["email"]) && $_POST["email"] != ""){ if($zoektermgegeven == False){$_SESSION["SQL"] .= "where email like \"%".$_POST["email"]."%\"";$zoektermgegeven = True;}else{$_SESSION["SQL"] .= " AND email like \"%".$_POST["email"]."%\"";}}
	if(isset($_POST["adres"]) && $_POST["adres"] != ""){ if($zoektermgegeven == False){$adres=$_POST["adres"]; if(isset($adres)){$adres=htmlspecialchars($adres); $adres=stripslashes($adres);} $_SESSION["SQL"] .= "where adres like \"%".$adres."%\"";$zoektermgegeven = True;}else{$adres=$_POST["adres"]; if(isset($adres)){$adres=htmlspecialchars($adres); $adres=stripslashes($adres);} $_SESSION["SQL"] .= " AND adres like \"%".$adres."%\"";}}
	if(isset($_POST["gebruikersrol"]) && $_POST["gebruikersrol"] != ""){ if($zoektermgegeven == False){$_SESSION["SQL"] .= "where gebruikersrol like \"%".$_POST["gebruikersrol"]."\"%";$zoektermgegeven = True;}else{$_SESSION["SQL"] .= " AND gebruikersrol = \"%".$_POST["gebruikersrol"]."%\"";}}
header("location:zoekresultaat.php");
}
}
}
?>
<head>
	<meta charset="UTF-8">
	<title>Klant zoeken - Camping Le Passage</title>
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
		<div class="zoeken">
			<div>
				<div>
					<div class="zoeken">
						<h2>klant zoeken</h2>
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
										<td><label for="email"><span>E</span>-Mail:</label></td>
										<td><input type="text" id="email" name="email"></td>
									</tr>
									<tr>
										<td><label for="adres"><span>A</span>dres:</label></td>
										<td><input type="text" id="adres" name="adres"></td>
									</tr>
									<tr>
										<td><label for="gemeente"><span>G</span>emeente:</label></td>
										<td><input type="text" id="gemeente" name="gemeente"></td>
                                    </tr>
                                    <tr>
                                        <td><label for="postcode"><span>P</span>ostcode:</label></td>
										<td><input type="number" id="postcode" name="postcode"></td>
									</tr>
                                    <tr>
                                    	<td><label for="gebruikersrol"><span>G</span>ebruikersrol:</label></td>
                                        <td><select name="gebruikersrol" id="gebruikersrol"><option disabled selected value=""> -- selecteer een optie -- </option><option value="user">user</option>
  <option value="admin">admin</option></select></td>
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