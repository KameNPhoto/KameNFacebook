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
