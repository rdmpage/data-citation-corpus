<?php

// Convert data dump into SQL

$row_count = 0;

$filename = "data-dump.json";

$make_schema = false;

$schema = '';

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
	$json = preg_replace('/^\[/', '', $json);
	$json = preg_replace('/,$/', '', $json);
	$json = preg_replace('/\]$/', '', $json);
	
	// echo $json . "\n";
		
	$obj = json_decode($json);
	
	// print_r($obj);
	
	if ($make_schema)
	{	
		$schema = 'CREATE TABLE "citation" (';
		foreach ($obj as $k => $v)
		{
			switch ($k)
			{
				case 'id':
					$schema .= "\nid TEXT PRIMARY KEY";
					break;
				
				case 'retried':
					$schema .= "\n," . $k . " INTEGER";
					break;
				
				default:
					$schema .= "\n, " . $k . " TEXT";
					break;
		
			}
		}
		$schema .= "\n);";
	
		echo $schema . "\n";
		exit();
	}
	
	// SQL
	$keys = array();
	$values = array();

	foreach ($obj as $k => $v)
	{
		$go = true;
		
		if (is_array($v) && count($v) == 0)
		{
			$go = false;
		}
		else
		{
			$go = ($v != '');
		}
				
		if ($go)
		{
	
			$keys[] = '"' . $k . '"'; // must be double quotes

			if (is_array($v))
			{
				$values[] = "'" . str_replace("'", "''", json_encode(array_values($v))) . "'";
			}
			elseif(is_object($v))
			{
				$values[] = "'" . str_replace("'", "''", json_encode($v)) . "'";
			}
			elseif (preg_match('/^POINT/', $v))
			{
				$values[] = "ST_GeomFromText('" . $v . "', 4326)";
			}
			else
			{               
				$values[] = "'" . str_replace("'", "''", $v) . "'";
			}  
		}                 
	}

	$sql = 'INSERT INTO citation (' . join(",", $keys) . ') VALUES (' . join(",", $values) . ') ON CONFLICT DO NOTHING;';                   
	$sql .= "\n";

	echo $sql;
	
	$row_count++;	
	
	
	if ($row_count > 100) // bail if we are just debugging
	{
		//exit();
	}
	
}	

?>
