<?php

define(DS, DIRECTORY_SEPARATOR);
include('LOM' . DS . 'O.php');
$title = $_REQUEST['title'];
$dir = 'funded_ebooks';
$handle = opendir($dir);
while(false !== ($entry = readdir($handle))) {
	if($entry === '.' || $entry === '..' || file_extension($entry) !== '.xml') {
		
	} else {
		$O = new O($entry);
		$queried_title = $O->_('title');
		if(is_array($queried_title)) {
			$queried_title = $queried_title[0];
		}
		if($title = $queried_title) {
			$queried_funding = $O->_('funding');
			if(is_array($queried_funding)) {
				$queried_funding = $queried_funding[0];
				print($queried_funding);exit(0);
			}
		}
	}
}
closedir($handle);

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