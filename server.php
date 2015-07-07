<?php
    // let's bootstrap our application.
    include_once('includes/bootstrap.inc.php');
    header('Content-Type: application/json');

	// array_key_lower_case(
	switch(strtolower($_REQUEST['action'])) {

		case "gettrips":
            //-----------------
            $d = GetTrips();
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

            // do the update
            $wp = new waypoint();
            $wp->retrieve($wpId);
            $wp->att("arrivaldistance", $arrivaldistance);
            $wp->att("arrivaltypical", $arrivaltypical);
            $wp->att("arrivalconsumption", $arrivalconsumption);
            $wp->att("arrivaltime", time());
            $wp->att('statusid', WaypointStatus::STATUS_ARRIVED);
            $wp->save();

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

            // do the update
            $wp = new waypoint();
            $wp->retrieve($wpId);
            $wp->att('departuredistance', $departuredistance);
            $wp->att('departuretypical', $departuretypical);
            $wp->att('departureconsumption', $departureconsumption);
            $wp->att('departuretime', time());
            $wp->att('statusid', WaypointStatus::STATUS_LEFT);
            $wp->save();

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        default:
            echo '';
            break;

	}


    function GetTrips()
    {
    //-----------------------------------------

        // trips
        $trip = new trip();
        $trips = $trip->getAllObjectsArray(null, array('objid', 'date', 'statusid', 'name', 'theoreticalstarttime'));

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

        return $d;

    }

    function GetTripDetail($tripId)
    {
        //-----------------------------------------

        // selected trip
        $trip = new trip();
        $trip->retrieve($tripId);

        $waypoint = new waypoint();
        $waypoints = $waypoint->getAllObjectsArray('tripid = '. DB::qstr($tripId));

        // make the result array
        // initialise totals
        $totaldistance = 0;
        $totaltypical = 0;
        $totalconsumption = 0;
        $totaldrivetime = 0;
        $totalchargetime = 0;
        $wpnbr = -1;
        $statusid = 4;
        $startdrivetime = $trip->att('theoreticalstarttime');

        // build the waypoints array
        $dwp = null;

        foreach ($waypoints as &$wp) {
            if ($statusid == 4){
                $wpnbr++;
                $statusid = $wp['statusid'];
            }
            // make running totals
            if ($wp['statusid'] == 0){
                $totaldistance += $wp['theoreticaldistance'];
                $totaltypical += $wp['theoreticaltypical'];
                $totalconsumption += $wp['theoreticalconsumption'];
                $wpdistance = $wp['theoreticaldistance'];
                $wptypical = $wp['theoreticaltypical'];
                $wpconsumption = $wp['theoreticalconsumption'];
            }
            else{
                $totaldistance += $wp['arrivaldistance'];
                $totaltypical += $wp['arrivaltypical'];
                $totalconsumption += $wp['arrivalconsumption'];
                $wpdistance = $wp['arrivaldistance'];
                $wptypical = $wp['arrivaltypical'];
                $wpconsumption = $wp['arrivalconsumption'];
            }
            if ($wpdistance <> 0){
                $wpaverage = round( ($wpconsumption * 1000)/ $wpdistance, 0);
            }
            else{
                $wpaverage = '';
            }
            // to calculate
            $wpdrivetime = '';
            $totaldrivetime = '';
            $wpchargetime = '';
            $totalchargetime = '';
            $chargeneeded = $wp['theoreticalchargeneeded'];
            $wparrivaltime = '';
            $wpdeparturetime = '';


            // build the waypoint
            $dwp[] = array('id' => $wp['objid']
            , 'location' => ''
            , 'name' => $wp['destination']
            , 'overview' =>
                    array('distance' => $wpdistance
                    , 'totaldistance' => $totaldistance
                    , 'typical' => $wptypical
                    , 'totaltypical' => $totaltypical
                    , 'consumption' => $wpconsumption
                    , 'totalconsumption' => $totalconsumption
                    , 'averageconsumption' => $wpaverage
                    , 'drivetime' => $wpdrivetime
                    , 'totaldrivetime' => $totaldrivetime
                    , 'chargetime' => $wpchargetime
                    , 'totalchargetime' => $totalchargetime
                    , 'chargeneeded' => $chargeneeded
                    , 'arrivaltime' => $wparrivaltime
                    , 'departuretime' => $wpdeparturetime
                    )
            , 'theoretical' =>
                    array('arrival' =>
                        array('distance' => $wp['theoreticaldistance']
                            , 'typical' => $wp['theoreticaltypical']
                            , 'consumption' => $wp['theoreticalconsumption']
                            , 'time' => $wp['theoreticaldrivetime']
                        )
                    , 'chargestart' =>
                        array('typical' => ''
                        , 'time' => ''
                        )
                    , 'chargeend' =>
                        array('typical' => $wp['theoreticalchargeneeded']
                        , 'time' => ''
                        )
                    , 'departure' =>
                        array('distance' => 0
                        , 'typical' => $wp['theoreticalchargeneeded']
                        , 'consumption' => 0
                        , 'time' => ''
                        )
                    )
            , 'efffective' =>
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
        }

        // final result
        $d = array('id' => $tripId
        , 'desc' => $trip->att('name')
        , 'statusid' => $statusid
        , 'etape' => $wpnbr
        , 'waypoints' => $dwp
        );
        return $d;
    }
