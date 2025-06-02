<?php

// Do some checks

error_reporting(E_ALL);

// https://stackoverflow.com/a/25320265
function emptyObj( $obj ) {
    foreach ( $obj AS $prop ) {
        return FALSE;
    }

    return TRUE;
}


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

$count = 0;

$tests = array(
'bad doi' => 0,
'bad data' => 0,
'no repo' => 0,
'bad repo title' => 0
);

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
	$json = preg_replace('/^\[/', '', $json);
	$json = preg_replace('/,$/', '', $json);
	$json = preg_replace('/\]$/', '', $json);

	$doc = json_decode($json);
	
	//print_r($doc);
	
	
	
	if ($doc)
	{	
		
		if (!preg_match('/(https?:\/\/(dx\.)?doi.org)?(10\.[0-9]{4,}(?:\.[0-9]+)*(?:\/|%2F)(?:(?![\"&\'])\S)+)/', $doc->publication))
		{
			echo $doc->id . ' ' . $doc->publication . "\n";
			$tests['bad doi']++;
		}
		
		if (preg_match('/^\s*[\.|-|:|_]/', $doc->dataset))
		{
			//echo $doc->dataset . "\n";
			$tests['bad data']++;
		}

		if (preg_match('/\s/', $doc->dataset))
		{
			//echo $doc->dataset . "\n";
			$tests['bad data']++;
		}
	
		
		if (emptyObj($doc->repository))
		{
			//echo $doc->id . "\n";
			$tests['no repo']++;
		}
		else
		{
			if (isset($doc->repository->title))
			{
				/*
				if (trim($doc->repository->title) == "")
				{
					$tests['bad repo title']++;
					echo $doc->id . "\n";
				}
				*/
			}
		}
		
		$count++;
		
		if ($count > 1000000)
		{
			print_r($tests);
			exit();
		}
	
	}
	else
	{
		echo "Expected JSON object on single line (JSONL) but got: $json\n";
		print_r($tests);
	}
}



?>
