<?php
    // let's bootstrap our application.
    include_once('includes/bootstrap.inc.php');
    header('Content-Type: application/json');

	// array_key_lower_case(
	switch(strtolower($_REQUEST['action'])) {

        case "login":
            //-----------------
            // get other parameters
            $car = $_REQUEST['car'];
            $password = $_REQUEST['password'];

            $d = Login($car, $password);
            echo json_encode($d);
            break;

        case "gettrips":
            //-----------------
            // get other parameters
            $car = strtolower($_REQUEST['car']);

            $d = GetTrips($car);
            echo json_encode($d);
            break;

        case "gettrip":
            //-------------------------------------
            // get other parameters
            $tripId = $_REQUEST['tripid'];

            // retrieve the data
            $d = GetTripDetail($tripId);
            echo json_encode($d);
            break;

        case "savearrival":
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST['tripid']);
            $wpId = strtolower($_REQUEST['wpid']);
            $arrivaltypical = strtolower($_REQUEST['arrivaltypical']);
            $arrivaldistance = strtolower($_REQUEST['arrivaldistance']);
            $arrivalconsumption = strtolower($_REQUEST['arrivalconsumption']);

            // do the update of the waypoint
            $wp = new waypoint();
            $wp->retrieve($wpId);
            $wp->att("arrivaldistance", $arrivaldistance);
            $wp->att("arrivaltypical", $arrivaltypical);
            $wp->att("arrivalconsumption", $arrivalconsumption);
            $wp->att("arrivaltime", time());
            $wp->att('statusid', WaypointStatus::STATUS_ARRIVED);
            $wp->save();

            // change status of Trip for arrival at last waypont
            if ($wp->att('typeid') == WaypointType::TYPE_ENDPOINT){
                $trip = new trip();
                $trip->retrieve($tripId);
                $trip->att('statusid', TripStatus::STATUS_ENDED);
                $trip->save();
            }

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        case "savechargestart":
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST['tripid']);
            $wpId = strtolower($_REQUEST['wpid']);
            $chargestartypical = strtolower($_REQUEST['chargestarttypical']);

            // do the update
            $wp = new waypoint();
            $wp->retrieve($wpId);
            $wp->att('chargestarttypical', $chargestartypical);
            $wp->att('chargestarttime', time());
            $wp->att('statusid', WaypointStatus::STATUS_CHARGING);
            $wp->save();

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        case 'savechargeend':
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST['tripid']);
            $wpId = strtolower($_REQUEST['wpid']);
            $chargeendtypical = strtolower($_REQUEST['chargeendtypical']);

            // do the update
            $wp = new waypoint();
            $wp->retrieve($wpId);
            $wp->att('chargeendtypical', $chargeendtypical);
            $wp->att('chargeendtime', time());
            $wp->att('statusid', WaypointStatus::STATUS_CHARGED);
            $wp->save();

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        case 'savedeparture':
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST['tripid']);
            $wpId = strtolower($_REQUEST['wpid']);
            $departuretypical = strtolower($_REQUEST['departuretypical']);
            $departuredistance = strtolower($_REQUEST['departuredistance']);
            $departureconsumption = strtolower($_REQUEST['departureconsumption']);

            // do the update of the waypoint
            $wp = new waypoint();
            $wp->retrieve($wpId);
            $wp->att('departuredistance', $departuredistance);
            $wp->att('departuretypical', $departuretypical);
            $wp->att('departureconsumption', $departureconsumption);
            $wp->att('departuretime', time());
            $wp->att('statusid', WaypointStatus::STATUS_LEFT);
            $wp->save();

            // change status of Trip for start at first waypont
            if ($wp->att('typeid') == WaypointType::TYPE_STARTPOINT){
                $trip = new trip();
                $trip->retrieve($tripId);
                $trip->att('statusid', TripStatus::STATUS_STARTED);
                $trip->save();
            }

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        case 'getcardata':
            //------------------------------
            //get other parameters
            $car = strtolower($_REQUEST['car']);
            $typical = strtolower($_REQUEST['typical']);

            // retrieve the data
            $d = GetCarData($car, $typical);
            echo json_encode($d);
            break;

        default:
            echo '';
            break;

	}


function LogIn($car, $password)
    {
        //-----------------------------------------

        $error = '';
        $login = '';
        if ($car != '38855' && $car != '93777' && $car != 'TEST'){
            $error = 'Onbekend VIN, auto niet gekend';
        }
        else{
            if ($password == ''){
                $login = 'readonly';
            }
            else{
                if (($car == '38855' && $password == 'diego') || ($car == '93777' && $password == 'david') || ($car == 'TEST' && $password == 'test') ){
                    $login = 'ok';
                }
                else{
                    $error = 'Foutief paswoord';
                }
            }
        }

        if ($error != ''){
            $login = 'nok';
        }
        // make the result array
        $d = array('car' => $car
            , 'login' => $login
            , 'error' => $error
            );

        return array("key" => "logindata", "data" => $d);
    }


function GetTrips($car)
    {
    //-----------------------------------------

        // trips
        $trip = new trip();
        $car = DB::qstr($car);
        $trips = $trip->getAllObjectsArray("car = $car", array('objid', 'date', 'statusid', 'name', 'theoreticalstarttime'), 'date DESC, objid DESC');

        // tripstatus
        $tripstatus = new TripStatus();
        $tripstatusses = $tripstatus->getAllObjectsArray(null, array('objid', 'name_en'));

        //var_dump($tripstatusses[array_keys($tripstatusses)[0]]);

        // make the result array
        $d = null;
        // initialise totals

        // build the trips array
        foreach ($trips as &$tr) {
            $d[] = array('id' => $tr['objid']
            , 'date' => $tr['date']
            , 'name' => $tr['name']
            , 'status' => $tripstatusses[$tr['statusid']]['name_en']
            );

        }

        return array("key" => "trips", "data" => $d);
    }

function GetTripDetailOld($tripId)
    {
        //-----------------------------------------

        // selected trip
        $trip = new trip();
        $trip->retrieve($tripId);

        $waypoint = new waypoint();
        $waypoints = $waypoint->getAllObjectsArray('tripid = '. DB::qstr($tripId), null, 'objid');

        // make the result array
        $wpnbr = -1;
        $statusid = 4;

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

        // build the waypoints array
        $dwp = null;

        //foreach ($waypoints as &$wp) {
         for($i = 0; $i < count($waypoints); $i++) {

                $wp = $waypoints[array_keys($waypoints)[$i]];
             //var_dump($wp); exit();
                if ($statusid == 4){
                    $wpnbr++;
                    $statusid = $wp['statusid'];
                }
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


                // build the waypoint
                $dwp[] = array('id' => $wp['objid']
                , 'location' => ''
                , 'name' => $wp['destination']
                , 'statusid' => $wp['statusid']
                , 'overview' =>
                        array('distance' => $wpdistance
                        , 'totaldistance' => number_format($totaldistance, 1, ',', '.')
                        , 'typical' => $wptypical
                        , 'totaltypical' => $totaltypical
                        , 'consumption' => $wpconsumption
                        , 'totalconsumption' => number_format($totalconsumption, 1, ',', '.')
                        , 'averageconsumption' => $wpaverage
                        , 'drivetime' => $wpdrivetime
                        , 'totaldrivetime' => date('H:i', mktime(0, $totaldrivetime, 0, 1, 1, 2000))
                        , 'chargetime' => $wpchargetime
                        , 'totalchargetime' => date('H:i', mktime(0, $totalchargetime, 0, 1, 1, 2000))
                        , 'chargeneeded' => $wpchargeneeded
                        , 'arrivaltime' => $wparrivaltime
                        , 'departuretime' => $wpdeparturetime
                        )
                , 'theoretical' =>
                        array('arrival' =>
                            array('distance' => $wpdistance + 0.0
                            , 'typical' => $wpchargestarted - $wp['theoreticaltypical']
                            , 'consumption' => $wp['theoreticalconsumption'] + 0.0
                            , 'time' => $wp['theoreticaldrivetime']
                            )
                        , 'chargestart' =>
                            array('typical' => $wpchargestart
                            , 'time' => ''
                            )
                        , 'chargeend' =>
                            array('typical' => $wp['theoreticalchargeneeded'] + 0
                            , 'time' => ''
                            )
                        , 'departure' =>
                            array('distance' => 0.0
                            , 'typical' => $wpchargeneeded + 0
                            , 'consumption' => 0.0
                            , 'time' => ''
                            )
                        )
                , 'effective' =>
                        array('arrival' =>
                            array('distance' => $wp['arrivaldistance']
                            , 'typical' => $wp['arrivaltypical']
                            , 'consumption' => $wp['arrivalconsumption']
                            , 'time' => $wp['arrivaltime']
                            )
                        , 'chargestart' =>
                            array('typical' => $wp['chargestarttypical']
                            , 'time' => $wp['chargestarttime']
                            )
                        , 'chargeend' =>
                            array('typical' => $wp['chargeendtypical']
                            , 'time' => $wp['chargeendtime']
                            )
                        , 'departure' =>
                            array('distance' => $wp['departuredistance']
                            , 'typical' => $wp['departuretypical']
                            , 'consumption' => $wp['departureconsumption']
                            , 'time' => $wp['departuretime']
                            )
                        )
                );

             $wpchargestarted = $wpchargeneeded;

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

function GetTripDetail($tripId)
{
//-----------------------------------------

    // selected trip
    $trip = new trip();
    $trip->retrieve($tripId);
    // get all waypoints for this trip
    $waypoint = new waypoint();
    $waypoints = $waypoint->getAllObjectsArray('tripid = '. DB::qstr($tripId), null, 'objid');

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

        // get the last departure typical to calculate consumption for next arrival
        if ($wp['statusid'] == WaypointStatus::STATUS_LEFT){
            $lasttypical = $wp['departuretypical'];
        }

        // calculate all values
        // first position : O overview (effective), T theoretical, F formatting
        // second position : A distance, T Typical, V consumption G Average, R DriveTime, L ChargeTime
        // third position : 1 waypoint-value, 2 sum
        // second and third position : CN ChargeNeeded AT ArrivalTime, VT DepartureTime
        CalculateDistance($i, $wp, $prevwp
            , $oa1, $ta1, $oa2, $ta2
            , $ot1, $tt1, $ot2, $tt2
            , $ov1, $tv1, $ov2, $tv2
            , $og1, $tg1, $og2, $tg2
            , $fa1, $fa2, $ft1, $ft2, $fv1, $fv2, $fg1, $fg2);

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
                array('distance' => format($oa1, 1)
                , 'totaldistance' => format($oa2, 1)
                , 'typical' => format($ot1, 0)
                , 'totaltypical' => format($ot2, 0)
                , 'consumption' => format($ov1, 1)
                , 'totalconsumption' => format($ov2, 1)
                , 'average' => format($og1, 0)
                , 'totalaverage' => format($og2, 0)
                , 'drivetime' => formatMinutes($or1)
                , 'totaldrivetime' => formatMinutes($or2)
                , 'chargetime' => formatMinutes($ol1)
                , 'totalchargetime' => formatMinutes($ol2)
                , 'chargeneeded' => $ocn
                , 'arrivaltime' => formatDate($oat)
                , 'departuretime' => formatDate($ovt)
                )
        /*
        , 'theoretical' =>
                array('distance' => format($ta1, 1)
                , 'totaldistance' => format($ta2, 1)
                , 'typical' => format($tt1, 0)
                , 'totaltypical' => format($tt2, 0)
                , 'consumption' => format($tv1, 1)
                , 'totalconsumption' => format($tv2, 1)
                , 'average' => format($tg1, 0)
                , 'totalaverage' => format($tg2, 0)
                , 'drivetime' => formatMinutes($tr1)
                , 'totaldrivetime' => formatMinutes($tr2)
                , 'chargetime' => formatMinutes($tl1)
                , 'totalchargetime' => formatMinutes($tl2)
                , 'chargeneeded' => $tcn
                , 'arrivaltime' => formatDate( $tat)
                , 'departuretime' => formatDate($tvt)
                )
        */
        /*
        , 'formating' =>
                array('distance' => $fa1
                , 'totaldistance' => $fa2
                , 'typical' => $ft1
                , 'totaltypical' => $ft2
                , 'consumption' => $fv1
                , 'totalconsumption' => $fv2
                , 'average' => $fg1
                , 'totalaverage' => $fg2
                , 'drivetime' => $fr1
                , 'totaldrivetime' => $fr2
                , 'chargetime' => $fl1
                , 'totalchargetime' => $fl2
                , 'chargeneeded' => $fcn
                , 'arrivaltime' => $fat
                , 'departuretime' => $fvt
              )
        */
        );
    }

    // final result
    $d = array('id' => $tripId
    , 'desc' => $trip->att('name')
    , 'statusid' => $statusid
    , 'etape' => $wpnbr
    , 'lasttypical' => $lasttypical
    , 'waypoints' => $dwp
    );

    return array("key" => "trip", "data" => $d);
    //return $d;
}

function CalculateChargeTime($from, $to)
    {
    //--------------------------------------

        $max = 385;
        // xls function :  =ROUNDUP((LN(-(((C4)/$J$2)-1))/-LN(1,03830664))-(LN(-(((B4)/$J$2)-1))/-LN(1,03830664));0)
        $mt = log(-(($to / $max) - 1)) / - log(1.03830664);
        $mf = log(-(($from / $max) - 1)) / - log(1.03830664);
        $m = ceil($mt - $mf);

        return $m;
    }

function CalculateChargeNeeded($typical)
    {
    //--------------------------------------

        $n = floor($typical * 1.1 / 10) * 10;
        $c = min(380, $n);

        return $c;
    }

function CalculateEnergy($typical)
{
    $kWh = round($typical * 0.193, 1);
    return $kWh;
}

function format($val, $dec){
    if ($val == '') {
        return $val;
    }
    else{
        return number_format($val, $dec, ',', '.');
    }
}
function formatDate($val){
    if ($val == '') {
        return $val;
    }
    else{
        return date('H:i', $val);
    }
}
function formatMinutes($val){
    if ($val == '') {
        return $val;
    }
    else{
        return date('H:i', mktime(0, $val, 0, 1, 1, 2000));
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
        if ($oa1 == 0){
            $og1 = 0;
        }else{
            $og1 = ($ov1 / $oa1) * 1000;
        }
        if ($oa2 == 0){
            $og2 = 0;
        }else{
            $og2 = ($ov2 / $oa2) * 1000;
        }
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

    if ($i == 0) { // start point
        // initialize totals
        $tr1 = ''; $tr2 = 0;  $or1 = ''; $or2 = 0;
        $tl1 = ''; $tl2 = 0;  $ol1 = ''; $ol2 = 0;
        $tat = ''; $oat = '';
        $fr1 = ''; $fr2 = ''; $fl1 = ''; $fl2 = ''; $fcn = ''; $fat = ''; $fvt = '';
        $tvt = $trip->att('date');
        if ($wp['statusid'] != WaypointStatus::STATUS_LEFT){
            $ovt = $tvt;
        }
        else{
            $ovt = $wp['departuretime'];
        }
        $ocn = $tcn;
    }
    if ($i > 0) { // other waypoints
        // calculate theoretical values
        $tr1 = $wp['theoreticaldrivetime'];
        $tr2 = $tr2 + $tr1;
        $tat = $ovt + ($tr1 * 60);
        if ($wp['typeid'] != WaypointType::TYPE_ENDPOINT){
            $tl1 = CalculateChargeTime(0, CalculateChargeNeeded($nextwp['theoreticaltypical']));
            $tl2 = $tl2 + $tl1;
            $tvt = $tat + ($tl1 * 60);
        }else{
            $tl1 = '';
            $tl2 = '';
            $tvt = '';
        }

        // calculate effective values if applicable
        if ($wp['statusid'] == WaypointStatus::STATUS_DRIVING){
            $or1 = $tr1;
            $ol1 = $tl1;
            $oat = $tat;
            $ovt = $tvt;
        }
        else{
            $or1 = round(($wp['arrivaltime'] - $prevwp['departuretime']) / 60, 0);
            $oat = $wp['arrivaltime'];

            if ($wp['typeid'] != WaypointType::TYPE_ENDPOINT){
                if ($wp['statusid'] < WaypointStatus::STATUS_CHARGED){
                    $ol1 = CalculateChargeTime($wp['arrivaltypical'], CalculateChargeNeeded($nextwp['theoreticaltypical']));
                }else{
                    $ol1 = round(($wp['chargeendtime'] - $wp['chargestarttime']) / 60, 0);
                }
            }
            if ($wp['statusid'] == WaypointStatus::STATUS_ARRIVED){
                $ovt = $wp['arrivaltime'] + (60 * $ol1);
            }
            if ($wp['statusid'] == WaypointStatus::STATUS_CHARGING){
                $ovt = $wp['chargestarttime'] + (60 * $ol1);
            }
            if ($wp['statusid'] == WaypointStatus::STATUS_CHARGED){
                $ovt = $wp['chargeendtime'];
            }
            if ($wp['statusid'] == WaypointStatus::STATUS_LEFT){
                $ovt = $wp['departuretime'];
            }
        }
        $or2 = $or2 + $or1;
        if ($wp['typeid'] != WaypointType::TYPE_ENDPOINT){
            $ol2 = $ol2 + $ol1;
            $fl1 = SetFormatClass($tl1, $ol1);
            $fl2 = SetFormatClass($tl2, $ol2);
            $fvt = SetFormatClass($tvt, $ovt);
        }

        $fr1 = SetFormatClass($tr1, $or1);
        $fr2 = SetFormatClass($tr2, $or2);
        $fat = SetFormatClass($tat, $oat);

    }

    if ($wp['typrid'] != WaypointType::TYPE_ENDPOINT) {
        $tcn = CalculateChargeNeeded($nextwp['theoreticaltypical']);
        $ocn = $tcn;
        $fcn = SetFormatClass($tcn, $ocn);
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


function GetCarData($carkey, $typical)
{
    //-----------------------------------------
    include_once("apiclient/tesla.class.php");
    $tesla = new TeslaClient("e4a9949fcfa04068f59abb5a658f2bac0a3428e4652315490b659d5ab3f35a9e", "c75f14bbadc8bee3a7594412c31416f8300256d7668ea7e6e7f06727bfb9d220");

    // read the car-table
    //$car = new car();
    //$carkey = DB::qstr($carkey);
    //$car = $car->getAllObjectsArray("car = $car", array('objid', 'date', 'statusid', 'name', 'theoreticalstarttime'), 'date DESC, objid DESC');
    $user = 'diego@dpinformatics.be';
    //$password = 'dpinfo6103';
    //$token = $tesla->auth($user, $password);
    $token = 'b927ce46507b94f2839b2c48f2fa946f5249094743454c7fdb462d8c4cb9fef2';
    $technicalid = '40235758685225996';

    $tesla->authWithToken($user, $token);

    // make the result array
    // car state
    $reply = $tesla->get('vehicles/' . $technicalid . '/data_request/vehicle_state');
    $physical = $reply['response'];
    $s['vehicle_name'] = $physical['vehicle_name'];
    $s['odometer'] = round($physical['odometer'], 2);
    $s['odometerkm'] = round($physical['odometer'] * 1.60934, 2);
    $s['locked'] = $physical['locked'];
    $s['exterior_color'] = $physical['exterior_color'];
    $s['car_version'] = $physical['car_version'];

    //charge state
    $reply = $tesla->get("vehicles/" . $technicalid . "/data_request/charge_state");
    $charge = $reply["response"];
    $c['charging_state'] = $charge["charging_state"];
    $c['battery_range'] = round($charge["battery_range"], 0);
    $c['battery_rangekm'] = round($charge["battery_range"] * 1.60934, 0);
    $c['est_battery_range'] = round($charge["est_battery_range"], 0);
    $c['est_battery_rangekm'] = round($charge["est_battery_range"] * 1.60934, 0);
    $c['ideal_battery_range'] = round($charge["ideal_battery_range"], 0);
    $c['ideal_battery_rangekm'] = round($charge["ideal_battery_range"] * 1.60934, 0);
    $c['battery_level'] = $charge["battery_level"];
    $c['usable_battery_level'] = $charge["usable_battery_level"];
    $c['charge_energy_added'] = $charge["charge_energy_added"];
    $c['charge_miles_added_rated'] = round($charge["charge_miles_added_rated"], 0);
    $c['charge_kms_added_rated'] = round($charge["charge_miles_added_rated"] * 1.60934, 0);
    $c['charge_miles_added_ideal'] = round($charge["charge_miles_added_ideal"], 0);
    $c['charge_kms_added_ideal'] = round($charge["charge_miles_added_ideal"] * 1.60934, 0);

    if ($typical == 0){
        $s['consumption'] = 0;
    }else{
        // calculate consumption
        $s['consumption'] = CalculateEnergy($typical - $c['ideal_battery_rangekm']);
    }

    $d['state'] = $s;
    $d['charge']= $c;

    return array("key" => "cardata", "data" => $d);
}

