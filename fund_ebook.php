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
<?php

define(DS, DIRECTORY_SEPARATOR);
include('functions.php');
//print('$_REQUEST: ');var_dump($_REQUEST);
$book_funderid = $_REQUEST['book_funderid'];
$book_fundername = $_REQUEST['book_fundername'];
$book_funding = $_REQUEST['book_funding'];
$book_funding_time_limit = $_REQUEST['book_funding_time_limit'];
$book_format = $_REQUEST['book_format'];
if($book_format === 'other:') {
	$book_format = $_REQUEST['other_format'];
}
$book_title = $_REQUEST['book_title'];
//$book_subtitle = $_REQUEST['book_subtitle'];
$book_author = $_REQUEST['book_author'];
$book_ISBN10 = $_REQUEST['book_ISBN10'];
$book_ISBN13 = $_REQUEST['book_ISBN13'];
//$book_dewey = $_REQUEST['book_dewey'];

// do some ISBN validation or calculation
include('ISBN' . DS . 'ISBN.php');
$isbn = new ISBN();
if($book_ISBN13 == false) {
	if($book_ISBN10 == false) {
		print('fund_ebook.php must be provided with an ISBN number. $book_ISBN10, $book_ISBN13: ');var_dump($book_ISBN10, $book_ISBN13);exit(0);
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

$time_limit_contents = file_get_contents('funded_ebooks' . DS . 'time_limits.txt');
$initial_time_limits_array = $time_limits_array = unserialize($time_limit_contents);
include('LOM' . DS . 'O.php');
// ISBNs misinput will cause problems, but probably better to check for this afterwards while retaining the extra performance of separating out by ISBN
if(file_exists('funded_ebooks' . DS . $book_ISBN13 . '.xml')) {
	$funded_book = new O('funded_ebooks' . DS . $book_ISBN13 . '.xml');
	$funded_book->new_('<book>
<funderid>' . $book_funderid . '</funderid>
<fundername>' . $book_fundername . '</fundername>
<funding>' . $book_funding . '</funding>
<timelimit>' . time_from_formatted_date($book_funding_time_limit) . '</timelimit>
<format>' . $book_format . '</format>
<title>' . $book_title . '</title>
<subtitle>' . $book_subtitle . '</subtitle>
<author>' . $book_author . '</author>
<ISBN10>' . $book_ISBN10 . '</ISBN10>
<ISBN13>' . $book_ISBN13 . '</ISBN13>
<dewey>' . $book_dewey . '</dewey>
</book>');
	$funded_book->save();
} else { // create it
	file_put_contents('funded_ebooks' . DS . $book_ISBN13 . '.xml', '<book>
<funderid>' . $book_funderid . '</funderid>
<fundername>' . $book_fundername . '</fundername>
<funding>' . $book_funding . '</funding>
<timelimit>' . time_from_formatted_date($book_funding_time_limit) . '</timelimit>
<format>' . $book_format . '</format>
<title>' . $book_title . '</title>
<subtitle>' . $book_subtitle . '</subtitle>
<author>' . $book_author . '</author>
<ISBN10>' . $book_ISBN10 . '</ISBN10>
<ISBN13>' . $book_ISBN13 . '</ISBN13>
<dewey>' . $book_dewey . '</dewey>
</book>');
}
// subtract the funding from the funder's account
$profiles = new O('profiles.xml');
$profiles->subtract($book_funding, 'currency', '.profile_id=' . $book_funderid);
$profiles->save();
if($book_funding_time_limit) {
	if(isset($time_limits_array[$book_funding_time_limit])) {
		$time_limits_array[$book_funding_time_limit][] = $book_ISBN13;
	} else {
		$time_limits_array[$book_funding_time_limit] = array($book_ISBN13);
	}
}

// do cleanup of eBooks that didn't get funded
foreach($time_limits_array as $time_limit => $value) {
	if(time() > $time_limit) {
		unset($time_limits_array[$time_limit]);
	} else {
		break;
	}
}
if($initial_time_limits_array !== $time_limits_array) {
	file_put_contents('funded_ebooks' . DS . 'time_limits.txt', serialize($time_limits_array));
}
// how to ensure the newly added one will be visible? sort by time?
//header('Location: list_funded_ebooks.php');
//hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST);
//$_REQUEST['test_var1'] = 'test_value1';
//$_POST['test_var2'] = 'test_value2';
//session_start();
//$_SESSION['test_var3'] = 'test_value3';
//exit(0);
//print('<meta http-equiv="refresh" content="0; url=list_funded_ebooks.php" />');
//print('hard-coded query_string');exit(0);
//print('<meta http-equiv="refresh" content="0; url=list_funded_ebooks.php?profile_name=account1" />');
$access_credentials['profile_id'] = $_REQUEST['profile_id'];
$access_credentials['profile_name'] = $_REQUEST['profile_name'];
$access_credentials['password'] = $_REQUEST['password'];
$access_credentials['biometric'] = $_REQUEST['biometric'];
$access_credentials['IP'] = $_REQUEST['IP'];
//print('$_REQUEST, $access_credentials, $tab_settings: ');var_dump($_REQUEST, $access_credentials, $tab_settings);exit(0);

?>
<form action="list_funded_ebooks.php" method="post">
<?php print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST)); ?>
</form>