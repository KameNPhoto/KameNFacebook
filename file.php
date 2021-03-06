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
  $watermarkFile = $folder.'/../water.png';
  $waterOffset = 0;
  $dir = scandirStrict($sourceFolder);
  if (!is_dir($resizeFolder)) { mkdir($resizeFolder, 0755, TRUE); }
  foreach($dir as $photo) {
    if (!is_file($sourceFolder."/".$photo)) { continue; }
    if (file_exists($resizeFolder."/".$photo)) { continue; }
    if ($debug) { fwrite(STDERR, "Resizing image ".$photo.PHP_EOL); }
    $resized = new Imagick($sourceFolder."/".$photo);
    $resized->resizeImage($resize,$resize,Imagick::FILTER_CATROM,1,TRUE);
    if (file_exists($watermarkFile)) {
      if ($debug) { fwrite(STDERR, "Adding watermark to ".$photo.PHP_EOL); }
      $watermark = new Imagick();
      $watermark->readImage($watermarkFile);
      $img_Width = $resized->getImageWidth();
      $img_Height = $resized->getImageHeight();
      $watermark_Width = $watermark->getImageWidth();
      $watermark_Height = $watermark->getImageHeight();
      $x = ($img_Width - $watermark_Width - $waterOffset);
      $y = $waterOffset;
      $resized->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
    }
    $resized->writeImage($resizeFolder."/".$photo);
    echo ".";
  }
  echo PHP_EOL;
}
