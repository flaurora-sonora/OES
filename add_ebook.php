<!--script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script-->
<script src="jquery-3.4.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	window.document.forms[0].submit();
});
</script>
<!--script>
$(document).ready(function(){
	$.post('/list_funded_ebooks.php', { key1: 'value1', key2: 'value2' }, function(result) {
		alert('successfully posted key1=value1&key2=value2 to list_funded_ebooks.php');
	});
});
</script-->
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />

<?php

define(DS, DIRECTORY_SEPARATOR);
include('functions.php');

//print('$_FILES, $_REQUEST: ');var_dump($_FILES, $_REQUEST);
$contents = file_get_contents($_FILES['book_filename']['tmp_name']);
//print('$contents: ');var_dump($contents);exit(0);

//$info = pathinfo($_FILES['book_filename']['name']);
//$ext = $info['extension']; // get the extension of the file
//$newname = 'newname.' . $ext; 

//$target = 'images/' . $newname;
//print('$_FILES, $info, $ext, $newname, $target: ');var_dump($_FILES, $info, $ext, $newname, $target);exit(0);
//move_uploaded_file($_FILES['book_filename']['tmp_name'], $target);

$book_creatorid = $_REQUEST['book_creatorid'];
$book_creatorname = $_REQUEST['book_creatorname'];
//$book_filename = $_REQUEST['book_filename'];
$book_filename = $_FILES['book_filename']['name'];
//$book_funding = $_REQUEST['book_funding'];
//$book_funding_time_limit = $_REQUEST['book_funding_time_limit'];
$book_format = $_REQUEST['book_format'];
//if($book_format === 'other:') {
//	$book_format = $_REQUEST['other_format'];
//}
$book_title = $_REQUEST['book_title'];
//$book_subtitle = $_REQUEST['book_subtitle'];
$book_author = $_REQUEST['book_author'];
$book_ISBN10 = $_REQUEST['book_ISBN10'];
$book_ISBN13 = $_REQUEST['book_ISBN13'];
$book_dewey = $_REQUEST['book_dewey'];
$book_publisher = $_REQUEST['book_publisher'];

// do some ISBN validation or calculation
include('ISBN' . DS . 'ISBN.php');
$isbn = new ISBN();
if($book_ISBN13 == false) {
	if($book_ISBN10 == false) {
		print('$book_ISBN10, $book_ISBN13: ');var_dump($book_ISBN10, $book_ISBN13);fatal_error('add_ebook.php must be provided with an ISBN number.');
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
//print('$book_ISBN10, $book_ISBN13: ');var_dump($book_ISBN10, $book_ISBN13);

//$time_limit_contents = file_get_contents('funded_ebooks' . DS . 'time_limits.txt');
//$initial_time_limits_array = $time_limits_array = unserialize($time_limit_contents);
include('LOM' . DS . 'O.php');
// ISBNs misinput will cause problems, but probably better to check for this afterwards while retaining the extra performance of separating out by ISBN

$ebooks = new O('ebooks' . DS . 'ebooks.xml');
$book = $ebooks->_('.book_title=' . $ebooks->enc($book_title));
$new_filename = 'ebooks' . DS . $book_ISBN13 . DS . $book_title . ' by ' . $book_author . $book_format;
//print('$book: ');var_dump($book);
//print('here3854956061<br>');
//if(is_string($book)) {
if($book === false || (is_array($book) && sizeof($book) === 0)) { // create it
	//print('here3854956065<br>');
	$ebooks->_new('<book>
<creatorid>' . $book_creatorid . '</creatorid>
<creatorname>' . $ebooks->xml_enc($book_creatorname) . '</creatorname>
<title>' . $ebooks->xml_enc($book_title) . '</title>
<subtitle>' . $ebooks->xml_enc($book_subtitle) . '</subtitle>
<author>' . $ebooks->xml_enc($book_author) . '</author>
<ISBN10>' . $book_ISBN10 . '</ISBN10>
<ISBN13>' . $book_ISBN13 . '</ISBN13>
<dewey>' . $book_dewey . '</dewey>
<publisher>' . $ebooks->xml_enc($book_publisher) . '</publisher>
<file>
<name>' . $new_filename . '</name>
<format>' . $book_format . '</format>
</file>
</book>');
} else {
	//print('here3854956062<br>');
	if(is_string($ebooks->_('file_format=' . $ebooks->enc($book_format), $book))) { // check if the format already exists
		//print('here3854956063<br>');
		print('$book_title, $book_ISBN13, $book_format: ');var_dump($book_title, $book_ISBN13, $book_format);fatal_error('this book already exists in this format');
	} else {
		//print('here3854956064<br>');
		$ebooks->_new('<file>
<name>' . $new_filename . '</name>
<format>' . $book_format . '</format>
</file>', $book);
	}
}
//exit(0);
// save the ebook and save the ebooks.xml file
$ebooks->save();

//move_uploaded_file($book_filename, $new_filename);
//$move_uploaded_file_result = move_uploaded_file('C:' . DS . 'fakepath' . DS . $book_filename, $new_filename);
//$move_uploaded_file_result = move_uploaded_file($_FILES['book_filename']['tmp_name'], $new_filename);
if(!is_dir('ebooks' . DS . $book_ISBN13)) {
	mkdir('ebooks' . DS . $book_ISBN13);
}
//if(!is_dir('ebooks' . DS . $book_ISBN13 . DS . $book_title . ' by ' . $book_author)) {
//	mkdir('ebooks' . DS . $book_ISBN13 . DS . $book_title . ' by ' . $book_author);
//}
//$file_put_contents_result = file_put_contents($new_filename, file_get_contents($_FILES['book_filename']['tmp_name']));
file_put_contents($new_filename, file_get_contents($_FILES['book_filename']['tmp_name']));

// add the funding from the funder's account and remove the fundations
$funded_ebook = new O('funded_ebooks' . DS . $book_ISBN13);
$profiles = new O('profiles.xml');
$book_funding_sum = $funded_ebook->sum('funding');
$profiles->add($book_funding_sum, 'currency', '.profile_id=' . $book_creatorid);
$funded_ebook->delete('.book_format=' . $funded_ebook->enc($book_format));
$funded_ebook->save();

//print('$_FILES, $book_filename, $new_filename: ');var_dump($_FILES, $book_filename, $new_filename);
//print('$file_put_contents_result: ');var_dump($file_put_contents_result);exit(0);
// how to ensure the newly added one will be visible? sort by time?
$access_credentials['profile_id'] = $_REQUEST['profile_id'];
$access_credentials['profile_name'] = $_REQUEST['profile_name'];
$access_credentials['password'] = $_REQUEST['password'];
$access_credentials['biometric'] = $_REQUEST['biometric'];
$access_credentials['IP'] = $_REQUEST['IP'];
//print('$_REQUEST, $access_credentials, $tab_settings: ');var_dump($_REQUEST, $access_credentials, $tab_settings);exit(0);

?>
<form action="list_ebooks.php" method="post">
<?php print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST)); ?>
</form>