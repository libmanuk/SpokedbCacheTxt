<?php

// Take the input from commandline.
echo "Enter title code for batch: ";
$handle = fopen ("php://stdin","r");
$jsonname = fgets($handle);

// Look for the JSON file in the incoming directory.
$json = '/var/www/html/ohms/incoming/$jsonname.json';

// Use cachefile name gathered from JSON file.
$cachefile = "sample";

// Read cachefile from cachefile directory.
$doc = file_get_contents("/var/www/html/ohms/cachefiles/$cachefile.xml", "r");

// Parse XML cachefile to extract full-text.
$string = <<<XML
$doc
XML;
$xml = simplexml_load_string($string);
$newxml1 = ($xml->transcript);

// Filters for XML snipet.
$filterxml1 = filter_var($newxml1, FILTER_SANITIZE_STRING);
$filterxml1 = str_replace("\n", ' ', $filterxml1); // remove new lines
$filterxml1 = str_replace("\r", ' ', $filterxml1); // remove carriage returns

// Bumdle up filtered XML snipet.
$fulltext = $filterxml1;

//read the entire JSON as a string.
$str=file_get_contents('/var/www/html/ohms/incoming/sample.json');

//replace something in the file sring
$str=str_replace("}", ",\"text\":\"$fulltext\"}",$str);

//write the entire string
file_put_contents('/var/www/html/ohms/json2index/temp.json', $str);

?>
