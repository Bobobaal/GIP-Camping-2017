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
if(!isset($_SESSION["iklantid"])){
	$_SESSION["iklantid"] = "";
}
if(!isset($_SESSION["igebruikersrol"])){
	$_SESSION["igebruikersrol"] = "user";
}
if((isset($_GET["actie"]) && $_GET["actie"] == "reserveren") && (isset($_GET["plaatsreserveren"]))){
	$mysqli= new MySQLi("localhost","root","","dietergip");
	if(mysqli_connect_errno()){
		trigger_error("Fout bij verbinding:".$mysqli->error);
	}
	else{
	$sql= " SELECT plaatsid from tblplaatsen
			WHERE plaatsid = ?";
	if($stmt=$mysqli->prepare($sql)){
		$stmt->bind_param("i",$plaatsid);
		$plaatsid = $_GET["plaatsreserveren"];
		if(!$stmt->execute()){
			echo "het uitvoeren van de query is mislukt:";
		}
		else {
		$stmt->bind_result($plaatsid);
		while($stmt->fetch()){
			$_SESSION["plaatsid"] = $plaatsid;
		}
		if($_SESSION["iklantid"] != ""){
			header("location:reserveren.php");
		}
		if($_SESSION["iklantid"] == ""){
			header("location:login.php");
			$_SESSION["nietingelogd"] = true;
		}
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
	<title>Plaatsenoverzicht - Camping Le Passage</title>
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
                <li class="selected">
                	<a href="plaatsen.php"><span>P</span>laatsen</a>
                </li>
                <li <?php if($_SESSION["igebruikersrol"] == "user"){ echo "hidden";} ?>>
                	<a href="reserveringen.php"><span>R</span>eserveringen</a>
                </li>
                <li <?php if($_SESSION["iklantid"] == ""){ echo "hidden";} ?>>
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
		<div class="plaatsen">
			<div>
				<div>
					<div class="plaatsen">
						<h2>Plaatsenoverzicht</h2>
							<div>
                       
                        <p><img src="images/plattegrondnummers.jpg" alt="" width="797" height="722" usemap="#Map" border="0"/>
                          <map name="Map">
                            <area shape="poly" coords="54,484,65,518,110,525,116,491,54,483" href="#1">
                            <area shape="poly" coords="60,437,124,451,116,490,57,485" href="#2">
                            <area shape="poly" coords="60,404,59,437,125,450,131,415" href="#3">
                            <area shape="poly" coords="65,370,136,383,131,416,64,403" href="#4">
                            <area shape="poly" coords="70,337,143,347,136,383,67,370" href="#5">
                            <area shape="poly" coords="70,303,71,337,141,347,147,316" href="#6">
                            <area shape="poly" coords="80,270,72,302,150,316,157,282" href="#7">
                            <area shape="poly" coords="87,236,80,269,154,282,161,247" href="#8">
                            <area shape="poly" coords="92,200,166,212,163,246,86,236" href="#9">
                            <area shape="poly" coords="98,169,92,199,166,213,174,180" href="#10">
                            <area shape="poly" coords="105,135,98,168,173,180,178,151" href="#11">
                            <area shape="poly" coords="113,100,184,112,180,151,106,134" href="#12">
                            <area shape="poly" coords="155,350,206,348,204,394,155,397" href="#13">
                            <area shape="poly" coords="207,346,205,394,246,392,246,346" href="#14">
                            <area shape="poly" coords="248,345,284,343,286,390,247,391" href="#15">
                            <area shape="poly" coords="284,341,324,339,325,387,288,390" href="#16">
                            <area shape="poly" coords="325,339,326,387,366,386,362,337" href="#17">
                            <area shape="poly" coords="364,337,413,335,414,381,367,385" href="#18">
                            <area shape="poly" coords="163,294,217,293,216,333,162,336" href="#19">
                            <area shape="poly" coords="219,293,268,293,269,329,218,331" href="#20">
                            <area shape="poly" coords="269,293,320,292,320,328,271,328" href="#21">
                            <area shape="poly" coords="321,290,373,291,375,323,319,328" href="#22">
                            <area shape="poly" coords="375,251,376,325,414,324,413,248" href="#23">
                            <area shape="poly" coords="175,260,166,294,226,292,227,256" href="#24">
                            <area shape="poly" coords="228,257,280,253,280,293,227,294" href="#25">
                            <area shape="poly" coords="279,256,332,253,332,292,282,293" href="#26">
                            <area shape="poly" coords="332,252,376,248,373,289,330,290" href="#27">
                            <area shape="poly" coords="181,209,227,209,227,245,178,244" href="#28">
                            <area shape="poly" coords="228,210,286,209,286,244,227,246" href="#29">
                            <area shape="poly" coords="197,172,180,210,232,209,234,172" href="#30">
                            <area shape="poly" coords="235,171,233,210,286,208,286,168" href="#31">
                            <area shape="poly" coords="287,167,287,209,338,209,334,167" href="#32">
                            <area shape="poly" coords="366,164,395,162,395,241,366,244" href="#33">
                            <area shape="poly" coords="202,121,192,156,245,156,248,120" href="#34">
                            <area shape="poly" coords="248,122,302,119,302,156,246,157" href="#35">
                            <area shape="poly" coords="302,119,355,120,355,154,302,154" href="#36">
                            <area shape="poly" coords="356,120,411,118,411,151,357,152" href="#37">
                            <area shape="poly" coords="200,120,209,82,267,82,266,121" href="#38">
                            <area shape="poly" coords="268,83,320,81,322,119,268,120" href="#39">
                            <area shape="poly" coords="414,336,456,332,458,379,413,382" href="#40">
                            <area shape="poly" coords="458,332,492,331,492,376,460,380" href="#41">
                            <area shape="poly" coords="493,332,525,330,528,373,493,376" href="#42">
                            <area shape="poly" coords="413,286,460,284,462,319,415,323" href="#43">
                            <area shape="poly" coords="460,285,505,280,506,315,461,319" href="#44">
                            <area shape="poly" coords="414,248,457,246,458,284,414,286" href="#45">
                            <area shape="poly" coords="460,248,458,283,506,280,503,240" href="#46">
                            <area shape="poly" coords="414,201,413,238,459,235,456,198" href="#47">
                            <area shape="poly" coords="456,197,497,196,502,230,457,236" href="#48">
                            <area shape="poly" coords="411,160,413,201,457,199,455,160" href="#49">
                            <area shape="poly" coords="457,159,494,156,497,197,458,199" href="#50">
                          </map>
                        </p>
                        <table width="780px">
                        <tr>
                            <th>Plaats Nr.</th>
                            <th>Omschrijving</th>
                         </tr>
							<?php
							$mysqli= new MySQLi("localhost","root","","dietergip");
							if(mysqli_connect_errno()){
								trigger_error("Fout bij verbinding:".$mysqli->error);
							}
							else{
								$sql="select * from tblplaatsen";
								if($stmt = $mysqli->prepare($sql))
								{
								if(!$stmt->execute())
								{
									echo "Het uitvoeren van de query is mislukt:".$stmt->error."in query:".$sql;
								}
								else{
									$stmt->bind_result($plaatsid ,$omschrijving, $gekochte_caravan);
									while($stmt->fetch()){
										$plaatsnr=$plaatsid;
										echo "<tr>
												<td id=".$plaatsid.">".$plaatsid."</td>
												<td>".$omschrijving."</td>";
										if($gekochte_caravan == 0){ echo	"<td><a href =".$_SERVER["PHP_SELF"]."?actie=reserveren&plaatsreserveren=".$plaatsnr."><span>R</span>eserveren</a</td>
											  </tr>";}else{echo "<td><span>N</span>iet reserveerbaar</td>";}
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