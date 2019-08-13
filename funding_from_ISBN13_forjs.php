<?php

define(DS, DIRECTORY_SEPARATOR);
include('LOM' . DS . 'O.php');
$ISBN13 = $_REQUEST['ISBN13'];
$filename = 'funded_ebooks' . DS . $ISBN13 . '.xml';
if(!is_file($filename)) {
	print('false');
} else { // not recognized as file or folder... then we have to work pretty hard to guess (usually due to strange characters)
	$O = new O($filename);
	$funding = $O->sum('funding');
	print($funding);
}

?>