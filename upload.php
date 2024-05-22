<?php


error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/couchsimple.php');

$force = false;

$filename = '';
if ($argc < 2)
{
	echo "Usage: upload.php <JSON file> \n";
	exit(1);
}
else
{
	$filename = $argv[1];
}

$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

$count = 1;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
	$json = trim(fgets($file_handle));
	$json = preg_replace('/^\[/', '', $json);
	$json = preg_replace('/,$/', '', $json);
	$json = preg_replace('/\]$/', '', $json);

	$doc = json_decode($json);
	
	if ($doc)
	{	
		$doc->_id = $doc->id;
		
		print_r($doc);
		
		$go = true;

		// Check whether this record already exists (i.e., have we done this object already?)
		$exists = $couch->exists($doc->_id);

		if ($exists)
		{
			$go = false;

			if ($force)
			{
				$couch->add_update_or_delete_document(null, $doc->_id, 'delete');
				$go = true;		
			}
		}

		if ($go)
		{
			$resp = $couch->send("PUT", "/" . $config['couchdb_options']['database'] . "/" . urlencode($doc->_id), json_encode($doc));
			var_dump($resp);							
		}	
		
		// Give server a break every 100 items
		if (($count++ % 100) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}		
		
	}
	else
	{
		echo "Expected JSON object on single line (JSONL) but got: $json\n";
		exit();
	}
}


?>
