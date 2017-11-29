<?php

function doGetRequest($uri, $token = '') {
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  if ($token !== '') { curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $token")); };
  $return = curl_exec($curl);
  curl_close($curl);
  return json_decode($return, true);
}

function doPostRequest($uri, $token, array $data = array()) {
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
  curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $token"));
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  $return = curl_exec($curl);
  curl_close($curl);
  return json_decode($return, true);
}

function doPostFileRequest($uri, array $data = array(), array $header = array()) {
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
  curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
  curl_setopt($curl, CURLOPT_HEADER, true);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  $return = curl_exec($curl);
  curl_close($curl);
  print_r($return);
  return json_decode($return, true);
}
