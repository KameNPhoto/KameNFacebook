<?php

function moveFileToPublished($src, $dest) {
  preg_match('#(/.+)/(.+?)$#',$src,$s);
  preg_match('#(/.+)/(.+?)$#',$dest,$d);
  if (! is_dir($d[1])) {
    echo "Creating destination directory : ".$d[1].PHP_EOL;
    mkdir($d[1], 0755, TRUE);
  }
  echo "Prepare to move ".$s[2]." from folder ".$s[1]." to folder ".$d[1].PHP_EOL;
  copy($src, $dest);
  unlink($src);
  clearstatcache();
  return;
}

function resizeImageFromFolder($folder, $resize) {
  global $debug;
  $sourceFolder = $folder;
  $resizeFolder = $folder.'/'.$resize;
  $dir = scandirStrict($sourceFolder);
  if (!is_dir($resizeFolder)) { mkdir($resizeFolder, 0755, TRUE); }
  foreach($dir as $photo) {
    if ($debug) { echo "Resizing image ".$photo.PHP_EOL; }
    if (file_exists($resizeFolder."/".$photo)) { continue; }
    $resized = new Imagick($sourceFolder."/".$photo);
    $resized->resizeImage($resize,$resize,Imagick::FILTER_CATROM,1,TRUE);
    $resized->writeImage($resizeFolder."/".$photo);
  }
}
