<?php
// Deletes all files in the /cache directory
error_reporting(E_ALL);

//set_time_limit(360);
$start = microtime(true);
$x = 0;
foreach(new DirectoryIterator('cache') as $files)
{
    if(!$files->isDot()) // Don't delete .files!
	{
        unlink("cache/".$files->getFilename());
		$x++;
		if($x%50==0) sleep(1);
	}
}

$end = microtime(true);
echo "<br>".$x." cached banners deleted in ".round(($end - $start),4)." seconds.";
?>