<?php

/**
 * Created by PhpStorm.
 * User: Diego
 * Date: 28/10/2015
 * Time: 10:02
 */
class WaypointType extends RootObject
{
    const TYPE_STARTPOINT = 1;
    const TYPE_ENDPOINT = 2;
    const TYPE_CHARGEPOINT_SUC = 3;         // waypoint with charging : SuperCharger
    const TYPE_CHARGEPOINT_CHA = 4;         // waypoint with charging : Chademo
    const TYPE_CHARGEPOINT_3_32 = 5;        // waypoint with charging : 22 kW : 3 fase 32A
    const TYPE_CHARGEPOINT_3_16 = 6;        // waypoint with charging : 11 kW : 3 fase 16A
    const TYPE_CHARGEPOINT_1_32 = 7;        // waypoint with charging : 7,4 kW : 1 fase 32A
    const TYPE_CHARGEPOINT_1_16 = 8;        // waypoint with charging : 3,7 kW : 1 fase 16A
    const TYPE_CHARGEPOINT_1_10 = 9;        // waypoint with charging : 2,3 kW : 1 fase 10A
    const TYPE_WAYPOINT = 99;                // waypoint without charging

}