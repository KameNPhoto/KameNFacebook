<?php

function writeConfig($accessToken, $pageToken="") {
  $fp = fopen('token.php','w');
  fputs($fp, "<?php\n");
  fputs($fp, '$accessToken = "'.$accessToken.'";'."\n");
  fputs($fp, '$pageToken = "'.$pageToken.'";'."\n");
  fclose($fp);
}

function getAccessToken($version, $app_id, $app_secret, $redirect_uri) {
  echo "Open this URL in a web browser and allow access to the Facebook Graph on behalf of your user account:\n";
  echo "    https://www.facebook.com/$version/dialog/oauth?client_id=$app_id&redirect_uri=$redirect_uri&scope=email,public_profile,manage_pages,publish_pages\n";
  sleep(10);
  echo "\n\nEnter the code given by the webpage after authenticating in facebook : ";
  $handle = fopen ("php://stdin","r");
  $line = fgets($handle);
  $access_code = trim($line);
  fclose($handle);
  $uri="https://graph.facebook.com/v2.11/oauth/access_token?client_id=$app_id&redirect_uri=$redirect_uri&client_secret=$app_secret&code=$access_code";
  $ret = doGetRequest($uri);
  $shortAccessToken = $ret['access_token'];
  $uri = "https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=$app_id&client_secret=$app_secret&fb_exchange_token=$shortAccessToken";
  $ret = doGetRequest($uri);
  $accessToken = $ret['access_token'];
  writeConfig($accessToken);
  return $accessToken;
}

function getPageToken($version, $accessToken, $pageID) {
  $pageToken = '';
  $uri = "https://graph.facebook.com/$version/me/accounts?fields=access_token";
  $ret = doGetRequest($uri, $accessToken);
  foreach ($ret['data'] as $key=>$value) {
    if ($value['id'] == $pageID) {
      $pageToken = $value['access_token'];
      writeConfig($accessToken, $pageToken);
    }
  }
  return $pageToken;
}

