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
    const TYPE_CHARGEPOINT = 3;         // waypoint with charging
    const TYPE_WAYPOINT = 4;            // waypoint without charging

}