<?php
    // let's bootstrap this stuff!
    // include files we need...
    include_once('includes/autoload.inc.php');
    include_once('config.inc.php');


    // initialise connection to the database...
    include_once('classes/DB.class.php');
    DB::connect("mysqlt", TESLA_DB_HOST, TESLA_DB_USER, TESLA_DB_PASS, TESLA_DB_NAME);
