<?php include('access.php'); ?>
<title>list funded eBooks</title>
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
<!--p>how could my personal paypal account be used? unlikely</p-->
<h1>list funded eBooks</h1>

<table class="DataTable">
<thead>
<tr>
<th>title</th>
<th>author</th>
<th>13-digit ISBN</th>
<th>funder profile name(s)</th>
<th>funding sum</th>
<th>time limit</th>
</tr>
</thead>
<tbody>
<?php

//print('$_SESSION: ');var_dump($_SESSION);
$array_funded_ebooks = array();
$dir = 'funded_ebooks';
$handle = opendir($dir);
while(false !== ($entry = readdir($handle))) {
	//print('reading dir...<br>');
	//print('$entry: ');var_dump($entry);
	if($entry === '.' || $entry === '..' || is_dir($entry) || file_extension($entry) !== '.xml') {
		// we're only interested in funded books with filenames corresponding to their ISBN numbers
	} else {
		$funded_ebook = new O('funded_ebooks' . DS . $entry);
		$book_title = $funded_ebook->_('title');
		if(is_array($book_title)) {
			$book_title = $book_title[0];
		}
		$book_author = $funded_ebook->_('author');
		if(is_array($book_author)) {
			$book_author = $book_author[0];
		}
		$book_ISBN13 = filename_minus_extension($entry);
		$book_funderids = $funded_ebook->_('funderid');
		$book_fundernames = $funded_ebook->_('fundername');
		if(sizeof($book_funderids) !== sizeof($book_fundernames)) {
			warning_once('$book_funderids and $book_fundernames size mismatch. $book_funderids, $book_fundernames');var_dump($book_funderids, $book_fundernames);
		}
		if(!is_array($book_funderids)) {
			$book_funderids = array($book_funderids);
		}
		if(!is_array($book_fundernames)) {
			$book_fundernames = array($book_fundernames);
		}
		$book_funderids = array_unique($book_funderids);
		$book_fundernames = array_unique($book_fundernames);
		// loose code but shouldn't be a problem assuming there's always good data input to the .xml file
		$book_funders_string = '';
		foreach($book_funderids as $index => $book_funderid) {
			$book_funders_string .= '<form action="profile.php?id=' . $book_funderid . '" method="post" style="display: inline;">
' . hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST) . '
<input type="submit" value="' . $book_fundernames[$index] . '" class="button_as_link" />
</form> ';
		}
		$book_funding_sum = $funded_ebook->sum('funding');
		$book_funding_time_limit = $funded_ebook->_('timelimit');
		if(is_string($book_funding_time_limit)) {
			$soonest_time_limit = $book_funding_time_limit;
		} else { // pick the soonest
			$soonest_time_limit = 100000000000000000000000000000;
			foreach($book_funding_time_limit as $index => $value) {
				if($value < $soonest_time_limit) {
					$soonest_time_limit = $value;
				}
			}
		}
		print('<tr>
<th>' . $book_title . '</th>
<td>' . $book_author . '</td>
<td>' . $book_ISBN13 . '</td>
<td>' . $book_funders_string . '</td>
<td>' . $book_funding_sum . '</td>
<td>' . date_from_time($soonest_time_limit) . '</td>
</tr>
');
	}
}
closedir($handle);

?>
</tbody>
<tfoot>

<tfoot>
</table>
<?php include('includes' . DS . 'html_end.incl'); ?>