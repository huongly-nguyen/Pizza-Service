<?php
header("Content-type: text/html");
$title = "Bäckerseite";
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
<h1>Bäckerseite</h1>
<ul>
    <?php
    for ($i = 0; $i < count($pizzas); $i++) {
        echo "<li>$pizzas[$i]:";
        for ($status = 0; $status < 3; $status++) {
            $statusText = ($status == 0) ? "Bestellt" : (($status == 1) ? "Im Ofen" : "Fertig");
            $checked = ($status == 2) ? "checked" : "";
            echo "<input type='radio' name='pizza$i' value='" . ($status == 0 ? "bestellt" : ($status == 1 ? "im_ofen" : "fertig")) . "' $checked>$statusText";
        }
        echo "</li>";
    }
    ?>
</ul>
</body>
</html>
