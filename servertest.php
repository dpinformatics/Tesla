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
//
$route = 1;

echo 'Create New Trip<br>===============<br><br>';
// trip
$t1 = new trip();
//$t1->att('statusid',1);
if ($route == 1){
    $t1->att('name', 'Thuis - La Treve - via autoroute du soleil');
    $t1->att('date', mktime(2 ,0, 0, 10, 31, 2015));
    $t1->att('theoreticalstarttime', mktime(2, 0, 0));
}
if ($route == 2){
    $t1->att('name', 'La Treve - Ibis Nuits Saint Georges - via Nimes');
    $t1->att('date', mktime(13 ,0, 0, 11, 7, 2015));
    $t1->att('theoreticalstarttime', mktime(13, 0, 0));
}
if ($route == 3){
    $t1->att('name', 'La Treve - Ibis Nuits Saint Georges - via Orange');
    $t1->att('date', mktime(13 ,0, 0, 11, 7, 2015));
    $t1->att('theoreticalstarttime', mktime(13, 0, 0));
}
$t1->save();
echo 'Trip '.$t1->att('name').' (id : '.$t1->att('objid').') saved<br>';
$tripid = $t1->att('objid');

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
    $wp->att('theoreticalchargeneeded', 380);
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
    $wp->att('theoreticalchargetime', 10);
    $wp->att('theoreticalchargeneeded', 100);
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
    $wp->att('theoreticalchargetime', 50);
    $wp->att('theoreticalchargeneeded', 310);
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
    $wp->att('theoreticalchargetime', 30);
    $wp->att('theoreticalchargeneeded', 240);
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
    $wp->att('theoreticalchargetime', 50);
    $wp->att('theoreticalchargeneeded', 310);
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
    $wp->att('theoreticalchargetime', 20);
    $wp->att('theoreticalchargeneeded', 160);
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
    $wp->att('theoreticalchargeneeded', 150);
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
    $wp->att('theoreticalchargetime', 50);
    $wp->att('theoreticalchargeneeded', 330);
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
    $wp->att('theoreticalchargetime', 30);
    $wp->att('theoreticalchargeneeded', 250);
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
    $wp->att('theoreticalchargetime', 0);
    $wp->att('theoreticalchargeneeded', 380);
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
    $wp->att('theoreticalchargeneeded', 230);
    $wp->save();
    echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

    $wp = new waypoint();
    $wp->att('tripid', $tripid);
    $wp->att('destination', 'SuC Orange');
    $wp->att('typeid', 3);
    $wp->att('statusid', 0);
    $wp->att('theoreticaldistance', 183.9);
    $wp->att('theoreticaltypical', 213);
    $wp->att('theoreticalconsumption', 40.0);
    $wp->att('theoreticaldrivetime', 135);
    $wp->att('theoreticalchargetime', 35);
    $wp->att('theoreticalchargeneeded', 260);
    $wp->save();
    echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

    $wp = new waypoint();
    $wp->att('tripid', $tripid);
    $wp->att('destination', 'SuC Vienne');
    $wp->att('typeid', 3);
    $wp->att('statusid', 0);
    $wp->att('theoreticaldistance', 183.2);
    $wp->att('theoreticaltypical', 239);
    $wp->att('theoreticalconsumption', 45.0);
    $wp->att('theoreticaldrivetime', 110);
    $wp->att('theoreticalchargetime', 30);
    $wp->att('theoreticalchargeneeded', 250);
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
    $wp->att('theoreticalchargetime', 0);
    $wp->att('theoreticalchargeneeded', 380);
    $wp->save();
    echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
}

echo '<br>done<br>';
exit();

/*
// waypoints
$tripid = 10;
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
$wp->att('theoreticalchargeneeded', 380);
$wp->save();
echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

$wp = new waypoint();
$wp->att('tripid', $tripid);
$wp->att('destination', 'SuC Châlons-en-Champagne');
$wp->att('typeid', 3);
$wp->att('statusid', 0);
$wp->att('theoreticaldistance', 285.2);
$wp->att('theoreticaltypical', 371);
$wp->att('theoreticalconsumption', 69.0);
$wp->att('theoreticaldrivetime', 150);
$wp->att('theoreticalchargetime', 30);
$wp->att('theoreticalchargeneeded', 240);
$wp->save();
echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

$wp = new waypoint();
$wp->att('tripid', $tripid);
$wp->att('destination', 'SuC Nivelles-Sud');
$wp->att('typeid', 3);
$wp->att('statusid', 0);
$wp->att('theoreticaldistance', 227.0);
$wp->att('theoreticaltypical', 228);
$wp->att('theoreticalconsumption', 42.0);
$wp->att('theoreticaldrivetime', 170);
$wp->att('theoreticalchargetime', 20);
$wp->att('theoreticalchargeneeded', 120);
$wp->save();
echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';

$wp = new waypoint();
$wp->att('tripid', $tripid);
$wp->att('destination', 'Brecht (thuis)');
$wp->att('typeid', 3);
$wp->att('statusid', 0);
$wp->att('theoreticaldistance', 108.5);
$wp->att('theoreticaltypical', 114);
$wp->att('theoreticalconsumption', 21.0);
$wp->att('theoreticaldrivetime', 75);
$wp->att('theoreticalchargetime', 0);
$wp->att('theoreticalchargeneeded', 0);
$wp->save();
echo 'Waypoint '.$wp->att('destination').' (id : '.$wp->att('objid').') saved<br>';
*/




// tripstatus
/*
$wps1 = new waypointstatus();
$wps1->att('objid', 0);
$wps1->att('name_en', 'Driving');
$wps1->save();
*/
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
/*
    $ts->att('name_en', 'Started');
    $ts->save();
*/


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
