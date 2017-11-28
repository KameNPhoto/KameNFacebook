<?php

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

function uploadPhotosToAlbum($version, $token, $ID) {
  $data = array();
  $files = array();
  echo "Dossier de stockage des photos : ";
  $h = fopen("php://stdin","r");
  $l = fgets($h);
  $fold = realpath(trim($l));
  fclose($h);
  echo "Folder is : $fold".PHP_EOL;
  echo "Content is : \n";
  $folder = scandir($fold);
  print_r($folder);
  foreach($folder as $value) {
    $fullpath=$folder.'/'.$value;
    if (is_file($folder.'/'.$value)) {
      $files[] = $folder.'/'.$value;
    }
  }
  print_r($files);
}
