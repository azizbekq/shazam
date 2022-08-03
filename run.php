<?php
require "shazam-api-v1.0.php";

$api = new Dublix;
$data = $api->init('test.mp3');
print_r($data);
