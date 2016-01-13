<?php
/**
 * Created by PhpStorm.
 * User: Diego
 * Date: 1/07/2015
 * Time: 15:06
 */

// let's bootstrap our application.
include_once('includes/bootstrap.inc.php');

// routes :
//          1   Thuis - La Treve - via autoroute du soleil
//          2   La Treve - Nuits-Saint-Georges via Nimes
//          3   La Treve - Nuits-Saint-Georges via Orange
//          4   Nuits-Saint-Georges - Brecht (thuis)
//          51  La Treve - Auxerre via Orange
//              1   La Trêve - Orange               183.9   203     38      2:30
//              2   Orange - Vienne                 183.2   220     41      1:50
//              3   Vienne - Beaune                 173.9   200     37      1:55
//              4   Beaune - Auxerre                153.2   188     35      1:40
//          52  La Treve - Auxerre via Valence
//              1   La Trêve - Valence              272.6   313     58      3:15
//              2   Valence - Beaune                254.8   295     55      2:50
//              3   Beaune - Auxerre                153.2   188     35      1:40
//          6   Auxerre - Brecht (thuis)
//CreateRoute(1, '2015-12-23 02:30');
//CreateRoute(2, '2016-01-02 12:00');
//CreateRoute(3, '2016-01-02 12:00');
//CreateRoute(4, '2016-01-03 10:00');
//CreateRoute(51, '2016-01-02 10:00');
CreateRoute(52, '2016-01-02 10:00'); exit();
//CreateRoute(6, '2016-01-03 10:00');

echo 'TrpDetail for trip 10 :<br>===============<br> ';
echo json_encode(GetTripDetail(10));

echo '<br><br>';
echo 'TrpDetail for trip 14 :<br>===============<br> ';
echo json_encode(GetTripDetail(14));

//echo CalculateChargeTime(50, 350);

//echo CalculateChargeNeeded(350);

/*
 $startdate = '2015-10-25 10:30';
$uu = substr($startdate, 11,2);
$nn = substr($startdate, 14,2);
$dd = substr($startdate, 8, 2);
$mm = substr($startdate, 5, 2);
$jj = substr($startdate, 0, 4);

echo $jj.'/'.$mm.'/'.$dd.'   '.$uu.'.'.$nn;
*/

/*
for ($x = 10; $x <= 380; $x = $x + 10) {
    echo 'Typical '. $x.' km is '.CalculateEnergy($x) .' kWh <br>';
}
*/


exit();


function CalculateEnergy($typical)
{
    $kWh = floor($typical * 0.190);
    return $kWh;
}

function CalculateChargeNeeded($typical)
{
    $n = floor($typical * 1.1 / 10) * 10;
    $c = min(380, $n);
    return $c;
}

function CalculateChargeTime($from, $to)
{
    $max = 385;
    // xls function :  =ROUNDUP((LN(-(((C4)/$J$2)-1))/-LN(1,03830664))-(LN(-(((B4)/$J$2)-1))/-LN(1,03830664));0)
    $mt = log(-(($to / $max) - 1)) / - log(1.03830664);
    $mf = log(-(($from / $max) - 1)) / - log(1.03830664);
    $m = ceil($mt - $mf);

    return $m;
}

function CreateRoute($route, $startdate){

// routes :
//          1   Thuis - La Treve - via autoroute du soleil
//          2   La Treve - Nuits-Saint-Georges via Nimes
//          3   La Treve - Nuits-Saint-Georges via Orange
//          4   Nuits-Saint-Georges - Brecht (thuis)
//          51  La Treve - Auxerre via Orange
//          52  La Treve - Auxerre via Valence
//          6   Auxerre - Brecht (thuis)
//
//$route = 4;

    // startdate = '2015-10-25 10:30'
    $uu = substr($startdate, 11,2);
    $nn = substr($startdate, 14,2);
    $dd = substr($startdate, 8, 2);
    $mm = substr($startdate, 5, 2);
    $jj = substr($startdate, 0, 4);

    echo 'Create New Trip<br>===============<br><br>';
    // trip
    $t1 = new trip();
    //$t1->att('statusid',1);
    if ($route == 1){
        $t1->att('name', 'Thuis - La Treve - via autoroute du soleil');
    }
    if ($route == 2){
        $t1->att('name', 'La Treve - Ibis Nuits Saint Georges - via Nimes');
    }
    if ($route == 3){
        $t1->att('name', 'La Treve - Ibis Nuits Saint Georges - via Orange');
    }
    if ($route == 4){
        $t1->att('name', 'Nuits-Saint-Georges - Brecht (thuis)');
    }
    if ($route == 51){
        $t1->att('name', 'La Treve - Auxerre (via Orange)');
    }
    if ($route == 52){
        $t1->att('name', 'La Treve - Auxerre (via Valence)');
    }
    if ($route == 6){
        $t1->att('name', 'Auxerre - Brecht (thuis)');
    }
    $t1->att('date', mktime($uu ,$nn, 0, $mm, $dd, $jj));
    $t1->att('car', '38855');
    $t1->save();
    $tripid = $t1->att('objid');
    echo 'Trip '.$t1->att('name').' (id : '.$t1->att('objid').') saved<br>';

    echo 'Create New Waypoints<br>====================<br><br>';
    // waypoints
    if ($route == 1) {
        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Brecht');
        $wp->att('typeid', 1);
        $wp->att('statusid', 3);
        $wp->att('theoreticaldistance', null);
        $wp->att('theoreticaltypical', null);
        $wp->att('theoreticalconsumption', null);
        $wp->att('theoreticaldrivetime', null);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(373));
        $wp->save();
        echo 'Waypoint ' . $wp->att('destination') . ' (id : ' . $wp->att('objid') . ') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Metz');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 322.5);
        $wp->att('theoreticaltypical', 373);
        $wp->att('theoreticalconsumption', 70.0);
        $wp->att('theoreticaldrivetime', 195);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(86));
        $wp->save();
        echo 'Waypoint ' . $wp->att('destination') . ' (id : ' . $wp->att('objid') . ') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Nancy');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 79.5);
        $wp->att('theoreticaltypical', 86);
        $wp->att('theoreticalconsumption', 16.0);
        $wp->att('theoreticaldrivetime', 60);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(287));
        $wp->save();
        echo 'Waypoint ' . $wp->att('destination') . ' (id : ' . $wp->att('objid') . ') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Nuits-Saint-Georges');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 229.6);
        $wp->att('theoreticaltypical', 287);
        $wp->att('theoreticalconsumption', 53.0);
        $wp->att('theoreticaldrivetime', 130);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(225));
        $wp->save();
        echo 'Waypoint ' . $wp->att('destination') . ' (id : ' . $wp->att('objid') . ') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Vienne');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 188.5);
        $wp->att('theoreticaltypical', 225);
        $wp->att('theoreticalconsumption', 42.0);
        $wp->att('theoreticaldrivetime', 115);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(289));
        $wp->save();
        echo 'Waypoint ' . $wp->att('destination') . ' (id : ' . $wp->att('objid') . ') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Nimes');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 234.1);
        $wp->att('theoreticaltypical', 289);
        $wp->att('theoreticalconsumption', 54.0);
        $wp->att('theoreticaldrivetime', 130);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(143));
        $wp->save();
        echo 'Waypoint ' . $wp->att('destination') . ' (id : ' . $wp->att('objid') . ') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'La Trêve, Véreilles');
        $wp->att('typeid', 2);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 124.0);
        $wp->att('theoreticaltypical', 143);
        $wp->att('theoreticalconsumption', 27.0);
        $wp->att('theoreticaldrivetime', 110);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', null);
        $wp->save();
        echo 'Waypoint ' . $wp->att('destination') . ' (id : ' . $wp->att('objid') . ') saved<br>';
    }

    if ($route == 2){
        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'La Trêve');
        $wp->att('typeid', 1);
        $wp->att('statusid', 3);
        $wp->att('theoreticaldistance', null);
        $wp->att('theoreticaltypical', null);
        $wp->att('theoreticalconsumption', null);
        $wp->att('theoreticaldrivetime', null);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(135));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Nimes');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 124.8);
        $wp->att('theoreticaltypical', 135);
        $wp->att('theoreticalconsumption', 25.0);
        $wp->att('theoreticaldrivetime', 105);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(307));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Vienne');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 234.1);
        $wp->att('theoreticaltypical', 307);
        $wp->att('theoreticalconsumption', 57.0);
        $wp->att('theoreticaldrivetime', 135);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(237));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Nuits-Saint-Georges (Ibis)');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 189.9);
        $wp->att('theoreticaltypical', 237);
        $wp->att('theoreticalconsumption', 44.0);
        $wp->att('theoreticaldrivetime', 110);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(350));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
    }

    if ($route == 3){
        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'La Trêve');
        $wp->att('typeid', 1);
        $wp->att('statusid', 3);
        $wp->att('theoreticaldistance', null);
        $wp->att('theoreticaltypical', null);
        $wp->att('theoreticalconsumption', null);
        $wp->att('theoreticaldrivetime', null);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(200));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Orange');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 186.7);
        $wp->att('theoreticaltypical', 200);
        $wp->att('theoreticalconsumption', 37.0);
        $wp->att('theoreticaldrivetime', 135);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(220));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Vienne');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 184.5);
        $wp->att('theoreticaltypical', 220);
        $wp->att('theoreticalconsumption', 42.0);
        $wp->att('theoreticaldrivetime', 120);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(230));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Nuits-Saint-Georges (Ibis)');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 190.2);
        $wp->att('theoreticaltypical', 230);
        $wp->att('theoreticalconsumption', 44.0);
        $wp->att('theoreticaldrivetime', 110);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(350));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
    }

    if ($route == 4){
        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Nuits-Saint-Georges (Ibis)');
        $wp->att('typeid', 1);
        $wp->att('statusid', 3);
        $wp->att('theoreticaldistance', null);
        $wp->att('theoreticaltypical', null);
        $wp->att('theoreticalconsumption', null);
        $wp->att('theoreticaldrivetime', null);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(350));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Châlon-en-Champagne');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 287.3);
        $wp->att('theoreticaltypical', 350);
        $wp->att('theoreticalconsumption', 65.0);
        $wp->att('theoreticaldrivetime', 150);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(250));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Nivelles-Sud');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 227.8);
        $wp->att('theoreticaltypical', 250);
        $wp->att('theoreticalconsumption', 44.5);
        $wp->att('theoreticaldrivetime', 170);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(120));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Brecht (thuis)');
        $wp->att('typeid', 2);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 103.4);
        $wp->att('theoreticaltypical', 120);
        $wp->att('theoreticalconsumption', 21.0);
        $wp->att('theoreticaldrivetime', 90);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', null);
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
    }

    if ($route == 51){
        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'La Trêve');
        $wp->att('typeid', 1);
        $wp->att('statusid', 3);
        $wp->att('theoreticaldistance', null);
        $wp->att('theoreticaltypical', null);
        $wp->att('theoreticalconsumption', null);
        $wp->att('theoreticaldrivetime', null);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(210));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Orange');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 186.7);
        $wp->att('theoreticaltypical', 210);
        $wp->att('theoreticalconsumption', 40.0);
        $wp->att('theoreticaldrivetime', 150);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(240));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Vienne');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 184.5);
        $wp->att('theoreticaltypical', 240);
        $wp->att('theoreticalconsumption', 45.0);
        $wp->att('theoreticaldrivetime', 110);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(200));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Beaune');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 173.9);
        $wp->att('theoreticaltypical', 200);
        $wp->att('theoreticalconsumption', 37.0);
        $wp->att('theoreticaldrivetime', 110);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(188));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Auxerre (Novotel)');
        $wp->att('typeid', 2);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 153.2);
        $wp->att('theoreticaltypical', 188);
        $wp->att('theoreticalconsumption', 35.0);
        $wp->att('theoreticaldrivetime', 95);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', null);
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
    }

    if ($route == 52){
        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'La Trêve');
        $wp->att('typeid', 1);
        $wp->att('statusid', 3);
        $wp->att('theoreticaldistance', null);
        $wp->att('theoreticaltypical', null);
        $wp->att('theoreticalconsumption', null);
        $wp->att('theoreticaldrivetime', null);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(313));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Valence');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 272.6);
        $wp->att('theoreticaltypical', 313);
        $wp->att('theoreticalconsumption', 58.0);
        $wp->att('theoreticaldrivetime', 200);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(295));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Beaune');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 254.8);
        $wp->att('theoreticaltypical', 295);
        $wp->att('theoreticalconsumption', 55.0);
        $wp->att('theoreticaldrivetime', 170);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(188));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Auxerre (Novotel)');
        $wp->att('typeid', 2);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 153.2);
        $wp->att('theoreticaltypical', 188);
        $wp->att('theoreticalconsumption', 35.0);
        $wp->att('theoreticaldrivetime', 95);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', null);
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
    }

    if ($route == 6){
        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Suc Auxerre (Novotel)');
        $wp->att('typeid', 1);
        $wp->att('statusid', 3);
        $wp->att('theoreticaldistance', null);
        $wp->att('theoreticaltypical', null);
        $wp->att('theoreticalconsumption', null);
        $wp->att('theoreticaldrivetime', null);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(240));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Senlis');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 210.0);
        $wp->att('theoreticaltypical', 240);
        $wp->att('theoreticalconsumption', 42.0);
        $wp->att('theoreticaldrivetime', 145);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(200));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'SuC Lille');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 165.0);
        $wp->att('theoreticaltypical', 200);
        $wp->att('theoreticalconsumption', 35.0);
        $wp->att('theoreticaldrivetime', 100);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', CalculateChargeNeeded(200));
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

        $wp = new waypoint();
        $wp->att('tripid', $tripid);
        $wp->att('destination', 'Brecht (thuis)');
        $wp->att('typeid', 3);
        $wp->att('statusid', 0);
        $wp->att('theoreticaldistance', 160.0);
        $wp->att('theoreticaltypical', 200);
        $wp->att('theoreticalconsumption', 30.0);
        $wp->att('theoreticaldrivetime', 110);
        $wp->att('theoreticalchargetime', null);
        $wp->att('theoreticalchargeneeded', null);
        $wp->save();
        echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
    }

    echo '<br>done<br>';

}


function GetTripDetail($tripId)
{
//-----------------------------------------

    // selected trip
    $trip = new trip();
    $trip->retrieve($tripId);
    // get all waypoints for this trip
    $waypoint = new waypoint();
    $waypoints = $waypoint->getAllObjectsArray('tripid = '. DB::qstr($tripId), null, 'objid');

    /*
    $wpdistance = '';
    $totaldistance = 0;
    $wptypical = '';
    $totaltypical = 0;
    $wpconsumption = '';
    $totalconsumption = 0;
    $wpaverage = '';
    $wpdrivetime = '';
    $totaldrivetime = 0;
    $wpchargetime = '';
    $totalchargetime = 0;
    $startdrivetime = $trip->att('theoreticalstarttime');
    $wpchargeneeded = 0;
    $wpchargestarted = 0;
    $wparrivaltime = '';
    $wpdeparturetime = '';
    $wpchargestart = 0;
    */
    // declare and initialize all variables
    $wpnbr = -1;
    $statusid = WaypointStatus::STATUS_LEFT;

    // build the waypoints array
    $dwp = null;

    //foreach ($waypoints as &$wp) {
    for($i = 0; $i < count($waypoints); $i++) {

        // get the needed waypoints
        $wp = $waypoints[array_keys($waypoints)[$i]];
        if ($i > 0){
            $prevwp = $waypoints[array_keys($waypoints)[$i - 1]];
        }
        if ($i < count($waypoints) - 1){
            $nextwp = $waypoints[array_keys($waypoints)[$i + 1]];
        }

         // get the active waypoint (etappe) and status
        if ($statusid == WaypointStatus::STATUS_LEFT){
            $wpnbr++;
            $statusid = $wp['statusid'];
        }

        // calculate all values
        // first position : O overview (effective), T theoretical, F formatting
        // second position : A distance, T Typical, V consumption G Average
        // third position : 1 waypoint-value, 2 sum
        CalculateDistance($i, $wp, $prevwp
            , $oa1, $ta1, $oa2, $ta2
            , $ot1, $tt1, $ot2, $tt2
            , $ov1, $tv1, $ov2, $tv2
            , $og1, $tg1, $og2, $tg2
            , $fa1, $fa2, $ft1, $ft2, $fv1, $fv2, $fg1, $fg2);
        $oa1 = format($oa1, 1);
        $foa2 = format($oa2, 1);
        $ta1 = format($ta1, 1);
        $fta2 = format($ta2, 1);
        $ot1 = format($ot1, 0);
        $fot2 = format($ot2, 0);
        $tt1 = format($tt1, 0);
        $ftt2 = format($tt2, 0);
        $ov1 = format($ov1, 1);
        $fov2 = format($ov2, 1);
        $tv1 = format($tv1, 1);
        $ftv2 = format($tv2, 1);
        $og1 = format($og1, 0);
        $fog2 = format($og2, 0);
        $tg1 = format($tg1, 0);
        $ftg2 = format($tg2, 0);

        CalculateTimes($trip, $i, $wp, $prevwp, $nextwp
            , $or1, $tr1, $or2, $tr2
            , $ol1, $tl1, $ol2, $tl2
            , $ocn, $tcn, $oat, $tat, $ovt, $tvt
            , $fr1, $fr2, $fl1, $fl2, $fcn, $fat, $fvt);

        // build the waypoint output result
        $dwp[] = array('id' => $wp['objid']
        , 'location' => ''
        , 'name' => $wp['destination']
        , 'statusid' => $wp['statusid']
        , 'overview' =>
                array('distance' => $oa1
                , 'totaldistance' => $foa2
                , 'typical' => $ot1
                , 'totaltypical' => $fot2
                , 'consumption' => $ov1
                , 'totalconsumption' => $fov2
                , 'average' => $og1
                , 'totalaverage' => $fog2
                , 'drivetime' => $or1
                , 'totaldrivetime' => $or2                   //date('H:i', mktime(0, $totaldrivetime, 0, 1, 1, 2000))
                , 'chargetime' => $ol1
                , 'totalchargetime' => $ol2
                , 'chargeneeded' => $ocn
                , 'arrivaltime' => $oat
                , 'departuretime' => $ovt
                )
        , 'theoretical' =>
                array('distance' => $ta1
                , 'totaldistance' => $fta2
                , 'typical' => $tt1
                , 'totaltypical' => $ftt2
                , 'consumption' => $tv1
                , 'totalconsumption' => $ftv2
                , 'average' => $tg1
                , 'totalaverage' => $ftg2
                , 'drivetime' => $tr1
                , 'totaldrivetime' => $tr2
                , 'chargetime' => $tl1
                , 'totalchargetime' => $tl2
                , 'chargeneeded' => $tcn
                , 'arrivaltime' => $tat
                , 'departuretime' => $tvt
                )
        , 'formating' =>
                array('distance' => $fa1
                , 'totaldistance' => $fa2
                , 'typical' => $ft1
                , 'totaltypical' => $ft2
                , 'consumption' => $fv1
                , 'totalconsumption' => $fv2
                , 'average' => $fg1
                , 'totalaverage' => $fg2
                , 'drivetime' => $tr1
                , 'totaldrivetime' => $tr2
                , 'chargetime' => $tl1
                , 'totalchargetime' => $tl2
                , 'chargeneeded' => $tcn
                , 'arrivaltime' => $tat
                , 'departuretime' => $tvt
                )
        );

        //$wpchargestarted = $wpchargeneeded;

    }

    // final result
    $d = array('id' => $tripId
    , 'desc' => $trip->att('name')
    , 'statusid' => $statusid
    , 'etape' => $wpnbr
    , 'waypoints' => $dwp
    );

    return array("key" => "trip", "data" => $d);
    //return $d;
}

function format($val, $dec){
    if ($val == '') {
        return $val;
    }
    else{
        return number_format($val, $dec, ',', '.');
    }
}

function CalculateDistance($i, $wp, $prevwp
    , &$oa1, &$ta1, &$oa2, &$ta2
    , &$ot1, &$tt1, &$ot2, &$tt2
    , &$ov1, &$tv1, &$ov2, &$tv2
    , &$og1, &$tg1, &$og2, &$tg2
    , &$fa1, &$fa2, &$ft1, &$ft2, &$fv1, &$fv2, &$fg1, &$fg2)
{
//------------------------------------------------------------------------

    if ($i == 0) {
        // initialize totals
        $ta1 = ''; $ta2 = 0;  $oa1 = ''; $oa2 = 0;
        $tt1 = ''; $tt2 = 0;  $ot1 = ''; $ot2 = 0;
        $tv1 = ''; $tv2 = 0;  $ov1 = ''; $ov2 = 0;
        $tg1 = ''; $tg2 = ''; $og1 = ''; $og2 = '';
        $fa1 = ''; $fa2 = ''; $ft1 = ''; $ft2 = ''; $fv1 = ''; $fv2 = ''; $fg1 = ''; $fg2 = '';
    }

    if ($i > 0) {
        // calculate theoretical values
        $ta1 = $wp['theoreticaldistance'];
        $ta2 = $ta2 + $ta1;
        $tt1 = $wp['theoreticaltypical'];
        $tt2 = $tt2 + $tt1;
        $tv1 = CalculateEnergy($tt1);
        $tv2 = $tv2 + $tv1;
        $tg1 = ($tv1 / $ta1) * 1000;
        $tg2 = ($tv2 / $ta2) * 1000;

        // calculate effective values if applicable
        if ($wp['statusid'] == WaypointStatus::STATUS_DRIVING){
            $oa1 = $ta1;
            $ot1 = $tt1;
            $ov1 = $tv1;
            $fa1 = '';
            $ft1 = '';
            $fv1 = '';
        }
        else{
            $oa1 = $wp['arrivaldistance'] - $prevwp['departuredistance'];
            $ot1 = $prevwp['departuretypical'] - $wp['arrivaltypical'];
            $ov1 = $wp['arrivalconsumption'] - $prevwp['departureconsumption'];
            $fa1 = SetFormatClass($ta1, $oa1);
            $ft1 = SetFormatClass($tt1, $ot1);
            $fv1 = SetFormatClass($tv1, $ov1);
        }
        $oa2 = $oa2 + $oa1;
        $ot2 = $ot2 + $ot1;
        $ov2 = $ov2 + $ov1;
        $og1 = ($ov1 / $oa1) * 1000;
        $og2 = ($ov2 / $oa2) * 1000;
        $fa2 = SetFormatClass($ta2, $oa2);
        $ft2 = SetFormatClass($tt2, $ot2);
        $fv2 = SetFormatClass($tv2, $ov2);
        $fg1 = SetFormatClass($tg1, $og1);
        $fg2 = SetFormatClass($tg2, $og2);
    }
}

Function CalculateTimes($trip, $i, $wp, $prevwp, $nextwp
    , &$or1, &$tr1, &$or2, &$tr2
    , &$ol1, &$tl1, &$ol2, &$tl2
    , &$ocn, &$tcn, &$oat, &$tat, &$ovt, &$tvt
    , &$fr1, &$fr2, &$fl1, &$fl2, &$fcn, &$fat, &$fvt){
//-----------------------------------------------------

    if ($i == 0) {
        // initialize totals
        $tr1 = ''; $tr2 = '';  $or1 = ''; $or2 = '';
        $tl1 = ''; $tl2 = '';  $ol1 = ''; $ol2 = '';
        $tat = ''; $oat = '';
        $fr1 = ''; $fr2 = ''; $fl1 = ''; $fl2 = ''; $fcn = ''; $fat = ''; $fvt = '';
        $tvt = '';
        $tcn = 0;
        if ($wp['statusid'] != WaypointStatus::STATUS_LEFT){
            $ovt = $tvt;
        }
        else{
            $ovt = $wp['departuretime'];
        }
        $ocn = $tcn;
    }

}

Function SetFormatClass($t, $o){
//----------------------------------
    if ($t > $o){
        return 'P';
    }
    if ($t < $o){
        return 'M';
    }
    return 'E';
}
/*
if ($i == 0){
    // first waypoint initialize counters
    if ($wp['statusid'] < 4){
        $startdrivetime = $trip->att('date');
        $wpchargeneeded = $wp['theoreticalchargeneeded'];
    }
    else{
        $startdrivetime = $wp['departuretime'];
        $wpchargeneeded = $wp['departuretypical'];
    }
    $wpdeparturetime = date('H:i', $startdrivetime);
    $wpchargestarted = $wpchargeneeded;
}
else{
    // make running totals
    if ($wp['statusid'] == 0){
        $wpdistance = $wp['theoreticaldistance'];
        $wptypical = $wp['theoreticaltypical'];
        $wpconsumption = $wp['theoreticalconsumption'];
        $wparrivaltime = $startdrivetime + ($wp['theoreticaldrivetime'] * 60);
        $wpchargestart = $wpchargestarted - $wp['theoreticaltypical'];
    }
    else{
        $wpdistance = $wp['arrivaldistance'];
        $wptypical = ($wpchargestarted - $wp['arrivaltypical']);
        $wpconsumption = $wp['arrivalconsumption'];
        $wparrivaltime = $wp['arrivaltime'];
        $wpchargestart = intval($wp['arrivaltypical']);
    }
    $totaldistance += $wpdistance;
    $totaltypical += $wptypical;
    $totalconsumption += $wpconsumption;
    $startdrivetime = $wparrivaltime;

    if ($wpdistance <> 0){
        $wpaverage = round( ($wpconsumption * 1000)/ $wpdistance, 0);
    }
    else{
        $wpaverage = '';
    }

    if ($wp['statusid'] < 3){
        //$startdrivetime = $wparrivaltime + ($wp['theoreticalchargetime'] * 60);
        $startdrivetime = $wparrivaltime + (CalculateChargeTime(0, $wp['theoreticalchargeneeded']) * 60);
        $wpchargeneeded = $wp['theoreticalchargeneeded'];
    }
    if ($wp['statusid'] == 3){
        $startdrivetime = $wp['chargeendtime'];
        $wpchargeneeded = $wp['chargeendtypical'];
    }

    if ($wp['statusid'] == 4){
        $wpchargeneeded = $wp['departuretypical'];
        $startdrivetime = $wp['departuretime'];
    }
    // to calculate
    $wpdrivetime = $wp['theoreticaldrivetime'];
    $totaldrivetime += $wpdrivetime;
    //$wpchargetime = $wp['theoreticalchargetime'];
    $wpchargetime = CalculateChargeTime(0, $wp['theoreticalchargeneeded']);
    $totalchargetime += $wpchargetime;



    //format the output
    $wpdistance = number_format($wpdistance, 1, ',', '.');
    $wptypical = number_format($wptypical, 0);
    $wpconsumption = number_format($wpconsumption, 1, ',', '.');
    $wpdrivetime = date ('H:i', mktime(0 ,$wpdrivetime, 0, 1, 1, 2000));
    if ($wpchargetime > 0){
        $wpchargetime = date('H:i', mktime(0, $wpchargetime, 0, 1, 1, 2000));
    }
    else{
        $wpchargetime = '';
    }

    $wparrivaltime = date ('H:i', $wparrivaltime);
    $wpdeparturetime = date ('H:i', $startdrivetime);
}
*/

function Obsolete(){
    $t = 1;


    /*
    // tripstatus
    $wps1 = new waypointstatus();
    $wps1->att('objid', 0);
    $wps1->att('name_en', 'Driving');
    $wps1->save();

    $wps2 = new waypointstatus();
    $wps2->att('name_en', 'Arrived');
    $wps2->save();

    $wps3 = new waypointstatus();
    $wps3->att('name_en', 'Charging');
    $wps3->save();

    $wps3 = new waypointstatus();
    $wps3->att('name_en', 'Charged');
    $wps3->save();

    $wps3 = new waypointstatus();
    $wps3->att('name_en', 'Left');
    $wps3->save();

    exit();


    $ts = new tripstatus();
    //$ts->retrieve(4);
    $ts->att('objid', 4);

    $ts->att('name_en', 'updated after explicite object id');
    $ts->save();

    echo TripStatus::STATUS_SCHEDULED;

    //sleep(5);

        $ts->att('name_en', 'Started');
        $ts->save();

    */


    $t= 2;


    /*
    $d2 = '{
        id: 1,
        desc: 'La Vielle Ferme - Thuis',
        etape: 0,
        statusid: 3,
        waypoints: [
            {name: 'SuC Macon - La Vielle Ferme',
                id: 1,
                location: '',
                overview: {
                    distance: '',
                    totaldistance: '',
                    typical: '',
                    totaltypical: '',
                    consumption: '',
                    totalconsumption: '',
                    averageconsumption: '',
                    drivetime: '',
                    totaldrivetime: '',
                    chargetime: '',
                    totalchargetime: '',
                    chargeneeded: 340,
                    arrivaltime: '',
                    departuretime: '10:00'
                },
                theoretical: {
                    arrival: {
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    },
                    chargestart: {
                        typical: 0,
                        time: 0
                    },
                    chargeend: {
                        typical: 0,
                        time: 0
                    },
                    departure:{
                        distance: 0,
                        typical: 340,
                        consumption: 0,
                        time: '10:00'
                    }
                },
                efffective: {
                    arrival: {
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: ''
                    },
                    chargestart: {
                        typical: 0,
                        time: ''
                    },
                    chargeend: {
                        typical: 0,
                        time: ''
                    },
                    departure:{
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: ''
                    }
                }
            },
            {name: 'SuC Auxerre',
                id: 2,
                location: '',
                overview: {
                    distance: 232.2,
                    totaldistance: 232.2,
                    typical: 323,
                    totaltypical: 323,
                    consumption: 60.0,
                    totalconsumption: 60.0,
                    averageconsumption: 258,
                    drivetime: '2:05',
                    totaldrivetime: '2:05',
                    chargetime: '0:40',
                    totalchargetime: '0:40',
                    chargeneeded: 280,
                    arrivaltime: '12:05',
                    departuretime: '12:45'
                },
                theoretical: {
                    arrival: {
                        distance: 232.2,
                        typical: 17,
                        consumption: 60.0,
                        time: '2:05'
                    },
                    chargestart: {
                        typical: 17,
                        time: '12:05'
                    },
                    chargeend: {
                        typical: 280,
                        time: '12:45'
                    },
                    departure:{
                        distance: 0,
                        typical: 280,
                        consumption: 0,
                        time: '12:45'
                    }
                },
                efffective: {
                    arrival: {
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: ''
                    },
                    chargestart: {
                        typical: 0,
                        time: ''
                    },
                    chargeend: {
                        typical: 0,
                        time: ''
                    },
                    departure:{
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    }
                }
            },
            {name: 'SuC Senlis',
                id: 3,
                location: '',
                overview: {
                    distance: 209.6,
                    totaldistance: 441.8,
                    typical: 260,
                    totaltypical: 583,
                    consumption: 48.0,
                    totalconsumption: 108.0,
                    averageconsumption: 229,
                    drivetime: '2:10',
                    totaldrivetime: '4:15',
                    chargetime: '0:50',
                    totalchargetime: '1:30',
                    chargeneeded: 330,
                    arrivaltime: '14:55',
                    departuretime: '15:45'
                },
                theoretical: {
                    arrival: {
                        distance: 209.6,
                        typical: 20,
                        consumption: 48.0,
                        time: '2:10'
                    },
                    chargestart: {
                        typical: 20,
                        time: '14:55'
                    },
                    chargeend: {
                        typical: 330,
                        time: '15:45'
                    },
                    departure:{
                        distance: 0,
                        typical: 330,
                        consumption: 0,
                        time: '15:45'
                    }
                },
                efffective: {
                    arrival: {
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    },
                    chargestart: {
                        typical: 0,
                        time: 0
                    },
                    chargeend: {
                        typical: 0,
                        time: 0
                    },
                    departure:{
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    }
                }
            },
            {name: 'SuC Gent',
                id: 4,
                location: '',
                overview: {
                    distance: 238.4,
                    totaldistance: 680.2,
                    typical: 305,
                    totaltypical: 888,
                    consumption: 57.0,
                    totalconsumption: 165.0,
                    averageconsumption: 239,
                    drivetime: '2:15',
                    totaldrivetime: '6:30',
                    chargetime: '0:10',
                    totalchargetime: '1:40',
                    chargeneeded: 110,
                    arrivaltime: '18:00',
                    departuretime: '18:10'
                },
                theoretical: {
                    arrival: {
                        distance: 238.4,
                        typical: 15,
                        consumption: 57.0,
                        time: '2:15'
                    },
                    chargestart: {
                        typical: 15,
                        time: '18:00'
                    },
                    chargeend: {
                        typical: 110,
                        time: '18:10'
                    },
                    departure:{
                        distance: 0,
                        typical: 110,
                        consumption: 0,
                        time: '18:10'
                    }
                },
                efffective: {
                    arrival: {
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    },
                    chargestart: {
                        typical: 0,
                        time: 0
                    },
                    chargeend: {
                        typical: 0,
                        time: 0
                    },
                    departure:{
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    }
                }
            },
            {name: 'thuis',
                id: 5,
                location: '',
                overview: {
                    distance: '80.0',
                    totaldistance: 760.2,
                    typical: 96,
                    totaltypical: 984,
                    consumption: 18.0,
                    totalconsumption: 183.0,
                    averageconsumption: 225,
                    drivetime: '1:00',
                    totaldrivetime: '7:30',
                    chargetime: '',
                    totalchargetime: '',
                    chargeneeded: '',
                    arrivaltime: '19:10',
                    departuretime: ''
                },
                theoretical: {
                    arrival: {
                        distance: 80.0,
                        typical: 14,
                        consumption: 18.0,
                        time: '1:00'
                    },
                    chargestart: {
                        typical: 0,
                        time: ''
                    },
                    chargeend: {
                        typical: 0,
                        time: ''
                    },
                    departure:{
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: ''
                    }
                },
                efffective: {
                    arrival: {
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    },
                    chargestart: {
                        typical: 0,
                        time: 0
                    },
                    chargeend: {
                        typical: 0,
                        time: 0
                    },
                    departure:{
                        distance: 0,
                        typical: 0,
                        consumption: 0,
                        time: 0
                    }
                }
            }
        ]
    }';
    echo $d2;
    */
    /*
        [     id: '.$tripId.',
                    desc: 'La Vielle Ferme - Thuis',
                    etape: 0,
                    statusid: 3,
                    waypoints: [
                        {name: 'SuC Auxerre',
                            id: 2,
                            location: '',
                            overview: {
                                distance: 232.2,
                                totaldistance: 232.2,
                                typical: 323,
                                totaltypical: 323,
                                consumption: 60.0,
                                totalconsumption: 60.0,
                                averageconsumption: 258,
                                drivetime: '2:05',
                                totaldrivetime: '2:05',
                                chargetime: '0:40',
                                totalchargetime: '0:40',
                                chargeneeded: 280,
                                arrivaltime: '12:05',
                                departuretime: '12:45'
                            },
                            theoretical: {
                                arrival: {
                                    distance: 232.2,
                                    typical: 17,
                                    consumption: 60.0,
                                    time: '2:05'
                                },
                                chargestart: {
                                    typical: 17,
                                    time: '12:05'
                                },
                                chargeend: {
                                    typical: 280,
                                    time: '12:45'
                                },
                                departure:{
                                    distance: 0,
                                    typical: 280,
                                    consumption: 0,
                                    time: '12:45'
                                }
                            },
                            efffective: {
                                arrival: {
                                    distance: 0,
                                    typical: 0,
                                    consumption: 0,
                                    time: ''
                                },
                                chargestart: {
                                    typical: 0,
                                    time: ''
                                },
                                chargeend: {
                                    typical: 0,
                                    time: ''
                                },
                                departure:{
                                    distance: 0,
                                    typical: 0,
                                    consumption: 0,
                                    time: 0
                                }
                            }
                        }
                    ]
                ]';
        return $d;
    */
    $t = 3;
}

