<?php

$filename = $_REQUEST['filename'];
print(file_extension($filename));

function file_extension($string) {
	if(strpos($string, '.') === false || strpos_last($string, '.') < strpos_last($string, DIRECTORY_SEPARATOR)) {
		return false;
	}
	$file_extension = substr($string, strpos_last($string, '.'));
	if(strpos($file_extension, ' ') !== false || strpos($file_extension, '[') !== false || strpos($file_extension, ']') !== false || strpos($file_extension, '(') !== false || strpos($file_extension, ')') !== false || strpos($file_extension, '-') !== false) { // should we use preg_match?
		return false;
	}
	return $file_extension;
}

function strpos_last($haystack, $needle) {
	//print('$haystack, $needle: ');var_dump($haystack, $needle);
	if(strlen($needle) === 0) {
		return false;
	}
	$len_haystack = strlen($haystack);
	$len_needle = strlen($needle);		
	$pos = strpos(strrev($haystack), strrev($needle));
	if($pos === false) {
		return false;
	}
	return $len_haystack - $pos - $len_needle;
}

?>