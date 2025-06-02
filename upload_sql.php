<?php

// Generate simple SQL

error_reporting(E_ALL);


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
		//print_r($doc);
		
		$record = new stdclass;
		
		$record->id = $doc->id;
		
		if (isset($doc->repository))
		{
			if (isset($doc->repository->title))
			{
				$record->repository = $doc->repository->title;
			}
		}

		if (isset($doc->publisher))
		{
			if (isset($doc->publisher->title))
			{
				$record->publisher = $doc->publisher->title;
			}
		}

		if (isset($doc->journal))
		{
			if (isset($doc->journal->title))
			{
				$record->journal = $doc->journal->title;
			}
		}

		if (isset($doc->title) && $doc->title != '')
		{
			$record->title = $doc->title;
		}
				
		$record->publication = $doc->publication;
		$record->dataset = $doc->dataset;
		$record->source = $doc->source;
		
		// SQL
		$keys = array();
		$values = array();

		foreach ($record as $k => $v)
		{
			$keys[] = '"' . $k . '"'; // must be double quotes
			
			if (!$v)
			{
				$values[] = 'NULL';
			}
			elseif (is_array($v))
			{
				$values[] = "'" . str_replace("'", "''", json_encode(array_values($v))) . "'";
			}
			elseif(is_object($v))
			{
				$values[] = "'" . str_replace("'", "''", json_encode($v)) . "'";
			}
			else
			{				
				$values[] = "'" . str_replace("'", "''", $v) . "'";
			}					
		}
		
		echo 'INSERT INTO v2(' . join(',', $keys) . ') VALUES (' . join(',', $values) . ') ON CONFLICT DO NOTHING;' . "\n";


	}
	else
	{
		echo "-- Expected JSON object on single line (JSONL) but got: $json\n";
		exit();
	}
}


?>
