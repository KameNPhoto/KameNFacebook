<?php

function createAlbum() {
  global $config;
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

  $uri = "https://graph.facebook.com/".$config['version']."/me/albums?fields=name,id,can_upload,count";
  $ret = doPostRequest($uri, $config['pageToken'], $data);
  return $ret['id'];
}

function checkAlbumID($ID) {
  global $config;
  $uri = "https://graph.facebook.com/".$config['version']."/me/albums?fields=name,id,can_upload,count";
  $ret = doGetRequest($uri, $config['pageToken']);
  foreach ($ret['data'] as $key=>$value) {
    if ($value['can_upload']) {
      if ($value['id'] == $ID) {
        return TRUE;
      }
    }
  }
  return FALSE;
}

function listAlbums() {
  global $config;
  $uri = "https://graph.facebook.com/".$config['version']."/me/albums?fields=name,id,can_upload,count";
  $ret = doGetRequest($uri, $config['pageToken']);
  foreach ($ret['data'] as $key=>$value) {
    if ($value['can_upload']) {
      echo $value['id']." -> ".$value['name']." (".$value['count'].")\n";
    }
  }
}

function getPhotoList($albumID) {
  global $config;
  $output = array();
  $uri = "https://graph.facebook.com/".$config['version']."/".$albumID."/photos?fields=name&limit=100";

  $loop = TRUE;
  while ($loop) {
    $return = doGetRequest($uri, $config['pageToken']);
    $output = array_merge($output, $return['data']);
    if (isset($return['paging']['next'])) {
      $uri = $return['paging']['next'];
    } else {
      $loop = FALSE;
    }
  }
  return $output;
}

function uploadPhotoToAlbum($albumID, $link, $caption) {
  global $config;
  $data = array();
  $files = array();
  $data['caption'] = $caption;
  $data['url'] = $link;
  $data['no_story'] = true;
  $uri = "https://graph.facebook.com/".$config['version']."/".$albumID."/photos";
  $ret = doPostRequest($uri, $config['pageToken'], $data);
  if ($ret == FALSE) {
    return FALSE;
  } else {
    return TRUE;
  }
}

function uploadPhotosToAlbum($albumID) {
  global $config;
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
    $header[] = "Authorization: OAuth ".$config['pageToken'];
    $header[] = "Content-Type:multipart/form-data";
    $photo = new FacebookFile($value);
    $data['caption'] = $p.$key.")";
    $data['source'] = $photo;
    echo "Uploading to album $albumID :".PHP_EOL;
    print_r($data);

    $uri = "https://graph.facebook.com/".$config['version']."/".$albumID."/photos";
    $ret = doPostHeaderRequest($uri, $header, $data);
    print_r($ret);
    echo PHP_EOL;
    echo "============================================================================================================".PHP_EOL;
  }
}
