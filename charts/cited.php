<?php

error_reporting(E_ALL);

$filename = 'cited.json';

$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

$count = 1;
$row_startTime = microtime(true);

$values = array();

for ($i = 1; $i < 11; $i++)
{
	$values[$i] = 0;
}
$values['> 10'] = 0;

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
		$cited = $doc->value;
		if ($cited > 10)
		{
			$cited = '> 10';
		}
		if (!isset($values[$cited]))
		{
			$values[$cited] = 0;
		}
		$values[$cited]++;
		
	}
	else
	{
		echo "Expected JSON object on single line (JSONL) but got: $json\n";
	}
}

//print_r($values);

$obj = new stdclass;
$obj->values = array();

foreach ($values as $k => $v)
{
	$row = new stdclass;
	$row->cited = $k;
	$row->count = $v;
	
	$obj->values[] = $row;
}

echo json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";



?>
