<?php

// Get clean list of identifiers

$filename = "current_file_holdings.json";

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));

	// "9XIM" : {

	if (preg_match('/^"([0-9A-Z]{4})"/', $line, $m))
	{
		echo 'INSERT INTO identifier(namespace, id) VALUES("pdb", "' . $m[1] . '");' . "\n";
	}
	
}	

?>
