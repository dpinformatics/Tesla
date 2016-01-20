<?php
    include_once("tesla.class.php");

    $tesla = new TeslaClient("e4a9949fcfa04068f59abb5a658f2bac0a3428e4652315490b659d5ab3f35a9e", "c75f14bbadc8bee3a7594412c31416f8300256d7668ea7e6e7f06727bfb9d220");

    echo "<pre>";

    // login to the tesla api
    // for demonstration purposes, we're taking user and password from the request parameters.

    if (!isset($_REQUEST["email"]) || !isset($_REQUEST["password"])) {
        echo "<strong>You need to supply credentials!</strong>";
        echo "<br/>add them to the request using parameters <strong>email</strong> and <strong>password</strong>";
        exit();
    }
    $tesla->auth($_REQUEST["email"], $_REQUEST["password"]);

    // let's get vehicle information
    $reply = $tesla->get("vehicles");
    $vehicles = $reply["response"];

    echo "<hr/>Wow!  You have <strong>" . count($vehicles) . "</strong> Tesla car" . (count($vehicles) > 1 ? "s" : "") . "!";
    foreach ($vehicles as $v) {
        echo "<fieldset><legend>" . $v["display_name"] . "</legend><table>";
        echo "<tr><td colspan='2'><strong>CAR IDENTIFICATION</strong></td>";
        echo "<tr><td>Name</td><td><strong>" . $v["display_name"] . "</strong></td></tr>";
        echo "<tr><td>VIN</td><td><strong>" . $v["vin"] . "</strong></td></tr>";
        echo "<tr><td>State</td><td><strong>" . $v["state"] . "</strong></td></tr>";
        echo "<tr><td>ID</td><td><strong>" . $v["vehicle_id"] . "</strong></td></tr>";
        echo "<tr><td>Technical ID</td><td><strong>" . $v["id"] . "</strong></td></tr>";


        // let's add physical state information...
        $reply = $tesla->get("vehicles/" . $v["id"] . "/data_request/vehicle_state");
        $physical = $reply["response"];

        echo "<tr><td colspan='2'><strong>CAR INFORMATION</strong></td>";
        echo "<tr><td>Car Type</td><td><strong>" . $physical["car_type"] . "</strong></td></tr>";
        echo "<tr><td>Software Version</td><td><strong>" . $physical["car_version"] . "</strong></td></tr>";
        echo "<tr><td>Colour</td><td><strong>" . $physical["exterior_color"] . "</strong></td></tr>";
        echo "<tr><td>Locked</td><td><strong>" . ($physical["locked"] ? "Yes" : "No") . "</strong></td></tr>";
        echo "<tr><td>ODO</td><td><strong>" . round($physical["odometer"] * 1.60934, 2) . "</strong> km</td></tr>";

        // what about charging data...
        $reply = $tesla->get("vehicles/" . $v["id"] . "/data_request/charge_state");
        $charge = $reply["response"];

        echo "<tr><td colspan='2'><strong>CHARGING INFORMATION</strong></td>";
        echo "<tr><td>Charging state</td><td><strong>" . $charge["charging_state"] . "</strong></td></tr>";
        echo "<tr><td>Range - RATED</td><td><strong>" . number_format(round($charge["battery_range"] * 1.60934, 2), 2) . "</strong> km</td></tr>";
        echo "<tr><td>Range - ESTIMATED</td><td><strong>" . number_format(round($charge["est_battery_range"] * 1.60934, 2), 2) . "</strong> km</td></tr>";
        echo "<tr><td>Range - TYPICAL</td><td><strong>" . number_format(round($charge["ideal_battery_range"] * 1.60934, 2), 2) . "</strong> km</td></tr>";
        echo "<tr><td>Battery level</td><td><strong>" . number_format(round($charge["battery_level"], 2), 2) . "</strong> %</td></tr>";
        echo "</table></fieldset>";

        //$reply = $tesla->post("vehicles/" . $v["id"] . "/command/honk_horn");
        //echo "Sorry, I just liked honking your car's horn ;-)";
        echo "<fieldset><legend>" . $v["display_name"] . " streaming data</legend>";
        echo "Data will show up after 2 minutes max.";

        $start = time();
        echo "<table><tr><th>timestamp</th><th>speed</th><th>odometer</th><th>soc</th><th>elevation</th><th>est_heading</th><th>est_lat</th><th>est_lng</th><th>power</th><th>shift_state</th><th>range</th><th>est_range</th><th>heading</th></tr>";

        while ($start + 1200 > time()) {
            flush();
            $data = $tesla->stream($v["vehicle_id"], $v["tokens"][0]);
            $exploded = explode(",", $data);
            echo "<tr>";

            echo "<td>" . date("c", $exploded[0] / 1000) . "</td>"; // timestamp
            echo "<td>" . $exploded[1] . "</td>";                   // speed
            echo "<td>" . number_format(round($exploded[2] * 1.60934, 2), 2) . " km</td>";                   // odometer
            echo "<td>" . number_format(round($exploded[3], 2), 2) . " %</td>";                   // soc
            echo "<td>" . $exploded[4] . "</td>";                   // elevation
            echo "<td>" . $exploded[5] . "</td>";                   //est_heading
            echo "<td>" . $exploded[6] . "</td>";                   //est_lat
            echo "<td>" . $exploded[7] . "</td>";                   //est_lng
            echo "<td>" . $exploded[8] . "</td>";                   //power
            echo "<td>" . $exploded[9] . "</td>";                   //shift_state
            echo "<td>" . number_format(round($exploded[10] * 1.60934, 2), 2) . " km</td>";                  //range
            echo "<td>" . number_format(round($exploded[11] * 1.60934, 2), 2) . " km</td>";                // est_range
            echo "<td>" . $exploded[12] . "</td>";                  // heading

            echo "</tr>";


        }


        echo "</table></fieldset>";

    }


    echo "<br/><br/><br/>";
    //var_dump($tesla->get("vehicles"));


    echo "</pre>";
