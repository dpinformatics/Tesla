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
            $tripId = strtolower($_REQUEST['tripid']);

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

    function GetTripDetail($tripId)
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
                        $startdrivetime = mktime(0, 0, 0, 1, 1 , 2000);//$trip->att('theoreticalstarttime');
                        if ($tripId < 8 || $tripId == 11){
                            $startdrivetime = mktime(2, 0, 0, 1, 1 , 2000);//$trip->att('theoreticalstarttime');
                        }
                        if ($tripId == 8 || $tripId == 9){
                            $startdrivetime = mktime(13, 0, 0, 1, 1 , 2000);//$trip->att('theoreticalstarttime');
                        }
                        if ($tripId == 10){
                            $startdrivetime = mktime(10, 0, 0, 1, 1 , 2000);//$trip->att('theoreticalstarttime');
                        }

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
                        $startdrivetime = $wparrivaltime + ($wp['theoreticalchargetime'] * 60);
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
                    $wpchargetime = $wp['theoreticalchargetime'];
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
