<?php
header("Content-type: text/html");
$title = "Kundenseite";
$pizzas = array("Margherita", "Salami", "Hawaii");
?>
<!DOCTYPE html>
<html lang="de">
<?php
echo <<<EOT
<!-- HEREDOC! Hier steht HTML-Code -->
<head>
    <meta charset="UTF-8" />
    <title>$title</title>
</head>
EOT;
?>
<body>
<h1>Kundenseite</h1>
<ul>
    <?php
    for ($i = 0; $i < count($pizzas); $i++) {
        $pizza = $pizzas[$i];
        echo "<li>$pizza:";
        echo "<input type='radio' name='pizza$i' value='bestellt' checked>Bestellt";
        echo "<input type='radio' name='pizza$i' value='im_ofen'>Im Ofen";
        echo "<input type='radio' name='pizza$i' value='fertig'>Fertig";
        echo "<input type='radio' name='pizza$i' value='unterwegs'>Unterwegs";
        echo "<input type='radio' name='pizza$i' value='geliefert'>Geliefert";
        echo "</li>";
    }
    ?>
</ul>
<button onclick="location.href='bestellung.php'">Neue Bestellung</button>
</body>
</html>
