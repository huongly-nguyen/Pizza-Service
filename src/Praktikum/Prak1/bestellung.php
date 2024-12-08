<?php
header("Content-type: text/html");
$title = "Bestellseite";
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
    <h1>Bestellung</h1>
    <h2>Speisekarte</h2>
    <?php
    $pizzen = array(
            "Pizza Salami" => array(
                    "preis" => 4.5,
                    "bild" => "bilder/pizza_salami.jpg"
            ),
            "Pizza Margherita" => array(
                    "preis" => 4.0,
                    "bild" => "bilder/pizza_margherita.jpg"
            ),
            "Pizza Hawaii" => array(
                    "preis" => 5.5,
                    "bild" => "bilder/pizza_hawaii.jpg"
            )
    );

    foreach($pizzen as $pizza => $pizzaDaten){
        echo<<<EOT
        <section>
        <img src="{$pizzaDaten["bild"]}" alt="" height="150" width="150">
        <h3>{$pizza}</h3>
        <p>Preis: {$pizzaDaten["preis"]}€</p>
        </section>
        EOT;
    }
    ?>
    <h2>Warenkorb</h2>
    <form action="baecker.php" method="post" accept-charset="UTF-8">
        <select name="bestellung[]" size="3" multiple tabindex="1">
            <?php
            foreach($pizzen as $pizza_name => $pizza_info) {
                echo <<<HTML
                <option value="{$pizza_name}">{$pizza_name}</option>
                HTML;
            }
            ?>
        </select>
        <p>Preis: </p>
        <p><input type="text" name="Adresse" placeholder="Ihre Adresse" value="" tabindex="2"/></p>
        <button type="reset" tabindex="3" accesskey="l">Alle löschen</button>
        <button type="button" tabindex="4" accesskey="a">Auswahl löschen</button>
        <button type="submit" tabindex="5" accesskey="b">Bestellen</button>
    </form>
</body>
</html>
