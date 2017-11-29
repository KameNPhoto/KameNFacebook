<?php

use Facebook\FileUpload\FacebookFile;

function createAlbum($version, $pageToken) {
  $data = array();
  echo "Nom de l'album : ";
  $h = fopen("php://stdin","r");
  $l = fgets($h);
  $data['name'] = trim($l);
  fclose($h);

  echo "Description : ";
  $h = fopen("php://stdin","r");
  $l = fgets($h);
  $data['message'] = trim($l);
  fclose($h);

  $ret = doPostRequest("https://graph.facebook.com/$version/me/albums?fields=name,id,can_upload,count,event", $pageToken, $data);
  return $ret['id'];
}

function checkAlbumID($version, $pageToken, $ID) {
  $uri = "https://graph.facebook.com/$version/me/albums?fields=name,id,can_upload,count";
  $ret = doGetRequest($uri, $pageToken);
  foreach ($ret['data'] as $key=>$value) {
    if ($value['can_upload']) {
      if ($value['id'] == $ID) {
        return true;
      }
    }
  }
  return false;
}

function uploadPhotosToAlbum($version, $token, $albumID) {
  $data = array(); $header = array();
  $files = array();
  // Récupération du dossier source des photos
  echo "Dossier de stockage des photos : ";
  $h = fopen("php://stdin","r");
  $l = fgets($h);
  $ll = preg_replace('/~/',$_ENV['HOME'],$l);
  $f = realpath(trim($ll));
  fclose($h);
  // Récupération de l'identifiant photographe
  echo "Identifiant de photographe : ";
  $h = fopen("php://stdin","r");
  $p = trim(fgets($h));
  fclose($h);
  // Analyse du dossier à la recherche de photos
  $folder = scandir($f);
  foreach($folder as $value) {
    if (preg_match('/^\.+$/',$value)) { continue; }
    if (!preg_match('/.+\.jpg$/i',$value)) { continue; }
    $fullpath=$f.'/'.$value;
    preg_match('/([0-9]{1,4})\./',$value, $id);
    if (is_file($fullpath)) {
      $files[$id[1]] = $fullpath;
      #$photoid[$id[1]] = $id[1];
    }
  }
  echo "============================================================================================================".PHP_EOL;
  foreach ($files as $key=>$value) {
    $header[] = "Authorization: OAuth $token";
    $header[] = "Content-Type:multipart/form-data";
    $photo = new FacebookFile($value);
    $data['caption'] = $p.$key.")";
    $data['source'] = $photo;
    echo "Uploading to album $albumID :".PHP_EOL;
    print_r($data);

    $ret = doPostFileRequest("https://graph.facebook.com/$version/me/photos", $data, $header);
    print_r($ret);
    echo PHP_EOL;
    echo "============================================================================================================".PHP_EOL;
  }
}
