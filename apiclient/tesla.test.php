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
        echo "<tr><td>Name</td><td><strong>" . $v["display_name"] . "</strong></td></tr>";
        echo "<tr><td>VIN</td><td><strong>" . $v["vin"] . "</strong></td></tr>";
        echo "<tr><td>State</td><td><strong>" . $v["state"] . "</strong></td></tr>";
        echo "<tr><td>ID</td><td><strong>" . $v["vehicle_id"] . "</strong></td></tr>";
        echo "<tr><td>Technical ID</td><td><strong>" . $v["id"] . "</strong></td></tr>";


        // let's add physical state information...
        $reply = $tesla->get("vehicles/" . $v["id"] . "/data_request/vehicle_state");
        $physical = $reply["response"];

        echo "<tr><td>Car Type</td><td><strong>" . $physical["car_type"] . "</strong></td></tr>";
        echo "<tr><td>Software Version</td><td><strong>" . $physical["car_version"] . "</strong></td></tr>";
        echo "<tr><td>Colour</td><td><strong>" . $physical["exterior_color"] . "</strong></td></tr>";
        echo "<tr><td>Locked</td><td><strong>" . ($physical["locked"] ? "Yes" : "No") . "</strong></td></tr>";
        echo "<tr><td>ODO</td><td><strong>" . round($physical["odometer"] * 1.60934, 2) . "</strong> km</td></tr>";


        echo "</table></fieldset>";

        $reply = $tesla->post("vehicles/" . $v["id"] . "/command/honk_horn");
        echo "Sorry, I just liked honking your car's horn ;-)";


    }


    echo "<br/><br/><br/>";
    //var_dump($tesla->get("vehicles"));


    echo "</pre>";
