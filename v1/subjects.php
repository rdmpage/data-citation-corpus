<?php

// count number of distinct subjects

$row_count = 0;

$filename = "data-dump.json";

$subjects = array();


$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
	$json = preg_replace('/^\[/', '', $json);
	$json = preg_replace('/,$/', '', $json);
	$json = preg_replace('/\]$/', '', $json);
	
	// echo $json . "\n";
		
	$obj = json_decode($json);
	
	//print_r($obj);


	if (isset($obj->subjects) and count($obj->subjects) > 0)
	{
		print_r($obj->subjects);
		foreach ($obj->subjects as $s)
		{
			if (!isset($subjects[$s]))
			{
				$subjects[$s] = 0;
			}
			$subjects[$s]++;
		}
	}
	
}	

print_r($subjects);


