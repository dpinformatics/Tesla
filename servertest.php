<?php
/**
 * Created by PhpStorm.
 * User: Diego
 * Date: 1/07/2015
 * Time: 15:06
 */

// let's bootstrap our application.
include_once('includes/bootstrap.inc.php');


// trip

echo("09-07-2015");
echo("02:00:00");

$t1 = new trip();
//$t1->att("statusid",1);
$t1->att("name", "Thuis - La Trêve via Metz");
$t1->att("date", mktime(0 ,0, 0, 7, 9, 2015));
$t1->att("theoreticalstarttime", mktime(2, 0, 0));

$t1->save();


exit();



// tripstatus
$ts1 = new tripstatus();
$ts1->att("name_en", "Scheduled");
$ts1->save();

$ts2 = new tripstatus();
$ts2->att("name_en", "Started");
$ts2->save();

$ts3 = new tripstatus();
$ts3->att("name_en", "Ended");
$ts3->save();


//$ts->retrieve(4);
$ts->att("objid", 4);

$ts->att("name_en", "updated after explicite object id");
$ts->save();

echo TripStatus::STATUS_SCHEDULED;

//sleep(5);
/*
    $ts->att("name_en", "Started");
    $ts->save();
*/
