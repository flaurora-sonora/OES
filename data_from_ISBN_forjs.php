<?php

define(DS, DIRECTORY_SEPARATOR);
include('LOM' . DS . 'O.php');
$book_ISBN10 = $_REQUEST['book_ISBN10'];
$book_ISBN13 = $_REQUEST['book_ISBN13'];
$book_format = $_REQUEST['book_format'];
//$data_array = array();
$data_array = array('valid_ISBN' => 'false');

// do some ISBN validation or calculation
include('ISBN' . DS . 'ISBN.php');
$isbn = new ISBN();
if($book_ISBN13 == false) {
	if($book_ISBN10 == false) {
		exit(0);
	} else {
		if(!$isbn->validation->isbn10($book_ISBN10)) {
			print('$book_ISBN10, $isbn->validation->isbn10($book_ISBN10): ');var_dump($book_ISBN10, $isbn->validation->isbn10($book_ISBN10));
			fatal_error('provided ISBN10 is invalid</span>');
		}
		$book_ISBN13 = $isbn->translate->to13($book_ISBN10);
	}
} else {
	if($book_ISBN10 == false) {
		if(!$isbn->validation->isbn13($book_ISBN13)) {
			print('$book_ISBN13, $isbn->validation->isbn13($book_ISBN13): ');var_dump($book_ISBN13, $isbn->validation->isbn13($book_ISBN13));
			fatal_error('<span style="color: red;">provided ISBN13 is invalid');
		}
		$book_ISBN10 = $isbn->translate->to10($book_ISBN13);
	} else {
		// all good?
	}
}
//print('$isbn->validation->isbn($book_ISBN10), $isbn->validation->isbn($book_ISBN13), $isbn->validation->isbn10($book_ISBN10), $isbn->validation->isbn13($book_ISBN13): ');var_dump($isbn->validation->isbn($book_ISBN10), $isbn->validation->isbn($book_ISBN13), $isbn->validation->isbn10($book_ISBN10), $isbn->validation->isbn13($book_ISBN13));
$book_ISBN10 = $isbn->hyphens->fixHyphens($book_ISBN10);
$book_ISBN13 = $isbn->hyphens->fixHyphens($book_ISBN13);
$data_array['book_ISBN10'] = $book_ISBN10;
$data_array['book_ISBN13'] = $book_ISBN13;
$data_array = array('valid_ISBN' => 'true'); // assume it's true

$funded_book_filename = 'funded_ebooks' . DS . $book_ISBN13 . '.xml';
if(!is_file($funded_book_filename)) {
	//print('false');
} else { // not recognized as file or folder... then we have to work pretty hard to guess (usually due to strange characters)
	$funded_book = new O($funded_book_filename);
	$book_funding = $funded_book->sum('funding', '.book_format=' . $funded_book->enc($book_format));
	//print($funding);
	if($book_funding > 0) {
		$data_array['book_funding'] = $book_funding;
	}
}
$ebooks = new O('ebooks' . DS . 'ebooks.xml');
$book = $ebooks->_('.book_ISBN13=' . $ebooks->enc($book_ISBN13));
//print('$funded_book, $book: ');var_dump($funded_book, $book);
if($book === false || (is_array($book) && sizeof($book) === 0)) {
	
} else {
	$book_title = $ebooks->_('title', $book);
	if($book_title === false || (is_array($book_title) && sizeof($book_title) === 0)) {
		
	} else {
		$data_array['book_title'] = $book_title;
	}
	$book_author = $ebooks->_('author', $book);
	if($book_author === false || (is_array($book_author) && sizeof($book_author) === 0)) {
		
	} else {
		$data_array['book_author'] = $book_author;
	}
	$book_dewey = $ebooks->_('dewey', $book);
	if($book_dewey === false || (is_array($book_dewey) && sizeof($book_dewey) === 0)) {
		
	} else {
		$data_array['book_dewey'] = $book_dewey;
	}
	$book_publisher = $ebooks->_('publisher', $book);
	if($book_publisher === false || (is_array($book_publisher) && sizeof($book_publisher) === 0)) {
		
	} else {
		$data_array['book_publisher'] = $book_publisher;
	}
}
foreach($data_array as $index => $value) {
	print($index . '	' . $value . '
'); // tab separates the index and value while a new line separates each entry from the next
}

?>