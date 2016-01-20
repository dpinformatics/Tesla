<?php

    Class TeslaClient
    {
        private $tesla_client_id;
        private $tesla_client_secret;
        private $debug = false;
        private $auth = false;
        private $user = false;

        private $apiversion = 1;
        private $restUrl = "https://owner-api.teslamotors.com/api";

        public function __construct($clientId, $secret)
        {
            $this->tesla_client_id = $clientId;
            $this->tesla_client_secret = $secret;
        }

        public function auth($user, $pass)
        {
            // let's make the request to get the authentication id...
            $result = $this->request(
                "post",
                "https://owner-api.teslamotors.com/oauth/token",
                true,
                array(
                    "grant_type"    => "password",
                    "client_id"     => $this->tesla_client_id,
                    "client_secret" => $this->tesla_client_secret,
                    "email"         => $user,
                    "password"      => $pass
                )
            );
            if (isset($result["access_token"])) {
                $this->auth = $result;
                $this->user = $user; // keep for streaming possibilities
            }

        }

        public function debug($newvalue = true)
        {
            if (func_num_args() == 1) {
                $this->debug = $newvalue;
            }

            return $this->debug;
        }

        public function get($request)
        {
            $url = $this->restUrl . "/" . $this->apiversion . "/" . $request;

            return $this->request(
                "get",
                $url,
                true
            );
        }

        public function stream($vehicle, $token)
        {
            $url = "https://streaming.vn.teslamotors.com/stream/" . $vehicle; // . ' + options.vehicle_id + '/?values=' + exports.stream_columns.join(',')
            $url .= "/?values=speed,odometer,soc,elevation,est_heading,est_lat,est_lng,power,shift_state,range,est_range,heading";

            return $this->request("get", $url, false, false, false, $token);

        }

        public function post($request, $params = false)
        {
            $url = $this->restUrl . "/" . $this->apiversion . "/" . $request;

            return $this->request(
                "post",
                $url,
                true,
                $params
            );
        }

        private function request($type = "get", $url, $returnJson = true, $params = false, $customheaders = false, $streamtoken = false)
        {
            $headers = array();
            $headers[] = "content-type: application/x-www-form-urlencoded";

            if (is_array($customheaders)) {
                foreach ($customheaders as $custhead) {
                    $headers[] = $custhead;
                }
            }

            if (isset($this->auth["access_token"]) && !$streamtoken) {
                $headers[] = "Authorization: Bearer " . $this->auth["access_token"];
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);

            curl_setopt($ch, CURLOPT_URL, $url);
            if ($type == "post") {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            if (is_array($params) && count($params)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);

            if ($streamtoken) {
                curl_setopt($ch, CURLOPT_USERPWD, $this->user . ":" . $streamtoken);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $return = curl_exec($ch);


            if ($returnJson) {
                $return = json_decode($return, true);
            }


            return $return;

        }
    }