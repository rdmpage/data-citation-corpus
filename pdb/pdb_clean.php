<?php

// Count how many PDB citations are correctly formatted

$filename = "corpus_pdb.txt";

$count = 0;

$bad = array();

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));

	// "9XIM" : {

	if (!preg_match('/^[0-9][A-Za-z0-9]{3}$/', $line))
	{
		$bad[] = $line;
	}
	
	$count++;
	
}	


print_r($bad);

echo "Total: $count\n";
echo "Bad: " . count($bad) . "\n";

echo round((count($bad)/$count) * 100) . "\n";

?>
