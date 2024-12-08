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
session_start();
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
class Kunde extends Page
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
        $pizza = array();
        $query = "SELECT * FROM `ordered_article`
                NATURAL JOIN `article` 
                NATURAL JOIN `ordering` 
                ORDER BY ordering_id, ordered_article_id ASC";
        $recordset = $this->_database->query($query);
        if (!$recordset) {
            throw new Exception("Abfrage fehlgeschlagen: " . $this->_database->error);
        }
        while ($record = $recordset->fetch_assoc()) {
            $pizza[] = [
                "ordered_article_id" => $record["ordered_article_id"],
                "name" => $record["name"],
                "status" => $record["status"],
                "ordering_id" => $record["ordering_id"],
                "address" => $record["address"]
            ];
        }
        $recordset->free();
        return $pizza;
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
        $this->generatePageHeader('Kundeseite'); //to do: set optional parameters
        // to do: output view of this page
        echo <<< HTML
        <nav>
        <a href="Uebersicht.php">Uebersicht</a>
        <a href="Bestellung.php">Bestellung</a>
        <a href="Kunde.php">Kunde</a>
        <a href="Baeker.php">Baeker</a>
        <a href="Fahrer.php">Fahrer</a>
        </nav>
        <h1>Kunde</h1>
        HTML;

        $current_ordering_id = 0;
        if(isset($_SESSION["ordering_id"])){
            $current_ordering_id = (int)$_SESSION["ordering_id"];
        }

        $addressDisplayed = False;
        for($i = 0; $i < count($data); $i++){
            if($data[$i]['ordering_id'] == $current_ordering_id){
                if (!$addressDisplayed){
                    $address = htmlspecialchars($data[$i]['address']);
                    echo <<< HTML
                        <h2>Bestellungsnummer: #{$data[$i]['ordering_id']}</h2>
                        <h3>Ihre Adresse: {$address}</h3>
                    HTML;
                    $addressDisplayed = True;
                }

                $status = $data[$i]['status'];
                $isBestellt = ($status == 0) ? 'checked' : '';
                $isImOffen = ($status == 1) ? 'checked' : '';
                $isFertig = ($status == 2) ? 'checked' : '';
                $isUnterwegs = ($status == 3) ? 'checked' : '';
                $isGeliefert = ($status == 4) ? 'checked' : '';

                echo <<< HTML
                    <form action="Kunde.php" method="POST">
                        <p>{$data[$i]['name']}</p>
                        <input type="radio" name="status_{$data[$i]['ordered_article_id']}" value="bestellt" {$isBestellt} disabled>
                        <label for="html">bestellt</label> 
                        <input type="radio" name="order_status_{$data[$i]['ordered_article_id']}" value="im_offen" {$isImOffen} disabled>
                        <label for="html">im Offen</label>
                        <input type="radio" name="order_status_{$data[$i]['ordered_article_id']}" value="fertig" {$isFertig} disabled>                    
                        <label for="html">fertig</label>
                        <input type="radio" name="order_status_{$data[$i]['ordered_article_id']}" value="unterwegs" {$isUnterwegs} disabled>                    
                        <label for="html">unterweg</label>
                        <input type="radio" name="order_status_{$data[$i]['ordered_article_id']}" value="geliefert" {$isGeliefert} disabled>                    
                        <label for="html">geliefert</label>
                        <input type="hidden" name="ordering_id" value="{$data[$i]['ordering_id']}">
                        <input type="hidden" name="ordered_article_id" value="{$data[$i]['ordered_article_id']}">
                    </form>
                HTML;
            }
        }

        echo <<< HTML
            <form action="Bestellung.php" method="POST">
                <button type="submit">Neue Bestellung</button>
            </form>
        HTML;


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
        try {
            $page = new Kunde();
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
Kunde::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >