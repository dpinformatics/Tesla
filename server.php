<?php
    // let's bootstrap our application.
    include_once('includes/bootstrap.inc.php');


    $user = new User();
    $user->att("name", "David");
    var_dump($user);

    exit();


    header('Content-Type: application/json');

	// array_key_lower_case(
	switch(strtolower($_REQUEST["action"])) {

		case "gettrips":
            //--------------------------------------
			/*
			for($i = 0; $i < 10; $i++) {
				$return[] = array(
					"date" => time(),
					"description" => "Trip " . $i,
					"status" => "Gepland"
				);
			}
            */
			
			$d[] = array('id' =>  4,
					     'date' =>  '19-04-2015',
					     'name' => 'La Vielle Ferme - Thuis',
					     'status' => 'Gepland'
            );
            $d[] = array('id' => 3,
					     'date' => '18-04-2015',
					     'name' => 'La Treve - La Vielle Ferme (via Orange)',
					     'status'=> 'Gepland'
            );
            $d[] = array('id' => 2,
                         'date' => '18-04-2015',
                         'name' => 'La Treve - La Vielle Ferme (via Nimes)',
                         'status' => 'Bezig'
            );
            $d[] = array('id' => 1,
					     'date' => '10-04-2015',
					     'name' => 'Thuis - La Treve (via Metz)',
                         'status' => 'Beeindigd'
            );
            //echo $d;
			echo json_encode($d);
            break;


		case "gettrip":
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST["tripid"]);

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;


        case "savearrival":
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST["tripid"]);

            // do the update

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        case "savechargestart":
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST["tripid"]);

            // do the update

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        case "savechargeend":
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST["tripid"]);

            // do the update

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        case "savedeparture":
            //-------------------------------------
            // get other parameters
            $tripId = strtolower($_REQUEST["tripid"]);

            // do the update

            // retrieve the data
            echo json_encode(GetTripDetail($tripId));
            break;

        default:
            echo "";
            /*
            $d2 = "{
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
            }";
            echo $d2;
            */
            break;

	}


function GetTripDetail($tripId){
    //-----------------------------------------

    // destination
    $dest[] = array('id' => 101, 'location' => '46.346553, 4.850173', 'description' => 'SuC Macon, France-1');
    $dest[] = array('id' => 102, 'location' => '47.85183, 3.542802', 'description' => 'SuC Auxerre, France');
    $dest[] = array('id' => 103, 'location' => '49.208728, 2.605788', 'description' => 'SuC Senlis, France');
    $dest[] = array('id' => 104, 'location' => '51.019861, 3.734917', 'description' => 'SuC Ghent, Belgium');
    $dest[] = array('id' => 10, 'location' => '51.3257132, 4.5685887', 'description' => 'Home, Belgium');

    // selected trip
    $trip = array('id' =>  4, 'date' =>  '19-04-2015', 'status' => 0, 'name' => 'La Vielle Ferme - Thuis', 'theoreticalstarttime' => '10:00');

    // waypoints of a selected trip
    $wps[] = array('tripid'=> 4, 'wpid' => 41, 'destinationid' => 101, 'type' => 0, 'status' => 0
        , 'theoreticaldistance' => '', 'theoreticaltypical' => '', 'theoreticalconsumption' => '', 'theoreticaldrivetime' => '0:00', 'theoreticalchargetime' => '0:00', 'theoreticalchargeneeded' => 340
        , 'arrivaldistance' => 0, 'arrivaltypical' => 0, 'arrivalconsumption' => 0, 'arrivaltime' => '0:00'
        , 'chargestarttypical' => 0, 'chargestarttime' => '0:00'
        , 'chargeendtypical' => 0, 'chargeendtime' => '0:00'
        , 'departuredistance' => 0, 'departuretypical' => 0, 'departureconsumption' => 0, 'departuretime' => '0:00'
    );
    $wps[] = array('tripid'=> 4, 'wpid' => 42, 'destinationid' => 102, 'type' => 3, 'status' => 0
        , 'theoreticaldistance' => 232.2, 'theoreticaltypical' => 323, 'theoreticalconsumption' => 60, 'theoreticaldrivetime' => '2:05', 'theoreticalchargetime' => '0:40', 'theoreticalchargeneeded' => 280
        , 'arrivaldistance' => 0, 'arrivaltypical' => 0, 'arrivalconsumption' => 0, 'arrivaltime' => '0:00'
        , 'chargestarttypical' => 0, 'chargestarttime' => '0:00'
        , 'chargeendtypical' => 0, 'chargeendtime' => '0:00'
        , 'departuredistance' => 0, 'departuretypical' => 0, 'departureconsumption' => 0, 'departuretime' => '0:00'
    );
    $wps[] = array('tripid'=> 4, 'wpid' => 43, 'destinationid' => 103, 'type' => 3, 'status' => 0
        , 'theoreticaldistance' => 209.6, 'theoreticaltypical' => 260, 'theoreticalconsumption' => 48, 'theoreticaldrivetime' => '2:10', 'theoreticalchargetime' => '0:50', 'theoreticalchargeneeded' => 330
        , 'arrivaldistance' => 0, 'arrivaltypical' => 0, 'arrivalconsumption' => 0, 'arrivaltime' => '0:00'
        , 'chargestarttypical' => 0, 'chargestarttime' => '0:00'
        , 'chargeendtypical' => 0, 'chargeendtime' => '0:00'
        , 'departuredistance' => 0, 'departuretypical' => 0, 'departureconsumption' => 0, 'departuretime' => '0:00'
    );

    // make the result array
    // initialise totals
    $totaldistance = 0;
    $totaltypical = 0;
    $totalconsumption = 0;
    $totaldrivetime = 0;
    $totalchargetime = 0;
    $wpnbr = -1;

    // build the waypoints array
    foreach($wps as &$wp){
        // make running totals
        $totaldistance += $wp["theoreticaldistance"];
        $totaltypical += $wp["theoreticaltypical"];
        $totalconsumption += $wp["theoreticalconsumption"];

        // get specific data
        $wpnbr++;
        $destwp = $dest[$wpnbr];

        // build the waypoint
        $dwp[] = array('id' => $wp["wpid"]
            , 'location' => $destwp["location"]
            , 'desc' => $destwp["description"]
            , 'overview' =>
                array('distance' => $wp["theoreticaldistance"]
                    , 'totaldistance' => $totaldistance
                    , 'typical' => $wp["theoreticaltypical"]
                    , 'totaltypical' => $totaltypical
                    , 'consumption' => $wp["theoreticalconsumption"]
                    , 'totalconsumption' => $totalconsumption
                )
        );
    }

    // final result
    $d = array('id' => $tripId
            , 'desc' => $trip["name"]
            , 'statusid' => 3
            , 'etape' => 0
            , 'waypoints' =>$dwp
    );
    return $d;
/*
    [     id: ".$tripId.",
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
            ]";
    return $d;
*/
}