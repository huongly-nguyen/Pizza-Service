<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

/**
 * Class PageTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7.4
 *
 * @file     PageTemplate.php
 * @package  Page Templates
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @version  3.1
 */

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class Fahrer extends Page
{
    // to do: declare reference variables for members
    // representing substructures/blocks

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So, the database connection is established.
     * @throws Exception
     */
    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Cleans up whatever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So, the database connection is closed.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is returned in an array e.g. as associative array.
     * @return array An array containing the requested data.
     * This may be a normal array, an empty array or an associative array.
     */
    protected function getViewData():array
    {
        // to do: fetch data for this view from the database
        // to do: return array containing data
        $sql = "SELECT ordered_article_id,ordering_id, address, status, name, price
                FROM `ordered_article`
                NATURAL JOIN `article` 
                NATURAL JOIN `ordering` 
                WHERE ordered_article.status >=2
                ORDER BY ordering_id, ordered_article_id ASC" ;

        $recordset = $this->_database->query($sql);
//        if (!$recordset) {
//            throw new Exception("Abfrage fehlgeschlagen: " . $this->_database->error);
//        }

        $result = array();
        while ($record = $recordset->fetch_assoc()) {
            $result[] = [
                "ordered_article_id" => $record["ordered_article_id"],
                "ordering_id" => $record["ordering_id"],
                "address" => $record["address"],
                "status" => $record["status"],
                "name" => $record["name"],
                "price" => $record["price"]
            ];
        }

        $recordset->free();
        return $result;
    }

    /**
     * First the required data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if available- the content of
     * all views contained is generated.
     * Finally, the footer is added.
     * @return void
     */
    protected function generateView():void
    {
        $data = $this->getViewData(); //NOSONAR ignore unused $data
        $this->generatePageHeader('Fahrerseite','','Fahrer.css'); //to do: set optional parameters
        // to do: output view of this page

        echo '<meta http-equiv="refresh" content="10">';

        echo <<< HTML
        <div class="navbar">
            <ul>
                <li><a href="Uebersicht.php">Übersicht</a></li>
                <li><a href="Bestellung.php">Bestellung</a></li>
                <li><a href="Baeker.php">Baeker</a></li>
                <li><a href="Kunde.php">Kunde</a></li>
                <li><a href="Fahrer.php">Fahrer</a></li>
            </ul>
        </div>
        HTML;

        echo '<h1>Fahrer</h1>';
        echo <<< HTML
        <div class="content-box">                  
        HTML;

        if (empty($data)) {
            echo '<p>Keine Bestellungen vorhanden.</p>';
        }
        else {
            $current_order_id = NULL;
            $totalPrice = 0;
            for ($i = 0; $i < count($data); $i++) {
                if ($current_order_id != $data[$i]['ordering_id']) {
                    if ($current_order_id !== NULL) { //Check if this is the first order
                        // Close the previous order section and display total price
                        echo "</form><p>Gesamtpreis: " . htmlspecialchars("{$totalPrice} €") . "</p>";
                    }
                    echo '<hr>';
                    $current_order_id = htmlspecialchars($data[$i]['ordering_id']);
                    $current_address = htmlspecialchars($data[$i]['address']);
                    $totalPrice = 0;
                    echo <<< HTML
                    <h2>Bestellung: {$current_order_id}</h2>
                    <h3>Adresse: {$current_address}</h3>
                    HTML;
                }
                $totalPrice += $data[$i]['price'];
                $status = $data[$i]['status'];
                $isFertig = ($status == 2) ? 'checked' : '';
                $isUnterwegs = ($status == 3) ? 'checked' : '';
                $isGeliefert = ($status == 4) ? 'checked' : '';

                echo <<< HTML
            <form id="lieferung_{$data[$i]['ordered_article_id']}" action="Fahrer.php" method="post">
                <p>{$data[$i]['name']} (Preis: {$data[$i]['price']} €)</p>               
                <input type="radio" name="status" onclick="document.forms['lieferung_{$data[$i]['ordered_article_id']}'].submit();" value="fertig" {$isFertig}>
                <label for="html">fertig</label>
                <input type="radio" name="status" onclick="document.forms['lieferung_{$data[$i]['ordered_article_id']}'].submit();" value="unterwegs" {$isUnterwegs}>
                <label for="html">unterwegs</label>
                <input type="radio" name="status" onclick="document.forms['lieferung_{$data[$i]['ordered_article_id']}'].submit();" value="geliefert" {$isGeliefert}>
                <label for="html">geliefert</label>
                <input type="hidden" name="ordering_id" value="{$data[$i]['ordering_id']}">
                <input type="hidden" name="ordered_article_id" value="{$data[$i]['ordered_article_id']}">
            </form>   
            
            HTML;
            }
            if ($current_order_id !== NULL) { //Display price of last order
                echo "<p>Gesamtpreis: " . htmlspecialchars("{$totalPrice} €") . "</p>";
            }
            echo '</div>';
        }
        $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST.
     * If this page is supposed to do something with submitted
     * data do it here.
     * @return void
     */
    protected function processReceivedData():void
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members

        if (isset($_POST['ordering_id']) && isset($_POST['status']) && isset($_POST['ordered_article_id'])) {
            $status = $_POST['status'];
            $status = ($status == 'fertig') ? 2 : (($status == 'unterwegs') ? 3 : 4);
            $ordered_article_id = $this->_database->real_escape_string($_POST['ordered_article_id']);
            $query = "UPDATE `ordered_article` SET `status` = '$status' WHERE `ordered_article`.`ordered_article_id` = $ordered_article_id";
            $recordset = $this->_database->query($query);
            if (!$recordset) {
                throw new Exception("Abfrage fehlgeschlagen: " . $this->_database->error);
            }
            header("Location: fahrer.php", true, 303);
            die();
        }
    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     * @return void
     */
    public static function main():void
    {
        session_start();
        try {
            $page = new Fahrer();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Fahrer::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >