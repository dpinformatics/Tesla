<?php

    Class TeslaClient
    {
        private $tesla_client_id;
        private $tesla_client_secret;
        private $debug = false;
        private $auth = false;
        private $user = false;
        private $token = false;

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
                $this->token = $this->auth["access_token"];
            }
            return $this->token;

        }
        
        public function authWithToken($email, $token) {
            $this->user = $email;
            $this->token = $token;    
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
            // initialise curl
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, 0);  // debug true or false
            
            // initialise headers
            $headers = array();
            $headers[] = "content-type: application/x-www-form-urlencoded";
            if (is_array($customheaders)) {
                foreach ($customheaders as $custhead) {
                    $headers[] = $custhead;
                }
            }
            
            // authentication
            if (isset($this->token) && !$streamtoken) {
                // we have a token, and we are not calling the stream API
                $headers[] = "Authorization: Bearer " . $this->token;
            }
            if ($streamtoken) {
                // we are calling the stream API
                curl_setopt($ch, CURLOPT_USERPWD, $this->user . ":" . $streamtoken);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            }
            
            // set URL to call
            curl_setopt($ch, CURLOPT_URL, $url);
            
            // POST or GET?
            if ($type == "post") {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            
            // add parameters to call
            if (is_array($params) && count($params)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            
            // adding the headers to the call
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            // generic settings... whatever
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);

            // Execute call to the API server
            $return = curl_exec($ch);

            if ($returnJson) {
                // decode response to JSON object
                $return = json_decode($return, true);
            }

            // return answer.
            return $return;

        }
    }
