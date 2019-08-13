<html>
<head>
<link rel="shortcut icon" href="favicon.gif" />
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />
<script src="jquery-3.4.1.js"></script>

<?php

// how to avoid the continual bloating of blockchain without sacrificing integrity (verifiability)?
// advantages and disadvantages of translating things like legalese into logical data structures... might be that abuses are more prevalent from the wardens of the law; if strictly defined, there could be far more dynamism to the law
// so that it would be more adaptable and eventually more relevant. 
// being strictly logically defined allows machine learning to work on it. 
define('DS', DIRECTORY_SEPARATOR);
include_once('functions.php');
$GLOBALS['OES_initial_time'] = getmicrotime();
//$OES_initial_time = getmicrotime();

//print('$_REQUEST: ');var_dump($_REQUEST);
//define('DS', DIRECTORY_SEPARATOR);
//$profile_id = $_REQUEST['profile_id'];
$profile_id_by_request = get_by_request('profile_id');
//$profile_name = $_REQUEST['name'];
$profile_name_by_request = get_by_request('profile_name');
//include_once('LOM' . DS . 'O.php');
//$profiles = new O('profiles.xml');

$timers = array();
//if(!include_once('..' . DS . 'LOM' . DS . 'O.php')) {
if(!include_once('LOM' . DS . 'O.php')) {	
	print('<a href="https://www.phpclasses.org/package/10594-PHP-Extract-information-from-XML-documents.html">LOM</a> is required');exit(0);
}

$profiles = new O('profiles.xml');
//print('$profile before id and name comparison: ');var_dump($profile);
//$profile = $profiles->_('.profile_id=' . $profile_id_by_request);
//if($profile === false || (is_array($profile) && sizeof($profile) === 0)) {
//	$profile = $profiles->_('.profile_name=' . $profiles->enc($profile_name_by_request));
//}

//print('$profile after id and name comparison: ');var_dump($profile);
//warning_once('check that profile promotion from a guid to an profilename works if that is desired since it doesn\'t seem to be working which was surprising. also probably would be good to hash the IPs');
// upgrading an account with a name and password at the same time seems to be the last thing not working. each of these upgrades works individually
//$profile_id_by_request = get_by_request('profile_id');
//$profile_name_by_request = get_by_request('profile_name');
$password = get_by_request('password');
$biometric = get_by_request('biometric');
$IP = $_SERVER['REMOTE_ADDR'];
//print('$profileid, $password, $biometric, $IP1: ');var_dump($profileid, $password, $biometric, $IP);

// if we're passed information for an existing profile that we're logged in to, assume it is to be added to the profile's data as long as all other information is correct
// notice that IP shouldn't completely deny login while other credentials if they are incorrect should
//print('$profiles->LOM: ');var_dump($profiles->LOM);

//$access_credentials = array('profilename' => $profileid, 'password' => $password, 'biometric' => $biometric, 'IP' => $IP);
$access_credentials = array('profile_id' => $profile_id_by_request, 'profile_name' => $profile_name_by_request, 'password' => $password, 'biometric' => $biometric, 'IP' => $IP);
$other_currency_symbols = array('₠', '₡', '₢', '₣', '₤', '₥', '₦', '₧', '₨', '₩', '₪', '₫', '€', '₭', '₮', '₯', '₰', '₱', '₲', '₳', '₴', '₵', '₶', '₷', '₸', '₹');
$messages = array();
//print('$access_credentials: ');var_dump($access_credentials);
//print('$profiles->_(\'profile_name=\' . $profiles->enc($profileid)): ');var_dump($profiles->_('profile_name=' . $profiles->enc($profileid)));
//$profile = $profiles->_('.profile_name=' . $profiles->enc(xml_enc($profileid)));
//$profile = $profiles->_('.profile_id=' . $profiles->enc(xml_enc($profile_id_by_request)));
$profilename = $profile_name_by_request; // never override user input at this stage
$created_new_profile = false;
//if($profile_id_by_request != false && $profile_name_by_request == false && $password === false && $biometric === false) {
if($profile_id_by_request != false && $profile_name_by_request == false) {	
	$profileid = $profile_id_by_request;
//} elseif($profile_id_by_request == false && $profile_name_by_request != false && $password === false && $biometric === false) {
} elseif($profile_id_by_request == false && $profile_name_by_request != false) {	
	$profile_by_name_only = $profiles->_('.profile_name=' . $profiles->enc($profile_name_by_request));
	if(is_array($profile_by_name_only) && sizeof($profile_by_name_only) === 1) {
		$profileid = $profiles->_('profile_id', $profile_by_name_only);
		$access_credentials['profile_id'] = $profileid;
	} elseif(is_array($profile_by_name_only) && sizeof($profile_by_name_only) > 1) {
		$profileid = $profiles->_('profile_id', $profile_by_name_only[0]); // use the first one
		$access_credentials['profile_id'] = $profileid;
	}
//} elseif($profile_id_by_request != false && $profile_name_by_request != false && $password === false && $biometric === false) {
} elseif($profile_id_by_request != false && $profile_name_by_request != false) {	
	//print('yer478541<br>');
	$profileid = $profile_id_by_request;
	$profile_by_id_only = $profiles->_('.profile_id=' . $profile_id_by_request);
	if(is_array($profile_by_id_only) && sizeof($profile_by_id_only) === 1) { // ids are not duplicated
		//print('yer478542<br>');
		if($profiles->_('profile_name', $profile_by_id_only) === $profilename) {
			//print('yer478543.1<br>');
			$access_credentials['profile_id'] = $profileid;
		} elseif($profiles->_('profile_name', $profile_by_id_only) === $profiles->_('profile_id', $profile_by_id_only)) { // upgrade to a named account
			//print('yer478543.2<br>');
			$profile_by_id_only = $profiles->__('profile_name', $profile_name_by_request, $profile_by_id_only);
			//print('$profile_by_id_only before adding password: ');var_dump($profile_by_id_only);
			//if($password !== false) {
			//	//$profiles->new_('<password>' . password_hash($password, PASSWORD_DEFAULT) . '</password>', $profile_by_id_only);
				//$profiles->__('profile_password', password_hash($password, PASSWORD_DEFAULT), $profile_by_id_only);
			//	$profile_by_id_only = $profiles->__('profile_password', password_hash($password, PASSWORD_DEFAULT), $profile_by_id_only);
			//}
			//print('$profile_by_id_only after adding password: ');var_dump($profile_by_id_only);
			//if($biometric !== false) {
			//	//$profiles->new_('<biometric>' . $biometric . '</biometric>', $profile_by_id_only);
			//	$profiles->__('profile_biometric', $biometric, $profile_by_id_only);
			//}
			$access_credentials['profile_id'] = $profileid;
			//$profilename = $profile_name_by_request;
		} else { // prime for a new account
			//print('yer478543.5<br>');
			$profile_by_name_only = $profiles->_('.profile_name=' . $profiles->enc($profile_name_by_request));
			//print('$profile_by_name_only: ');var_dump($profile_by_name_only);
			if(is_array($profile_by_name_only) && sizeof($profile_by_name_only) === 1) { // ignore the id and use a no password named account
				//print('yer478543.6<br>');
				$profileid = $profiles->_('profile_id', $profile_by_name_only);
				$access_credentials['profile_id'] = $profileid;
				//$profilename = $profile_name_by_request;
			} elseif(is_array($profile_by_name_only) && sizeof($profile_by_name_only) > 1) { // ignore the id and use a no password named account
				//print('yer478543.7<br>');
				$profileid = $profiles->_('profile_id', $profile_by_name_only[0]); // use the first one
				$access_credentials['profile_id'] = $profileid;
				//$profilename = $profile_name_by_request;
			} else {
				//print('yer478543.8<br>');
				$profileid = false;
			}
		}
	} else { 
		//print('yer478544<br>');
		$profile_by_name_only = $profiles->_('.profile_name=' . $profiles->enc($profile_name_by_request));
		if(is_array($profile_by_name_only) && sizeof($profile_by_name_only) === 1) { // ignore the id and use a no password named account
			//print('yer478545<br>');
			$profileid = $profiles->_('profile_id', $profile_by_name_only);
			$access_credentials['profile_id'] = $profileid;
			//$profilename = $profile_name_by_request;
		} elseif(is_array($profile_by_name_only) && sizeof($profile_by_name_only) > 1) { // ignore the id and use a no password named account
			//print('yer478546<br>');
			$profileid = $profiles->_('profile_id', $profile_by_name_only[0]); // use the first one
			$access_credentials['profile_id'] = $profileid;
			//$profilename = $profile_name_by_request;
		} else {
			// ??
			print('yer478547<br>');
		}
	}
} /* password processing is done below (should it be?) elseif($profile_id_by_request != false && $profile_name_by_request == false && $password != false && $biometric === false) {
	fatal_error('not written yet374857');
} elseif($profile_id_by_request != false && $profile_name_by_request != false && $password != false && $biometric === false) {
	
} elseif($profile_id_by_request == false && $profile_name_by_request != false && $password != false && $biometric === false) {
	$profile_by_name_and_password = $profiles->_('.profile_name=' . $profile_name_by_request);
	if(is_array($profile) && sizeof($profile) === 1) {
		$profileid = $profiles->_('profile_id', $profile_by_name_only);
		$profilename = $profile_name_by_request;
	}
} elseif($profile_id_by_request != false && $profile_name_by_request != false && $password != false && $biometric != false) {
	fatal_error('not written yet374859');
} */else { // going only on IP
	if($password === false && $biometric === false) {
		// first check if there is another profile with the same IP without access credentials, then make a new profile only if there isn't		
		$profiles_with_IP = $profiles->_('.profile_IP=' . $profiles->enc($IP));
		if(is_array($profiles_with_IP) && sizeof($profiles_with_IP) > 0) {
			foreach($profiles_with_IP as $profile_with_IP) {
				if($profiles->_('password', $profile_with_IP) === false && $profiles->_('biometric', $profile_with_IP) === false) {
					$profileid = $profiles->_('profile_id', $profile_with_IP);
					$profilename = $profiles->_('profile_name', $profile_with_IP);
					$access_credentials['profile_id'] = $profileid;
					//$found_a_profile_to_use = true;
					break;
				}
			}
		}
	}
}
$profile = $profiles->_('.profile_id=' . $profileid);
if(sizeof($profile) > 1) {
	print('$profile_id_by_request, $profileid, $profile: ');var_dump($profile_id_by_request, $profileid, $profile);
	fatal_error('apparently there is more than one profile with the same id.');
}
$found_a_profile_to_use = false;
if(is_array($profile) && sizeof($profile) > 0) {
	$found_a_profile_to_use = true;
} else {
//if(!$found_a_profile_to_use) {
	//if($profileid === false) {
	//if($profile_id_by_request === false) {
	//	//$profileid = guid($IP . time());
	//	$profileid = guid($IP . time());
	//	$profilename = $profileid;
	//	//$access_credentials['profilename'] = $profileid;
	//	$access_credentials['profile_id'] = $profileid;
	//	$access_credentials['profile_name'] = $profileid;
	//}
	//if($profileid === false) {
		$profileid = guid($IP . time());
		$access_credentials['profile_id'] = $profileid;
	//}
	if($profilename === false) {
		$profilename = $profileid;
		$access_credentials['profile_name'] = $profileid;
	}
	//fatal_error('about to create a new profile!~ styles are not being included in proper order...');
	$profiles = new_profile($access_credentials, $profiles);
	$created_new_profile = true;
}
//print('$profile, $found_a_profile_to_use, $created_new_profile: ');var_dump($profile, $found_a_profile_to_use, $created_new_profile);
//$profile = $profiles->_('.profile_name=' . $profiles->enc(xml_enc($profileid)));
//print('$profile, $profiles->to_string($profiles->LOM), $profiles->enc(xml_enc($profileid)): ');$profiles->var_dump_full($profile, $profiles->to_string($profiles->LOM), $profiles->enc(xml_enc($profileid)));
if($profile === false) {
	print('Could not properly identify profile. Try <a href="' . basename($_SERVER['PHP_SELF']) . '">reloading the page</a>.');exit(0);
}
?>
<link rel="stylesheet" href="bootstrap.css">
<script src="jquery-3.4.1.js"></script>
<script src="bootstrap.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.DataTable').DataTable( {
		responsive: true
		//responsive: false
		/*responsive: {
			details: false
			breakpoints: [
				{name: 'bigdesktop', width: Infinity},
				{name: 'meddesktop', width: 1480},
				{name: 'smalldesktop', width: 1280},
				{name: 'medium', width: 1188},
				{name: 'tabletl', width: 1024},
				{name: 'btwtabllandp', width: 848},
				{name: 'tabletp', width: 768},
				{name: 'mobilel', width: 480},
				{name: 'mobilep', width: 320}
			]
		}*/
	} );
	
	
	
		
	$("#currency_system_information_toggle").click(function(){
        $("#currency_system_information").slideToggle("slow");
    });
	$("#login_toggle").click(function(){
        $("#login").slideToggle("slow");
    });
	$("#new_profile_toggle").click(function(){
        $("#new_profile").slideToggle("slow");
    });
    $("#profile_details_toggle").click(function(){
        $("#profile_details").slideToggle("slow");
    });
	$("#messages_toggle").click(function(){
        $("#messages").slideToggle("slow");
    });
});
function auction_endtime_preset(newvalue) {
    document.getElementById("new_auction_endtimeformatted").value = newvalue;
}
function bounty_endtime_preset(newvalue) {
    document.getElementById("new_bounty_endtimeformatted").value = newvalue;
}
</script>
<link rel="stylesheet" type="text/css" href="css/styles.css">
<link rel="stylesheet" type="text/css" href="DataTables-1.10.16/css/jquery.dataTables.css"/>
<link rel="stylesheet" type="text/css" href="Responsive-2.2.0/css/responsive.dataTables.css"/>
<style>
#new_bounty_toggle { display: none; }
#new_auction_toggle { display: none; }
</style>
 
<script type="text/javascript" src="DataTables-1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="Responsive-2.2.0/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="tabber.js"></script>
<script type="text/javascript">

/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>

</head>
<body>
<img src="images/OES.png" width="200" height="100" alt="Open Exploration Society" />
<!--p>show last 10 currency transactions average or last 10 books added or last 10 books funded etc.</p-->
<?php
//if($counts_contents === false) { // use the record if it's there or calculate otherwise
if(!file_exists('ebooks' . DS . 'count.txt')) { // use the record if it's there or calculate otherwise
	$dir = 'ebooks';
	$handle = opendir($dir);
	$ebooks_count = 0;
	$files_count = 0;
	while(false !== ($entry = readdir($handle))) {
		//print('$entry, is_dir($entry), is_dir($handle . DS . $entry): ');var_dump($entry, is_dir($entry), is_dir($dir . DS . $entry));
		if($entry === '.' || $entry === '..' || substr($entry, strlen($entry) - 4, 4) === '.txt') {
			// ignore it
			//print('ignore it<br>');
		} elseif(is_dir($dir . DS . $entry)) {
			//print('count it<br>');
			$ebooks_count++;
			$handle2 = opendir($dir . DS . $entry);
			while(false !== ($entry2 = readdir($handle2))) {
				if($entry2 === '.' || $entry2 === '..') {
					
				} else {
					$files_count++;
				}
			}
			closedir($handle2);
		}
	}
	closedir($handle);
	// write the file if the counts had to be calculated
	file_put_contents('ebooks' . DS . 'count.txt', $ebooks_count . '
' . $files_count);
} else {
	$counts_contents = file_get_contents('ebooks' . DS . 'count.txt');
	$counts_array = explode('
', $counts_contents);
	$ebooks_count = $counts_array[0];
	$files_count = $counts_array[1];
}
//print('$counts_contents, $ebooks_count, $files_count: ');var_dump($counts_contents, $ebooks_count, $files_count);

if(!file_exists('funded_ebooks' . DS . 'count.txt')) { // use the record if it's there or calculate otherwise
	$dir = 'funded_ebooks';
	$handle = opendir($dir);
	$funded_ebooks_count = 0;
	$funded_files_count = 0;
	while(false !== ($entry = readdir($handle))) {
		//print('$entry, is_dir($entry), is_dir($dir . DS . $entry): ');var_dump($entry, is_dir($entry), is_dir($dir . DS . $entry));
		if($entry === '.' || $entry === '..') {
			// ignore it
		} elseif(substr($entry, strlen($entry) - 4, 4) === '.xml') {
			$funded_ebooks_count++;
			$funded_ebook = new O($dir . DS . $entry);
			$formats = $funded_ebook->_('format');
			//print('$funded_ebook->code, $formats: ');var_dump_full($funded_ebook->code, $formats);
			if(!is_array($formats)) {
				$formats = array($formats);
			}
			$formats = array_unique($formats);
			$funded_files_count += sizeof($formats);
		}
	}
	closedir($handle);
	// write the file if the counts had to be calculated
	file_put_contents('funded_ebooks' . DS . 'count.txt', $funded_ebooks_count . '
' . $funded_files_count);
} else {
	$counts_contents = file_get_contents('funded_ebooks' . DS . 'count.txt');
	$counts_array = explode('
', $counts_contents);
	$funded_ebooks_count = $counts_array[0];
	$funded_files_count = $counts_array[1];
}
//print('$counts_contents, $funded_ebooks_count, $funded_files_count: ');var_dump($counts_contents, $funded_ebooks_count, $funded_files_count);
//dump_total_time_taken();

?>

<form action="list_ebooks.php" method="post">
<p>There are 
<?php /*print('$access_credentials, $tab_settings: ');var_dump($access_credentials, $tab_settings);*/print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST)); ?>
<input type="submit" value="<?php print($ebooks_count . ' eBooks in ' . $files_count . ' files'); ?>" class="button_as_link" />
 on this site. The <a href="torrents/OES ebooks.torrent">Open Exploration Society eBooks torrent</a> (test file for now, show date) is available for download.</p>
</form>
<form action="list_funded_ebooks.php" method="post">
<p>
<?php print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST)); ?>
<input type="submit" value="<?php print($funded_files_count . ' eBooks files'); ?>" class="button_as_link" />
 have been funded; get out there and earn some <img src="images/EX.png" alt="EX" />!</p>
</form>
<ul id="menu">
<li>
<?php
if(basename($_SERVER['PHP_SELF']) === 'fund_ebook_form.php') { // non-linked
	print('fund eBook');
} else {
	print('<form action="fund_ebook_form.php" method="post">
');
print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST));
print('<input type="submit" value="fund eBook" class="button_as_link" />
</form>');
}
?>
</li>
<li>
<?php
if(basename($_SERVER['PHP_SELF']) === 'add_ebook_form.php') { // non-linked
	print('add eBook');
} else {
	print('<form action="add_ebook_form.php" method="post">
');
print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST));
print('<input type="submit" value="add eBook" class="button_as_link" />
</form>');
}
?>
</li>
<li>
<?php
if(basename($_SERVER['PHP_SELF']) === 'information.php') { // non-linked
	print('information');
} else {
	print('<form action="information.php" method="post">
');
print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST));
print('<input type="submit" value="information" class="button_as_link" />
</form>');
}
?>
</li>
<!--li>exchanging currencies can be coded after</li-->
<!--li>would be nice to tee the target in the bottom left like you normally do when hovering on these forms as links</li-->
</ul>

<div class="container-fluid">
  <div class="row" style="height: 30px;">
    <div class="col-sm-5"><?php
//print('$profile: ');var_dump($profile);exit(0);
//print('$_REQUEST: ');var_dump($_REQUEST);
$existing_parameters = array();
$existing_parameters['password'] = $profiles->_('password', $profile);
$existing_parameters['biometric'] = $profiles->_('biometric', $profile);
//print('$existing_parameters: ');var_dump($existing_parameters);
$access_granted = true;
if($profileid == false) { // until IP or tokens are implemented
	//print('ub37480<br>');
} elseif($created_new_profile) {
	//print('ub37481<br>');
} else {
	//print('ub37482<br>');
	//print('$password, $existing_parameters[\'password\']: ');var_dump($password, $existing_parameters['password']);
	if($password === false) {
		//print('ub37483<br>');
	} elseif($existing_parameters['password'] === '' || (is_array($existing_parameters['password']) && sizeof($existing_parameters['password']) === 0)) {
		//print('ub37484<br>');
		//print('$profile before adding password: ');var_dump($profile);
		//$profiles->new_('<password>' . password_hash($password, PASSWORD_DEFAULT) . '</password>', $profile);
		$profile = $profiles->__('password', password_hash($password, PASSWORD_DEFAULT), $profile);
		//print('$profile after adding password: ');var_dump($profile);
	} elseif(password_verify($password, $existing_parameters['password'])) {
		//print('ub37485<br>');
	} /*elseif(!isset($existing_parameters['password'])) { // then we have to add it to the profile
		$password_hash = password_hash($password, PASSWORD_DEFAULT);
		$profiles->__('password', $password_hash, $profiles->_('.profile_name=' . $profiles->enc(xml_enc($profileid))));
		$existing_parameters['password'] = $password_hash;
	} */else {
		//warning('incorrect information entered for profile: ' . $profileid);
		warning('incorrect information entered for profile: ' . $profilename);
		$access_granted = false;
	}
	if($biometric === false) {
		
	} /*elseif(is_array($existing_parameters['biometric']) && sizeof($existing_parameters['biometric']) === 0) {
		$profiles->new_('<biometric>' . $biometric . '</biometric>', $profile);
	} */elseif($biometric === $existing_parameters['biometric']) {
		
	} /*elseif(!isset($existing_parameters['biometric'])) { // then we have to add it to the profile
		$profiles->__('biometric', $biometric, $profiles->_('.profile_name=' . $profiles->enc(xml_enc($profileid))));
		$existing_parameters['biometric'] = $biometric;
	} */else {
		//warning('incorrect information entered for profile: ' . $profileid);
		warning('incorrect information entered for profile: ' . $profilename);
		$access_granted = false;
	}
}
//print('$access_granted: ');var_dump($access_granted);
if(!$access_granted) {
	unset($profileid);
	unset($password);
	unset($biometric);
	unset($IP);
} else {
	//$created_new_profile = false;
	// update the currency amount
	if(!$created_new_profile) { // update the IP login count
		//print('time(), $profiles->_(\'lasttime\', $profile), $profile: ');var_dump(time(), $profiles->_('lasttime', $profile), $profile);
		$currency_to_add = time() - $profiles->_('lasttime', $profile);
		$profile = $profiles->__('lasttime', time(), $profile);
		$profile = $profiles->add($currency_to_add, 'currency', $profile);
		//print('$profile: ');$profiles->var_dump_full($profile);
		//print('$profiles->_(\'IP=\' . $IP, $profile): ');$profiles->var_dump_full($profiles->_('IP=' . $IP, $profile));
		if($profiles->_('IP=' . $IP, $profile)) {
			//$profileIP_indices = $profiles->get_indices('IP=' . $IP, $profile);
			//$profiles->increment_attribute('logincount', $profileIP_indices[0] - 1); // -1 to get the tag instead of the text
			$profiles->increment_attribute('logincount', $profiles->get_tagged('IP=' . $IP, $profile));
		} else {
			$profiles->new_('<IP logincount="1">' . $IP . '</IP>
', $profile);
		}
		//print('$profiles->LOM: ');$profiles->var_dump_full($profiles->LOM);exit(0);
	}
	$existing_parameters['IP'] = get_profile_IP($profileid, $profiles);
	// do not show currency or do balancing or scoring
	/*print('$access_credentials, $profiles: ');var_dump($access_credentials, $profiles);
	$profile_score = get_profile_score($access_credentials, $profiles);
	$profile_currency = get_profile_currency(xml_enc($profileid), $profiles);
	$profile_unavailablecurrency = get_profile_unavailablecurrency(xml_enc($profileid), $profiles);
	// redistribute profile currency according to the current access level determined by the profile score
	$currency_sum_for_balancing = $profile_currency + $profile_unavailablecurrency;
	if($currency_sum_for_balancing < 10000) {
		$potential_balanced_currency = 10000 * $profile_score;
		$potential_balanced_unavailablecurrency = 10000 - $potential_balanced_currency;
		if($potential_balanced_unavailablecurrency > $currency_sum_for_balancing) {
			$balanced_unavailablecurrency = $currency_sum_for_balancing;
			$balanced_currency = 0;
		} else {
			$balanced_unavailablecurrency = $potential_balanced_unavailablecurrency;
			$balanced_currency = $currency_sum_for_balancing - $potential_balanced_unavailablecurrency;
		}
	} else {
		$balanced_currency = $currency_sum_for_balancing * $profile_score;
		$balanced_unavailablecurrency = $currency_sum_for_balancing - $balanced_currency;
	}
	$profiles->__('currency', $balanced_currency, $profiles->_('.profile_name=' . $profiles->enc(xml_enc($profileid))));
	$profiles->__('unavailablecurrency', $balanced_unavailablecurrency, $profiles->_('.profile_name=' . $profiles->enc(xml_enc($profileid))));*/
}
//print('$profileid, $password, $biometric, $IP2: ');var_dump($profileid, $password, $biometric, $IP);

$profiles->save(); // would like to not always update this file but we may need to to get the IP each time. would be better to specifically only update profiles.xml if the user used the login form
// places that change the profiles.xml are: upgrading an account name from guid to input name, creating an account, changing the IP data for an account

if($profileid == false) {
	print('<div id="login_toggle">login</div>');
} else {
	// current profile information
	print('<div id="login_toggle">
<div align="right">current profile: ' . xml_enc($profilename) . '<img src="images/profile_icon.png" /></div>
</div>');
/*$profile_currency = get_profile_currency(xml_enc($profileid), $profiles);
print('<div align="right">currency: ' . $profile_currency . '</div>');
$profile_unavailablecurrency = get_profile_unavailablecurrency(xml_enc($profileid), $profiles);
if($profile_unavailablecurrency > 0) {
	print('<div align="right">unavailable currency: ' . $profile_unavailablecurrency . '</div>');
}
// reputation would be split among these so it's up to reputation to disencentivize I guess. need a complex formula for reputation
$profile_reputation = get_profile_reputation(xml_enc($profileid), $profiles, true);
print('<div align="right">reputation: (' . $profile_reputation . ')</div>');
//print('<div align="right">IP: ' . $IP . '</div>');
print('<div align="right">profile identity score: ' . $profile_score_percent . '%</div>*/
}

//print('$profileid: ');var_dump($profileid);
print('<div id="login">');
/*print('<form action="profile.php?id=' . $profileid . '" method="post" style="text-align: right;">
');*/
print('<form action="profile.php" method="post" style="text-align: right;">
');
print('<input type="hidden" name="id" value="' . $profileid . '" />
');
print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST));
print('<input type="submit" value="your profile page" class="button_as_link" />
</form>');
?>
<h2 style="margin: 0;">profile login</h2>
<p>There are no required fields.</p>
<form action="<?php print(basename($_SERVER['PHP_SELF'])); ?>" method="post">
<?php print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST)); ?>
profile name: <input name="profile_name" type="text" size="50" value="<?php print(xml_enc($profilename)); ?>" /><br>
password: <input name="password" type="password" size="50" /><br>
<!--biometric: <input name="biometric" type="text" length="100" /><br>-->
<input type="submit" value="login" class="good_button" />
</form>
<?php

//warning('password was not being verified. want to allow multiple password maybe. why is there an array in $existing_parameters[\'password\']?');
print('<!--div align="right"><a href="">choose theme</a></div-->');

?>
<div id="new_profile_toggle" style="text-align: left;">create new profile</div>
<div id="new_profile">
<h3>create new profile</h3>
<form action="<?php print(basename($_SERVER['PHP_SELF'])); ?>" method="post">
profile name: <input name="profile_name" type="text" size="50" /><br>
password: <input name="password" type="password" size="50" /><br>
<!-- other profile verification parameters such as biometric, authenticator, etc. -->
<input type="submit" value="create profile" class="good_button" />
</form>
</div>
</div>
</div>
</div>
</div>
<div style="clear:both;"></div>