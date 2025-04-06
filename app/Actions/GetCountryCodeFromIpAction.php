<?php

namespace App\Actions;

class GetCountryCodeFromIpAction {
    public static function execute($ip) {
        $url = "http://ip-api.com/json/{$ip}";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['countryCode'])) {
            return $data['countryCode'];
        } else {
            return null; // or handle the error as needed
        }
    }
}