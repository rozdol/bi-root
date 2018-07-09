<?php

$data = array();
$packages = json_decode(file_get_contents('../vendor/composer/installed.json'), TRUE);
 // Assuming that the project root is one level above the web root.
foreach ($packages as $package) {
    $data[$package['name']] = $package['version'];
}


// make a curl request to packagist.org to get the package data
// https://packagist.org/packages/vendor_X/package_Y.json
// The output is something like the following:

/*
{
    "package": {
        "name": "omnipay/dummy",
        "versions": {
            "dev-master": {},
            "v1.1.0": {},
            "v1.0.0": {}
        },
        "type": "library",
    }
}
*/

$packagist = json_decode($packagist_reponse_as_json);
if (strcmp($data['vendor_X/package_Y'], $packagist['package']['versions'][1]) < 0) {
  // Fire a composer update, send email alert, show notification or call the president
}