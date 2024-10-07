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

$batch = new stdclass;
$batch->docs = array();

$batch_size = 1000;

$count = 0;

$row_startTime = microtime(true);

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
	$json = preg_replace('/^\[/', '', $json);
	$json = preg_replace('/,$/', '', $json);
	$json = preg_replace('/\]$/', '', $json);

	$doc = json_decode($json);
	
	$count++;
	
	if ($doc)
	{	
		$doc->_id = $doc->id;
		
		$batch->docs[] = $doc;
		
		if (count($batch->docs) == $batch_size)
		{		
			$row_endTime = microtime(true);
			$row_executionTime = $row_endTime - $row_startTime;
			$formattedTime = number_format($row_executionTime, 3, '.', '');
			echo "Took " . $formattedTime . " seconds to process " . $batch_size . " rows.\n";
			
			$resp = $couch->send("POST", "/" . $config['couchdb_options']['database'] . "/_bulk_docs", json_encode($batch));			
			//var_dump($resp);
			
			echo "$count rows done\n";							
			
			$row_startTime = microtime(true);
			$batch->docs = array();		
		}				
	}
	else
	{
		echo "Expected JSON object on single line (JSONL) but got: $json\n";
	}
}

// any left over?
if (count($batch->docs) > 0)
{
	$resp = $couch->send("POST", "/" . $config['couchdb_options']['database'] . "/_bulk_docs", json_encode($batch));
	// var_dump($resp);	
	
	$batch->docs = array();	
	
	echo "[$count] rows done\n";							
}


?>
