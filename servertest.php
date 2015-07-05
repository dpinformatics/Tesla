<?php
/**
 * Created by PhpStorm.
 * User: Diego
 * Date: 1/07/2015
 * Time: 15:06
 */

// let's bootstrap our application.
include_once('includes/bootstrap.inc.php');


echo "<br>done<br>";
exit();




// trip
$t1 = new trip();
//$t1->att("statusid",1);
$t1->att("name", "Thuis - La Treve - via Auxerre");
$t1->att("date", mktime(0 ,0, 0, 7, 9, 2015));
$t1->att("theoreticalstarttime", mktime(2, 0, 0));

$t1->save();


// waypoints

$wp = new waypoint();
$wp->att("tripid", 6);
$wp->att("destination", "Thuis");
$wp->att("typeid", 1);
$wp->att("statusid", 3);
$wp->att("theoreticaldistance", null);
$wp->att("theoreticaltypical", null);
$wp->att("theoreticalconsumption", null);
$wp->att("theoreticaldrivetime", null);
$wp->att("theoreticalchargetime", null);
$wp->att("theoreticalchargeneeded", 370);
$wp->save();
echo "Waypoint ".$wp->att("destination")." (id : ".$wp->att("objid").") saved<br>";

$wp = new waypoint();
$wp->att("tripid", 6);
$wp->att("destination", "SuC Chalons-en-Champagne");
$wp->att("typeid", 3);
$wp->att("statusid", 0);
$wp->att("theoreticaldistance", 329.8);
$wp->att("theoreticaltypical", 350);
$wp->att("theoreticalconsumption", 65.0);
$wp->att("theoreticaldrivetime", 240);
$wp->att("theoreticalchargetime", 25);
$wp->att("theoreticalchargeneeded", 210);
$wp->save();
echo "Waypoint ".$wp->att("destination")." (id : ".$wp->att("objid").") saved<br>";

$wp = new waypoint();
$wp->att("tripid", 6);
$wp->att("destination", "SuC Auxerre");
$wp->att("typeid", 3);
$wp->att("statusid", 0);
$wp->att("theoreticaldistance", 167.1);
$wp->att("theoreticaltypical", 192);
$wp->att("theoreticalconsumption", 36.0);
$wp->att("theoreticaldrivetime", 120);
$wp->att("theoreticalchargetime", 50);
$wp->att("theoreticalchargeneeded", 380);
$wp->save();
echo "Waypoint ".$wp->att("destination")." (id : ".$wp->att("objid").") saved<br>";

$wp = new waypoint();
$wp->att("tripid", 6);
$wp->att("destination", "Auchan Aubiére");
$wp->att("typeid", 3);
$wp->att("statusid", 0);
$wp->att("theoreticaldistance", 324);
$wp->att("theoreticaltypical", 373);
$wp->att("theoreticalconsumption", 70.0);
$wp->att("theoreticaldrivetime", 220);
$wp->att("theoreticalchargetime", 150);
$wp->att("theoreticalchargeneeded", 380);
$wp->save();
echo "Waypoint ".$wp->att("destination")." (id : ".$wp->att("objid").") saved<br>";

$wp = new waypoint();
$wp->att("tripid", 6);
$wp->att("destination", "La Trêve");
$wp->att("typeid", 2);
$wp->att("statusid", 0);
$wp->att("theoreticaldistance", 295.5);
$wp->att("theoreticaltypical", 362);
$wp->att("theoreticalconsumption", 67.0);
$wp->att("theoreticaldrivetime", 190);
$wp->att("theoreticalchargetime", null);
$wp->att("theoreticalchargeneeded", null);
$wp->save();
echo "Waypoint ".$wp->att("destination")." (id : ".$wp->att("objid").") saved<br>";




// tripstatus
/*
$wps1 = new waypointstatus();
$wps1->att("objid", 0);
$wps1->att("name_en", "Driving");
$wps1->save();
*/
$wps2 = new waypointstatus();
$wps2->att("name_en", "Arrived");
$wps2->save();

$wps3 = new waypointstatus();
$wps3->att("name_en", "Charging");
$wps3->save();

$wps3 = new waypointstatus();
$wps3->att("name_en", "Charged");
$wps3->save();

$wps3 = new waypointstatus();
$wps3->att("name_en", "Left");
$wps3->save();

exit();


$ts = new tripstatus();
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
