<?php include('access.php'); ?>
<title>add eBook</title>
<style>

</style>
<!--script src="jquery-3.4.1.js"></script-->
<script>
$(document).ready(function(){
	/*$('#book_format').change(function() {
		//alert($(this).val());
		if($(this).val() == 'other:') {
			$('#other_format').show();
		} else {
			$('#other_format').hide();
		}
	});*/
	$('#submit').prop('disabled', true);
	$('#book_filename').change(function() {
		//alert('book_filename: ' + $(this).val());
		$.post('file_extension_forjs.php', { 'filename': $(this).val() }, function(file_extension_result) {
			//alert('file_extension_result: ' + file_extension_result);
			$('#book_format').val(file_extension_result);
			$.post('data_from_ISBN_forjs.php', { 'book_ISBN10': $('#book_ISBN10').val(), 'book_ISBN13': $('#book_ISBN13').val(), 'book_format': file_extension_result }, function(data_result) {
				//alert('data_result: ' + data_result);
			//	$('#book_funding').val(funding_result);
				temp_array = data_result.split('\r\n');
				//data_array = array();
				data_array = [];
				for(var i = 0; i < temp_array.length; i++) {
					split = temp_array[i].split('	');
					index = split[0];
					value = split[1];
					data_array[index] = value;
				}
				$('#book_funding').val(data_array['book_funding']);
				if($('#book_title').val() === '') {
					$('#book_title').val(data_array['book_title']);
				}
				if($('#book_author').val() === '') {
					$('#book_author').val(data_array['book_author']);
				}
				if($('#book_ISBN10').val() === '') {
					$('#book_ISBN10').val(data_array['book_ISBN10']);
				}
				if($('#book_ISBN13').val() === '') {
					$('#book_ISBN13').val(data_array['book_ISBN13']);
				}
				if($('#book_dewey').val() === '') {
					$('#book_dewey').val(data_array['book_dewey']);
				}
				if($('#book_publisher').val() === '') {
					$('#book_publisher').val(data_array['book_publisher']);
				}
			});
		});
		//$.post('funding_from_filename_forjs.php', { 'file': $(this).val() }, function(funding_result) {
		//	$('#book_funding').val(funding_result);
		//});
		
	});
	//$('#book_title').change(function() {
	//	alert('book_title: ' + $(this).val());
	//	//$.post('file_extension_forjs.php', { 'file': $(this).val() }, function(file_extension_result) {
	//	//	$('#book_format').val(file_extension_result);
	//	//});
	//	if($('#book_funding').val() === '') { // ISBN13 is more reliable for getting the funding
	//		alert('book_funding is empty');
	//		$.post('funding_from_title_forjs.php', { 'title': $(this).val() }, function(funding_result) {
	//			alert('funding_result: ' + funding_result);
	//			$('#book_funding').val(funding_result);
	//		});
	//	}
	//});
	$('#book_ISBN10').focusout(function() {
		//alert('book_ISBN10: ' + $(this).val());
		$.post('data_from_ISBN_forjs.php', { 'book_ISBN10': $('#book_ISBN10').val(), 'book_ISBN13': $('#book_ISBN13').val(), 'book_format': $('#book_format').val() }, function(data_result) {
			//alert('data_result: ' + data_result);
		//	$('#book_funding').val(funding_result);
			temp_array = data_result.split('\r\n');
			//data_array = array();
			data_array = [];
			for(var i = 0; i < temp_array.length; i++) {
				split = temp_array[i].split('	');
				index = split[0];
				value = split[1];
				data_array[index] = value;
			}
			$('#book_funding').val(data_array['book_funding']);
			if($('#book_title').val() === '') {
				$('#book_title').val(data_array['book_title']);
			}
			if($('#book_author').val() === '') {
				$('#book_author').val(data_array['book_author']);
			}
			//if($('#book_ISBN10').val() === '') {
			//	$('#book_ISBN10').val(data_array['book_ISBN10']);
			//}
			if($('#book_ISBN13').val() === '') {
				$('#book_ISBN13').val(data_array['book_ISBN13']);
			}
			if($('#book_dewey').val() === '') {
				$('#book_dewey').val(data_array['book_dewey']);
			}
			if($('#book_publisher').val() === '') {
				$('#book_publisher').val(data_array['book_publisher']);
			}
			if(data_array['valid_ISBN'] === 'true') {
				$('#submit').prop('disabled', false);
			} else {
				$('#submit').prop('disabled', true);
			}
		});
	});
	$('#book_ISBN13').focusout(function() {
		//alert('book_ISBN13: ' + $(this).val());
		//$.post('file_extension_forjs.php', { 'file': $(this).val() }, function(file_extension_result) {
		//	$('#book_format').val(file_extension_result);
		//});
		//$.post('ebook_with_ISBN13_exists_forjs.php', { 'ISBN13': $(this).val() }, function(ebook_exists_result) {
		//	alert('ebook_exists_result: ' + ebook_exists_result);
		//	$('#messages').text(ebook_exists_result);
		//});
		//$.post('funding_from_ISBN13_forjs.php', { 'ISBN13': $(this).val() }, function(funding_result) {
		//	alert('funding_result: ' + funding_result);
		//	$('#book_funding').val(funding_result);
		//});
		$.post('data_from_ISBN_forjs.php', { 'book_ISBN10': $('#book_ISBN10').val(), 'book_ISBN13': $('#book_ISBN13').val(), 'book_format': $('#book_format').val() }, function(data_result) {
			//alert('data_result: ' + data_result);
		//	$('#book_funding').val(funding_result);
			temp_array = data_result.split('\r\n');
			//data_array = array();
			data_array = [];
			for(var i = 0; i < temp_array.length; i++) {
				split = temp_array[i].split('	');
				index = split[0];
				value = split[1];
				data_array[index] = value;
			}
			$('#book_funding').val(data_array['book_funding']);
			if($('#book_title').val() === '') {
				$('#book_title').val(data_array['book_title']);
			}
			if($('#book_author').val() === '') {
				$('#book_author').val(data_array['book_author']);
			}
			if($('#book_ISBN10').val() === '') {
				$('#book_ISBN10').val(data_array['book_ISBN10']);
			}
			//if($('#book_ISBN13').val() === '') {
			//	$('#book_ISBN13').val(data_array['book_ISBN13']);
			//}
			if($('#book_dewey').val() === '') {
				$('#book_dewey').val(data_array['book_dewey']);
			}
			if($('#book_publisher').val() === '') {
				$('#book_publisher').val(data_array['book_publisher']);
			}
			if(data_array['valid_ISBN'] === 'true' && $('#book_filename').val() !== '') {
				$('#submit').prop('disabled', false);
			} else {
				$('#submit').prop('disabled', true);
			}
		});
	});
});
function get_book_funding(book_filename) {
	
}
function get_book_file_extension(book_filename) {
	
}
</script>
</head>
<body>
<h1>add eBook</h1>
<?php

//$access = false;
//if($profile_id === $profile_id_by_request) {
//	$access = true;
//}
//print('$access, $access_granted: ');var_dump($access, $access_granted);
//if(!$access) {
if(!$access_granted) {
	print('You must be logged in to a profile to add an eBook.');exit(0);
}

?>
<!--p>consistency of title and h1 and capitalization used?</p-->
<p>PRO TIP: some form fields may auto-fill for a known book when an ISBN is input</p>
<form action="add_ebook.php" method="post" enctype="multipart/form-data">
<?php /*print('$access_credentials, $tab_settings: ');var_dump($access_credentials, $tab_settings);*/print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST)); ?>
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<input type="hidden" name="book_creatorid" value="<?php print($profileid); ?>" />
<tr>
<th scope="row">Creator:</th><td><input type="text" name="book_creatorname" value="<?php print($profilename); ?>" size="50" readonly /></td>
</tr>
<!--ajax whether this is a supported format-->
<tr>
<th scope="row">File:</th><td><input type="file" name="book_filename" id="book_filename" /></td>
</tr>
<tr>
<th scope="row">Format:</th><td><input type="text" name="book_format" id="book_format" readonly /></td>
</tr>
<tr>
<th scope="row">Funding:</th><td><input type="text" name="book_funding" id="book_funding" readonly /></td>
</tr>
</tbody>
<tbody>
<tr>
<th scope="col" colspan="2">Book Metadata</th>
</tr>
<tr>
<th scope="row">Title:</th><td><input type="text" name="book_title" id="book_title" /></td>
</tr>
<!--Subtitle: <input type="text" name="book_subtitle" /><br-->
<tr>
<th scope="row">Author:</th><td><input type="text" name="book_author" id="book_author" /></td>
</tr>
<tr>
<th scope="row">10-digit ISBN:</th><td><input type="text" name="book_ISBN10" id="book_ISBN10" /></td>
</tr>
<tr>
<th scope="row">13-digit ISBN:</th><td><input type="text" name="book_ISBN13" id="book_ISBN13" /></td>
</tr>
<tr>
<th scope="row">Dewey Decimal Number:</th><td><input type="text" name="book_dewey" id="book_dewey" /></td>
</tr>
<tr>
<th scope="row">Publisher:</th><td><input type="text" name="book_publisher" id="book_publisher" /></td>
</tr>
</tbody>
</table>
fancy filling all fields based on changed inputs
<input type="submit" id="submit" value="Add eBook!" />
</form>
<!--p>need to have recourse for funder satisfasction</p-->
<!--p>how are these validated? percent of votes determine percent of currency awarded? voting could provide some small amount of currency</p-->
<!--p>bounties can be cumulative at the time of turn in so that it's a matter of matching the provided parameters</p-->
<?php include('includes' . DS . 'html_end.incl'); ?>