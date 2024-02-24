<?php
function connectToPositionStackAPI($apiKey, $query) {
    $url = "http://api.positionstack.com/v1/forward?access_key=$apiKey&query=" . urlencode($query);

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'GET',
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) { 
        return "An error occured while fetching the data";
    }

    return $result;
}

function parseAPIResponse($response) {
    return json_decode($response, true);
}

function getAPIDataParams($APIData) {
    $arrayCount = count($APIData["data"]);
    if ($arrayCount === 0) { //If no data was received correctly
        return array( //Set default values to skip this address in the order line
            "latitude"=> 0.0,
            "longitude"=> 0.0,
        );
    }
    $APIDataParams = array( //Update coordinates when correctly received the data
        "latitude" => $APIData["data"][0]["latitude"],
        "longitude" => $APIData["data"][0]["longitude"],
    );
    return $APIDataParams;
}
function getAPIData($apiKey, AddressData $address) {
    $APIDataResult = connectToPositionStackAPI($apiKey, $address->getAddress());
    if ($APIDataResult === "An error occured while fetching the data") {
        return null;
    }
    $parsedResult = parseAPIResponse($APIDataResult);
    $APIDataParams = getAPIDataParams($parsedResult);
    return $APIDataParams;
}
