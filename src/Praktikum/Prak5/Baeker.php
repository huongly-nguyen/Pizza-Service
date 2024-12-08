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
class Baeker extends Page
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
        // Fetch data for this view from the database
        $sql = "SELECT ordered_article_id, name, status, ordering_id
                FROM article 
                NATURAL JOIN ordered_article 
                NATURAL JOIN ordering 
                WHERE status <= 2 
                ORDER BY ordering_id, ordered_article_id ASC";

        $recordSet = $this->_database->query($sql);

//        if (!$recordSet) {
//            throw new Exception("Keine Bestellung in Datenbank vorhanden");
//        }

        $bestellungArray = [];

        while ($record = $recordSet->fetch_assoc()) {
            $bestellungArray[] = [
                "ordered_article_id" => $record["ordered_article_id"],
                "name" => $record["name"],
                "status" => $record["status"],
                "ordering_id" => $record["ordering_id"]
            ];
        }

        $recordSet->free();

        return $bestellungArray;

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
        $this->generatePageHeader('Bäckerseite','','Baecker.css'); //to do: set optional parameters
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

        echo '<h1>Bäcker</h1>';
        echo <<< HTML
        <div class="content-box">                  
        HTML;

        if (empty($data)) {
            echo '<p>Keine Bestellungen vorhanden.</p>';
        }
        else {
            $current_ordering_id = NULL;
            for ($i = 0; $i < count($data); $i++) {
                if ($current_ordering_id != $data[$i]['ordering_id']) {
                    echo '<hr>';
                    $current_ordering_id = $data[$i]['ordering_id'];
                    echo <<< HTML
                    <h2>Bestellung: {$data[$i]['ordering_id']}</h2>        
                    HTML;
                }
                $status = $data[$i]['status'];
                $isBestellt = ($status == 0) ? 'checked' : '';
                $isImOffen = ($status == 1) ? 'checked' : '';
                $isFertig = ($status == 2) ? 'checked' : '';
                echo <<< HTML
            
            <form id="pizza_status_form_{$data[$i]['ordered_article_id']}" action="Baeker.php" method="post">
                <p>{$data[$i]['name']}</p>
                <input type="radio"  name="status" onclick="document.forms['pizza_status_form_{$data[$i]['ordered_article_id']}'].submit();" value="bestellt" {$isBestellt}>
                <label for="html">bestellt</label>
                <input type="radio"  name="status" onclick="document.forms['pizza_status_form_{$data[$i]['ordered_article_id']}'].submit();" value="im_offen" {$isImOffen}>
                <label for="html">im Ofen</label>
                <input type="radio"  name="status" onclick="document.forms['pizza_status_form_{$data[$i]['ordered_article_id']}'].submit();" value="fertig" {$isFertig}>                    
                <label for="html">fertig</label>
                <input type="hidden" name="ordering_id" value="{$data[$i]['ordering_id']}">
                <input type="hidden" name="ordered_article_id" value="{$data[$i]['ordered_article_id']}">
            </form>
            
            HTML;
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
            $status = ($status == 'bestellt') ? 0 : (($status == 'im_offen') ? 1 : 2);
            $ordered_article_id = $this->_database->real_escape_string($_POST['ordered_article_id']);
            $query = "UPDATE `ordered_article` SET `status` = $status WHERE `ordered_article`.`ordered_article_id` = $ordered_article_id";
            $recordset = $this->_database->query($query);
            if (!$recordset) {
                throw new Exception("Abfrage fehlgeschlagen: " . $this->_database->error);
            }
            header("Location: Baeker.php", true, 303);
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
            $page = new Baeker();
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
Baeker::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >