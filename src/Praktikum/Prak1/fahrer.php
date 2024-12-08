<?php
header("Content-type: text/html");
$title = "Fahrerseite";
$deliveries = array(
    array("name" => "Schulz", "address" => "Kasinostr. 5"),
    array("name" => "Muller", "address" => "Rheinstr. 11")
);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $title; ?></title>
</head>
<body>
<h1>Fahrerseite</h1>
<?php
for ($i = 0; $i < count($deliveries); $i++) {
    $name = $deliveries[$i]["name"];
    $address = $deliveries[$i]["address"];
    echo "<p>$name, $address</p>";
    echo "<label for='pizzaStatus$i'>Status:</label>";
    echo "<select id='pizzaStatus$i' name='pizzaStatus'>";
    echo "<option value='fertig'>Bestellt</option>";
    echo "<option value='unterwegs'>Im Ofen</option>";
    echo "<option value='geliefert' selected>Fertig</option>";
    echo "</select><br><br>";
}
?>
</body>
</html>
