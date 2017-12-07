<?php

function doGetRequest($uri, $token = '') {
  global $debug;
  fwrite(STDERR, "");
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, TRUE);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  if ($token !== '') { curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $token")); };
  $ret = curl_exec($curl);
  $return = json_decode($ret, TRUE);
  if ($debug) {
    $error = curl_error($curl);
    $header_size = curl_getinfo($curl,CURLINFO_HEADER_SIZE);
    fwrite(STDERR, "========== Request".PHP_EOL);
    fwrite(STDERR, "URI : ".$uri.PHP_EOL);
    fwrite(STDERR, "========== HTTP CODE".PHP_EOL);
    fwrite(STDERR, curl_getinfo($curl,CURLINFO_HTTP_CODE).PHP_EOL);
    fwrite(STDERR, "========== Header".PHP_EOL);
    fwrite(STDERR, substr($ret, 0, $header_size).PHP_EOL);
    fwrite(STDERR, "========== Retour".PHP_EOL);
    fwrite(STDERR, substr($ret, $header_size).PHP_EOL);
    $return = json_decode(substr($ret, $header_size), TRUE);
    fwrite(STDERR, "========== Array".PHP_EOL);
    fwrite(STDERR, print_r($return, TRUE).PHP_EOL);
    fwrite(STDERR, str_repeat("=",80).PHP_EOL);
  }
  curl_close($curl);
  if (isset($return['error'])) {
    return FALSE;
  } else {
    return $return;
  }
}

function doPostRequest($uri, $token = '', array $data = array()) {
  global $debug;
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_POST, TRUE);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
  curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, TRUE);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  if ($token !== '') { curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $token")); };
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  $ret = curl_exec($curl);
  $return = json_decode($ret, TRUE);
  if ($debug) {
    $error = curl_error($curl);
    $header_size = curl_getinfo($curl,CURLINFO_HEADER_SIZE);
    fwrite(STDERR, "========== Request".PHP_EOL);
    fwrite(STDERR, "URI : ".$uri.PHP_EOL);
    fwrite(STDERR, "Data : ".print_r($data, TRUE).PHP_EOL);
    fwrite(STDERR, "========== HTTP CODE".PHP_EOL);
    fwrite(STDERR, curl_getinfo($curl,CURLINFO_HTTP_CODE).PHP_EOL);
    fwrite(STDERR, "========== Header".PHP_EOL);
    fwrite(STDERR, substr($ret, 0, $header_size).PHP_EOL);
    fwrite(STDERR, "========== Retour".PHP_EOL);
    fwrite(STDERR, substr($ret, $header_size).PHP_EOL);
    $return = json_decode(substr($ret, $header_size), TRUE);
    fwrite(STDERR, "========== Array".PHP_EOL);
    fwrite(STDERR, print_r($return, TRUE).PHP_EOL);
    fwrite(STDERR, str_repeat("=",80).PHP_EOL);
  }
  curl_close($curl);
  if (isset($return['error'])) {
    return FALSE;
  } else {
    return $return;
  }
}

function doPostHeaderRequest($uri, array $header = array(), array $data = array()) {
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_POST, TRUE);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
  curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, TRUE);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  $ret = curl_exec($curl);
  curl_close($curl);
  $return = json_decode($ret, TRUE);
  if (isset($return['error'])) {
    return FALSE;
  } else {
    return $return;
  }
}

function scandirStrict($folder) {
  $f = scandir($folder);
  foreach($f as $index=>$fold) {
    if (preg_match('/^\.+$/',$fold)) { unset($f[$index]); }
  }
  return $f;
}
