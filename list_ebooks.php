<?php include('access.php'); ?>
<title>list eBooks</title>
<!--book>
<profile>' . $profile_name . '</profile>
<funding>' . $book_funding . '</funding>
<timelimit>' . $book_funding_time_limit . '</timelimit>
<format>' . $book_format . '</format>
<title>' . $book_title . '</title>
<subtitle>' . $book_subtitle . '</subtitle>
<author>' . $book_author . '</author>
<ISBN>' . $book_ISBN . '</ISBN>
<dewey>' . $book_dewey . '</dewey>
</book-->

<!--p>linear or spiral list format buttons</p-->
<!--p>how my personal paypal account be used? unlikely</p-->
<!--p>dark theme; take from fs</p-->
<h1>list eBooks</h1>
<!--p><a href="fund_ebook_form.php">fund ebook</a></p-->
<!--p>compare performance of reading the directory versus creating an XML file with the ebooks data</p>
<p>getting the 3 books' titles and formats and not querying other metadata takes 0.015 seconds</p>
<p>getting the 3 books' titles and formats and querying other metadata takes 0.027 seconds</p>
<p>using an XML file to get all data on the 3 books takes 0.023 seconds</p-->

<table class="DataTable">
<thead>
<tr>
<th scope="col">title</th>
<th scope="col">author</th>
<th scope="col">formats</th>
<th scope="col">13-digit ISBN</th>
</tr>
</thead>
<tbody>
<?php

//print('$_SESSION: ');var_dump($_SESSION);
include_once('LOM' . DS . 'O.php');
$dir = 'ebooks';
$ebooks = new O($dir . DS . 'ebooks.xml');
//$array_ebooks = array();

foreach($ebooks->_('book') as $book) {
	$formats_string = '';
	foreach($ebooks->_('file', $book) as $file) {
		$formats_string .= '<a href="' . $dir . DS . $ebooks->_('name', $file) . '">' . $ebooks->_('format', $file) . '</a> ';
	}
	print('<tr>
<th scope="row">' . $ebooks->_('title', $book) . '</th>
<td>' . $ebooks->_('author', $book) . '</td>
<td>' . $formats_string . '</td>
<td>' . $ebooks->_('ISBN13', $book) . '</td>
</tr>
');

}

/*$handle = opendir($dir);
while(false !== ($entry = readdir($handle))) {
	//print('reading dir...<br>');
	//print('$entry: ');var_dump($entry);
	if($entry === '.' || $entry === '..') {
		// we're only interested in books with filenames corresponding to their ISBN numbers
	} elseif(is_dir($dir . DS . $entry)) {
		$handle2 = opendir($dir . DS . $entry);
		$book = $ebooks->_('.book_title=' . $ebooks->enc($entry));
		$author = $ebooks->_('author', $book);
		$ISBN13 = $ebooks->_('ISBN13');
		//print('$book, $author, $ISBN13: ');var_dump($book, $author, $ISBN13);
		print('<tr>
<th scope="row">' . $entry . '</th>
<td>' . $author . '</td>
<td>');
		while(false !== ($entry2 = readdir($handle2))) {
			if($entry2 === '.' || $entry2 === '..') {
				
			} else {
				print('<a href="' . $dir . DS . $entry . DS . $entry2 . '">' . file_extension($entry2) . '</a> ');
			}
		}
		closedir($handle2);
		
	//	/*$ebook = new O('ebooks' . DS . $entry);
	//	$book_title = $ebook->_('title');
	//	if(is_array($book_title)) {
	//		$book_title = $book_title[0];
	//	}
	//	$book_ISBN13 = filename_minus_extension($entry);
	//	$book_funder_profile = $ebook->_('profile');
	//	if(is_array($book_funder_profile)) {
	//		print('<span style="color: orange;">multiple ebook funders not supported</span><br>');
	//		$book_funder_profile = $book_funder_profile[0];
	//	}
	//	$book_funding_sum = $ebook->sum('funding');
	//	$book_funding_time_limit = $ebook->_('timelimit');
	//	if(is_string($book_funding_time_limit)) {
	//		$soonest_time_limit = $book_funding_time_limit;
	//	} else { // pick the soonest
	//		$soonest_time_limit = 100000000000000000000000000000;
	//		foreach($book_funding_time_limit as $index => $value) {
	//			if($value < $soonest_time_limit) {
	//				$soonest_time_limit = $value;
	//			}
	//		}
	//	}
		print('</td>
<td>' . $ISBN13 . '</td>
</tr>
');
	}
}
closedir($handle);*/
//dump_total_time_taken();

?>
</tbody>
<tfoot>

<tfoot>
</table>
<?php include('includes' . DS . 'html_end.incl'); ?>