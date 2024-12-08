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
class Bestellung extends Page
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
        $sql = "SELECT* FROM article";
        $recordSet = $this->_database->query($sql);
        if(!$recordSet) {
            throw new Exception("keine Article in der Datenbank");
        }
        $article_List = array();

        while ($record = $recordSet->fetch_assoc()) {
            $article_id = $record["article_id"];
            $name = $record["name"];
            $picture = $record["picture"];
            $price = $record["price"];
            $article_List[] = array(
                "article_id" => $article_id,
                "name" => $name,
                "picture" => $picture,
                "price" => $price
            );
        }

        $recordSet->free();
        return $article_List;
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
        $articleList = $this->getViewData(); //NOSONAR ignore unused $data
        $this->generatePageHeader('Bestellungseite','','Bestellung.css'); //to do: set optional parameters
        // to do: output view of this page

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

        echo <<< HTML
            <h1>Bestellung</h1>            
        HTML;

        echo <<< HTML
        <div class="content-box">   
            <div class="left-column">  
            <h2>Speisekarte</h2>             
        HTML;

        foreach ($articleList as $article) {
            $article_name = htmlspecialchars($article["name"]);
            $article_price = htmlspecialchars($article["price"]);
            echo <<<HTML
            <div class="article-item">

            <img
                id="article_{$article['article_id']}"
                class="article_image"
                width="150"
                height="100"
                src=img/pizza_img.jpg
                alt="" 
                title="$article[name]"
                data-article-id="{$article['article_id']}"
                data-article-name="{$article['name']}"
                data-article-price="{$article['price']}"
                onclick="addPizza.call(this)"
            >
            <div class="article-details">
                <p>{$article_name}</p>
                <br>
                <p>{$article_price}€</p>
            </div>
        </div>
        HTML;
        }
        echo <<<EOT
        </div>
        <div class="right-column">
        <h2>Warenkorb</h2>
        
        <form action="Bestellung.php" method="post" accept-charset="UTF-8">
            <select id="warenkorb" name="warenkorb[]" size="3" multiple tabindex="1"></select>
            <p id="totalPrice">Gesamtpreis: 0.00€</p>
            <p><input type="text" id="addressInput" name="address" placeholder="Ihre Adresse" value="" tabindex="2"/></p>
            <button type="reset" id="resetButton" onclick="deleteAll()" tabindex="3" accesskey="l">Alle löschen</button>
            <button type="button" id="deleteSelectedButton" onclick="deleteSelection()" tabindex="4" accesskey="a">Auswahl löschen</button>
            <button type="submit" id="bestellenButton" tabindex="5" accesskey="b">Bestellen</button>
        </form>
        </div>
        </div>
        EOT;

        echo <<< JS
        <script src="warenkorb.js"></script>
        JS;

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


        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['warenkorb'])) {
            //erase unnecessary blanks
            $address = trim($_POST['address']);

            //sanitize input
            $address = $this->_database->real_escape_string($_POST['address']);

            // Insert into "ordering" table
            $insertOrderingSQL = "INSERT INTO ordering (address) VALUES ('$address')";
            $this->_database->query($insertOrderingSQL);

            // Get the ordering_id of the inserted row
            $orderingId = $this->_database->insert_id;

            $_SESSION["ordering_id"] = $orderingId;

            // Insert into "ordered_article" table for each selected article in the warenkorb
            foreach ($_POST['warenkorb'] as $articleId) {
                // Insert into "ordered_article" table with auto-incremented ordering_id
                $articleId = $this->_database->real_escape_string($articleId);
                $insertOrderedArticleSQL = "INSERT INTO ordered_article (ordering_id, article_id, status) VALUES ('$orderingId', '$articleId', 0)";
                $this->_database->query($insertOrderedArticleSQL);
            }

            // PRG PATTERN
            header('Location: Bestellung.php');
            exit();
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
            $page = new Bestellung();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Bestellung::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >