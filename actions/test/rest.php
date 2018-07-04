<?php
$api = new RestClient;

$result = $api->post("http://localhost:8001/?act=api", [
    'user' => 'admin', 'pass' => 'Pass1234'
]);

//var_dump($result);

$response_array= get_object_vars($result->decode_response());
$response_json=json_encode($response_array, JSON_PRETTY_PRINT);

// $this->assertEquals('POST',
//     $response_json->SERVER->REQUEST_METHOD);
// $this->assertEquals("foo=+bar&baz=1&bat%5B%5D=foo&bat%5B%5D=bar",
//     $response_json->body);

echo $this->html->pre_display($response_json, "response_json");
