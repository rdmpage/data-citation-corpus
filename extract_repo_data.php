<?php

// Extract records for a particular repository

error_reporting(E_ALL);

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
$row_startTime = microtime(true);

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
	$json = preg_replace('/^\[/', '', $json);
	$json = preg_replace('/,$/', '', $json);
	$json = preg_replace('/\]$/', '', $json);

	$doc = json_decode($json);
	
	if ($doc)
	{	
		$doc->_id = $doc->id;
		
		// print_r($doc);
		
		if (isset($doc->repository) && isset($doc->repository->title))
		{
			if ($doc->repository->title == "European Nucleotide Archive")
			{
				echo $doc->dataset . "\n";
			}		
		}
	}
	else
	{
		echo "Expected JSON object on single line (JSONL) but got: $json\n";
		exit();
	}
}


?>
