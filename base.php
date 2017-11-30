<?php

function doGetRequest($uri, $token = '') {
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  if ($token !== '') { curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $token")); };
  $ret = curl_exec($curl);
  curl_close($curl);
  $return = json_decode($ret, TRUE);
  if (isset($return['error'])) {
    return FALSE;
  } else {
    return $return;
  }
}

function doPostRequest($uri, $token = '', array $data = array()) {
  $curl = curl_init($uri);
  curl_setopt($curl, CURLOPT_POST, TRUE);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
  curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_TIMEOUT, 4);
  if ($token !== '') { curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $token")); };
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
