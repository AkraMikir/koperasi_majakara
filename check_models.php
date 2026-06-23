<?php
$key = "AQ.Ab8RN6KuxDN01qxK3q9KemxrLlJZl30WgTRxBK5Ec5QdjvDVRQ";
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $key;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
echo $result;
