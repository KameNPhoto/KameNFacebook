<?php

function writeToken() {
  global $config;
  $fp = fopen('token.php','w');
  fputs($fp, "<?php\n");
  fputs($fp, '$config[\'accessToken\'] = "'.$config['accessToken'].'";'."\n");
  fputs($fp, '$config[\'pageToken\'] = "'.$config['pageToken'].'";'."\n");
  fclose($fp);
}

function getAccessToken() {
  global $config;
  echo "Open this URL in a web browser and allow access to the Facebook Graph on behalf of your user account:\n";
  echo "    https://www.facebook.com/".$config['version']."/dialog/oauth?client_id=".$config['app_id']."&redirect_uri=".$config['redirect_uri']."&scope=email,public_profile,manage_pages,publish_pages\n";
  sleep(10);
  echo "\n\nEnter the code given by the webpage after authenticating in facebook : ";
  $handle = fopen ("php://stdin","r");
  $line = fgets($handle);
  $access_code = trim($line);
  fclose($handle);
  $uri="https://graph.facebook.com/v2.11/oauth/access_token?client_id=".$config['app_id']."&redirect_uri=".$config['redirect_uri']."&client_secret=".$config['app_secret']."&code=".$access_code;
  $ret = doGetRequest($uri);
  $shortAccessToken = $ret['access_token'];
  $uri = "https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=".$config['app_id']."&client_secret=".$config['app_secret']."&fb_exchange_token=".$shortAccessToken;
  $ret = doGetRequest($uri);
  $config['accessToken'] = $ret['access_token'];
  writeToken();
}

function getPageToken() {
  global $config;
  $pageToken = '';
  $uri = "https://graph.facebook.com/".$config['version']."/me/accounts?fields=access_token";
  $ret = doGetRequest($uri, $config['accessToken']);
  foreach ($ret['data'] as $key=>$value) {
    if ($value['id'] == $config['pageID']) {
      $config['pageToken'] = $value['access_token'];
      writeToken();
    }
  }
}

