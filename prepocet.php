<?php

$vaha = null; $davkovaniLeku = null; $koncentraceLeku = null; $mnozstviMedia = null; $chyby = []; $vseOK = false;
$vysledekJedna = 0; $vysledekDve = 0; $vysledekEnd = 0;
$seznamDavek = []; $seznamMnozstviM = [];

for($cislo=1;$cislo<=50;$cislo++) {
	$seznamDavek["davka$cislo"] = $cislo;
}

$seznamKoncentraci = [
	"koncentrace1" => "1",
	"koncentrace5" => "5",
	"koncentrace10" => "10",
	"koncentrace25" => "25",
	"koncentrace50" => "50",
];

$cislo=50;
while($cislo <= 250) {
	$seznamMnozstviM["mnozstviM$cislo"] = $cislo;
	$cislo = $cislo+50;
}

if (array_key_exists("odeslat", $_POST)) {
	$vaha = $_POST["vaha"];
	$davkovaniLeku = $_POST["davkovaniLeku"];
	$koncentraceLeku = $_POST["koncentraceLeku"];
	$mnozstviMedia = $_POST["mnozstviMedia"];

		// kontrola chyb - jmeno:

		if ($vaha == "" || $davkovaniLeku == "" || $koncentraceLeku == "" || $mnozstviMedia == "") {
			$chyby["prazdne"] = "Všechna pole musí být zadána!!!";
		}

		// kontrola zda je vse v poradku:

		else if (count($chyby) == 0) {
			$vseOK = true;
		}

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ARO</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="shortcut icon" href="srdce.png" type="image/x-icon">
	<style>
		body form i.fa-house-medical {
			font-size: 28px;
			color: red;
		}

		body form i.fa-truck-medical {
			font-size: 28px;
			color: darkblue;
		}

		body form i.fa-bed-pulse {
			font-size: 28px;
		}

		body form .chyba {
		color: red;
		font-weight: bold;
		}

		body table.jedinaTabulka {
			border-color: red;
		}
	</style>
</head>
<body>

<?php

			if ($vseOK == false) {

		?>

				<form method="post">
					<br>
					<i class="fa-solid fa-truck-medical"></i>
					<i class='fa-solid fa-house-medical'></i>
					<i class="fa-solid fa-bed-pulse"></i>
					<br><br><br>
						Váha pacienta: <input type="number" name="vaha" value="<?php echo $vaha ?>"> [jednotky: kilogram]<br><br>
						Dávkování léku: <select name="davkovaniLeku"> <!-- u select nelze dat value takze se resi u option pomoci selected jako obdoby value -->
									<option value="">vyberte</option>
									<?php
									foreach($seznamDavek as $idDavka => $nazevDavky) {
										$selected = "";
										if ($idDavka == $davkovaniLeku) {
											$selected = "selected";
										}
										echo "<option value=$idDavka $selected>$nazevDavky</option>";
									}
									?>
								</select> [jednotky: mililitry za hodinu na vahu pacienta]<br><br>

						Koncentrace léku - lék:
								<select name="koncentraceLeku"> <!-- u select nelze dat value takze se resi u option pomoci selected jako obdoby value -->
									<option value="">vyberte</option>
									<?php
									foreach($seznamKoncentraci as $idKoncentrace => $nazevKoncentrace) {
										$selected = "";
										if ($idKoncentrace == $koncentraceLeku) {
											$selected = "selected";
										}
										echo "<option value=$idKoncentrace $selected>$nazevKoncentrace</option>";
									}
									?>
								</select> [jednotky: miligramy]<br><br>
						
						Koncentrace léku - médium:
								<select name="mnozstviMedia"> <!-- u select nelze dat value takze se resi u option pomoci selected jako obdoby value -->
									<option value="">vyberte</option>
									<?php
									foreach($seznamMnozstviM as $idMnozstviM => $nazevMnozstviM) {
										$selected = "";
										if ($idMnozstviM == $mnozstviMedia) {
											$selected = "selected";
										}
										echo "<option value=$idMnozstviM $selected>$nazevMnozstviM</option>";
									}
									?>
								</select> [jednotky: mililitry]<br><br>

								<div class="chyba">
										<?php
										if (array_key_exists("prazdne", $chyby)) {
											echo $chyby["prazdne"];
										}
										?>
								</div><br>

								<button name="odeslat">Odeslat a přepočítat  <i class="fa-solid fa-arrow-right"></i></button>
				</form>
		<?php
			}

			else {

			echo "<table border=1 class=jedinaTabulka>";
			echo "<tr> <th>Váha pacienta</th> <td>$vaha</td> </tr>";
			echo "<tr> <th>Dávkování léku</th> <td>{$seznamDavek[$davkovaniLeku]}</td> </tr>";
			echo "<tr> <th>Koncentrace léku - lék</th> <td>{$seznamKoncentraci[$koncentraceLeku]}</td> </tr>";
			echo "<tr> <th>Koncentrace léku - médium</th> <td>{$seznamMnozstviM[$mnozstviMedia]}</td> </tr>";
			echo "</table>";
			echo "<br>";
			echo "<h3> Vzhledem k zadaným parametrům (tabulka) je přepočet:</h3>";

			$vysledekJedna = $seznamKoncentraci[$koncentraceLeku] / $seznamMnozstviM[$mnozstviMedia];
			$vysledekDve = $seznamDavek[$davkovaniLeku] * $vysledekJedna / 60;
			$vysledekEnd = $vysledekDve * 1000 / $vaha;
			echo "<h1>".number_format($vysledekEnd, 5, ",", " ")."</h1> 
			[jednotky: mikrogramů za minutu na 1 kg pacientovi vahy ($vaha kg)]";


			echo "<br><br><br><br>";
			echo "<form><button>ZPĚT</button></form>";

			}
			
		
		?>

<?php
/*  	vim: vaha pacienta 80 kg
		vim: davkovani 6ml media za hodinu (na celkovou vahu pacienta)
		vim: lek v koncentraci 5mg leku v 50ml media

		1. otazka: kolik mg leku v 1 ml media = prepocet = 5mg/50 = 0,1 miligramu leku v 1 ml media

		2. otazka: davkovani v miligramech za minutu = prepocet = 6 ml * 0,1 mg / 60 minutami = 0,01 miligramů za 1 minutu

		3. otazka: davkovani v mikrogramech za minutu na 1 kg u 80 kg pacienta = prepocet = 0,01 * 1000 / 80 = 
		= davkovani 0,125 mikrogramů léku za 1 minutu na 1 kg u 80 kg pacienta
 */
?>




</body>
</html>