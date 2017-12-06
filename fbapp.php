<?php
// This page need to be hosted on a public server and configured as callback on facebook app properties.
echo "<html><head><title>Kame'N Photo Facebook App</title></head>";
echo "<body><p>Enter the following code in the application :<br /></p><p>";
echo '<textarea rows="6" cols="80">'.$_GET['code'].'</textarea>';
echo "</p></body></html>";
?>
