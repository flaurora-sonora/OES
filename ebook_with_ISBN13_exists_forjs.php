<?php

define(DS, DIRECTORY_SEPARATOR);
include('LOM' . DS . 'O.php');
$ISBN13 = $_REQUEST['ISBN13'];
$foldername = 'ebooks' . DS . $ISBN13;
if(!is_dir($foldername)) {
	print('false');
} else {
	print('true');
}

?>