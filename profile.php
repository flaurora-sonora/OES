<?php include('access.php'); 
$id = get_by_request('id');
$profile_of_profile_page = $profiles->_('.profile_id=' . $id);
//print('$id, $profile_of_profile_page: ');var_dump($id, $profile_of_profile_page);exit(0);
if(is_array($profile_of_profile_page) && sizeof($profile_of_profile_page) === 0) {
	print('$id: ');var_dump($id);fatal_error('did not find a profile corresponding to this id');
}
$profile_name_from_id = $profiles->_('profile_name', $profile_of_profile_page);
//print('$_REQUEST, $id, $profile_of_profile_page, $profile_name_from_id: ');var_dump($_REQUEST, $id, $profile_of_profile_page, $profile_name_from_id);
?>
<title>profile: <?php print($profile_name_from_id); ?></title>
<?php

$access = false;
//print('hard-coded access denial.');
if($profileid === $id) {
	$access = true;
}

if($access) {
	print('<script src="../jquery-3.4.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){ // need jquery for this line
var smooth_incrementer_counter = document.getElementById(\'smooth_incrementer\').innerHTML;
var smooth_incrementer_shrinker_counter = 0;
var smooth_incrementer_interval = setInterval(smooth_incrementer, 1000); // 1 FPS
var smooth_incrementer_shrinker_interval = setInterval(smooth_incrementer_shrinker, 20); // 50 FPS
function smooth_incrementer() {
	smooth_incrementer_counter++;
	//console.log(smooth_incrementer_counter);
	//digits_changed = 1;
	//smooth_incrementer_counter_string = smooth_incrementer_counter.toString;
	//alert(\'smooth_incrementer_counter_string: \' + smooth_incrementer_counter_string);
	//string_array = smooth_incrementer_counter_string.split(\'\');
	//string_array = smooth_incrementer_counter.split(\'\');
	//digit_counter = string_array.length - 1;
	SIC_string = smooth_incrementer_counter.toString();
	digit_counter = SIC_string.length - 1;
	//console.log(digit_counter);
	//reversed_string_array = string_array.reverse();
	marked_piece = SIC_string.substr(digit_counter, 1);
	while(digit_counter > -1) {
		if(SIC_string.substr(digit_counter, 1) == 0) {
			//marked_piece = string_array[digit_counter] + marked_piece;
			marked_piece = SIC_string.substr(digit_counter - 1, 1) + marked_piece;
		} else {
			break;
		}
		digit_counter--;
	}
	//shrinker_marked_string = SIC_string.substr(0, digit_counter) + \'<span id="smooth_incrementer_shrinker">\' + marked_piece + \'</span>\';
	document.getElementById(\'smooth_incrementer\').innerHTML = SIC_string.substr(0, digit_counter) + \'<span id="smooth_incrementer_shrinker">\' + marked_piece + \'</span>\';
	//document.getElementById(\'smooth_incrementer\').innerHTML = smooth_incrementer_counter + \'<span id="smooth_incrementer_shrinker">a</span>\';
	smooth_incrementer_shrinker_counter = 25; // shrink for a half a second
	$(\'#smooth_incrementer_shrinker\').css(\'font-size\', \'150%\');
}
function smooth_incrementer_shrinker() {
	smooth_incrementer_shrinker_counter--;
	if(smooth_incrementer_shrinker_counter <= 0) {
		smooth_incrementer_shrinker_counter = 0;
	} else {
		$(\'#smooth_incrementer_shrinker\').css(\'font-size\', (100 + 50 * (smooth_incrementer_shrinker_counter / 25)) + \'%\');
	}
}
$("#hide_currency").click(function(){
	$("#currency").hide();
	$("#hide_currency").hide();
	$("#show_currency").show();
});
$("#show_currency").click(function(){
	$("#currency").show();
	$("#hide_currency").show();
	$("#show_currency").hide();
});
});
</script>
');
}

?>
</head>
<body>
<h1><?php print($profile_name_from_id); ?> <img src="images/profile.png" width="320" height="320" alt="profile Icon" /></h1>
<p>want to remember the show hide currency setting</p>

<?php

if($access) {
	print('<button id="hide_currency" style="display: inline;">Hide EX</button>
<button id="show_currency" style="display: none;">Show EX</button><div id="currency" style="height: 30px;"><!-- to prevent the whole page from moving --><p><span id="smooth_incrementer">' . $profiles->_('currency', $profile_of_profile_page) . '</span> <img src="images/EX.png" alt="EX" /> (incrementer with changed digits emphasis)</p></div>
');
}

?>
<!--p>what else to show here, publicly and not? links made, currency on the exchange, eBooks funded, eBooks created, exploration funded, etc.</p>
<p>what are the advantages and disadvantages of associating a person with a profile?</p>
<p>add link <img src="" width="16" height="16" alt="add link"></p>

<p>biography user-created if logged in.</p>
<p>show reputation?</p>
<p>tip creator of page... maybe preset buttons with amounts that make sense based on normal usage</p>
<p>could have the option of tracking what pages a profile sees. should be optional... how to handle privacy? this site shouldn't be about profileal information but rather public information?</p>
<h2>Links</h2>
<p>add link <img src="" width="16" height="16" alt="add link"></p>
<p>all-time | this year | this month | today</p-->
<!--table class="DataTable">
<caption>created links</caption>
<thead>
<tr>
<th>created link</th>
<th>clicks</th>
<th>currency awarded</th>
<th>comments?</th>
</tr>
</thead>
<tbody-->
<?php
/*
foreach($profiles->_('createdlink', $profile_of_profile_page) as $createdlink) {
	print('<tr>
<th><a href="' . $profiles->_('URL', $createdlink) . '">' . $profiles->_('URL', $createdlink) . '</a></th>
<td>' . $profiles->_('clicks', $createdlink) . '</td>
<td>' . $profiles->_('currencyawarded', $createdlink) . '</td>
<td>' . $profiles->_('comment', $createdlink) . '</td>
</tr>');
}
*/
?>
<!--/tbody>
<tfoot>
<tr>
could do some totals. think about it
</tr>
</tfoot>
</table-->

<!--table class="DataTable">
<caption>currency exchange</caption>
<thead>
<tr>
<th>exchange</th>
<th>offering</th>
<th>seeking</th>
<th>comments?</th>
</tr>
</thead>
<tbody-->
<?php
/*
foreach($profiles->_('exchange', $profile_of_profile_page) as $exchange) {
	print('<tr>
<th>id?</th>
<td>' . $profiles->_('offering_amount', $exchange) . ' ' . $profiles->_('offering_type', $exchange) . '</td>
<td>' . $profiles->_('seeking_amount', $exchange) . ' ' . $profiles->_('seeking_type', $exchange) . '</td>
<td>' . $profiles->_('comment', $exchange) . '</td>
</tr>');
}
*/
?>
<!--/tbody>
<tfoot>
<tr>
could do some totals. think about it
</tr>
</tfoot>
</table-->

<table class="DataTable">
<caption>eBooks funded</caption>
<thead>
<tr>
<th>title</th>
<th>ISBN13</th>
<th>funding amount</th>
<th>comment</th>
</tr>
</thead>
<tbody>
<?php

foreach($profiles->_('ebookfunded', $profile_of_profile_page) as $ebookfunded) {
	//print('$ebookfunded, $profile_of_profile_page: ');var_dump($ebookfunded, $profile_of_profile_page);
	//warning_once('ugly hack438034 OOOH BOOY!! different results with the same input; what fun!');
	//print('$ebookfunded before hack: ');var_dump($ebookfunded);
	//$ebookfunded[0] = substr($ebookfunded[0], 0, strpos($ebookfunded[0], '</ebookfunded>') + 14);
	//print('$ebookfunded after hack: ');var_dump($ebookfunded);
	print('<tr>
<th>' . $profiles->_('title', $ebookfunded) . '</th>
<td>' . $profiles->_('ISBN13', $ebookfunded) . '</td>
<td>' . $profiles->_('amount', $ebookfunded) . '</td>
<td>' . $profiles->_('comment', $ebookfunded) . '</td>
</tr>');
	//print('$profiles->_(\'title\', $ebookfunded), $profiles->_(\'ISBN13\', $ebookfunded), $profiles->_(\'amount\', $ebookfunded), $profiles->_(\'comment\', $ebookfunded): ');var_dump($profiles->_('title', $ebookfunded), $profiles->_('ISBN13', $ebookfunded), $profiles->_('amount', $ebookfunded), $profiles->_('comment', $ebookfunded));
}

?>
</tbody>
<!--tfoot>
<tr>
could do some totals. think about it
</tr>
</tfoot-->
</table>

<table class="DataTable">
<caption>eBooks created</caption>
<thead>
<tr>
<th>title</th>
<th>ISBN13</th>
<th>funding earned</th>
<th>comment</th>
</tr>
</thead>
<tbody>
<?php

foreach($profiles->_('ebookcreated', $profile_of_profile_page) as $ebookcreated) {
	print('<tr>
<th><a href="' . $profiles->_('filename', $ebookcreated) . '">' . $profiles->_('title', $ebookcreated) . '</a></th>
<td>' . $profiles->_('ISBN13', $ebookcreated) . '</td>
<td>' . $profiles->_('funding', $ebookcreated) . '</td>
<td>' . $profiles->_('comment', $ebookcreated) . '</td>
</tr>');
}

?>
</tbody>
<!--tfoot>
<tr>
could do some totals. think about it
</tr>
</tfoot-->
</table>

<!--table class="DataTable">
<caption>exploration funded</caption>
<thead>
<tr>
<th>exploration funded</th>
<th>location</th>
<th>funding amount</th>
<th>comments?</th>
</tr>
</thead>
<tbody-->
<?php
/*
foreach($profiles->_('explorationfunded', $profile_of_profile_page) as $explorationfunded) {
	print('<tr>
<th><a href="#location_page_link">' . $profiles->_('location', $explorationfunded) . '</a></th>
<td>' . $profiles->_('amount', $explorationfunded) . '</td>
<td>' . $profiles->_('comment', $explorationfunded) . '</td>
</tr>');
}
*/
?>
<!--/tbody>
<tfoot>
<tr>
could do some totals. think about it
</tr>
</tfoot>
</table-->
<?php include('includes' . DS . 'html_end.incl'); ?>