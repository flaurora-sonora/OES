<?php

function add_bounty($reward, $starttime, $endtime, $profilename, $description, $profiles, $bounties) {
	//print('$reward, $starttime, $endtime, $profilename, $description: ');var_dump($reward, $starttime, $endtime, $profilename, $description);
	if($endtime - $starttime < 1200) { // seems we're off by some minutes somewhere
		add_warning_message('Bounties must be at least 20 minutes long.');
	} else {
		$bounty_id_counter = file_get_contents('bounty_id_counter.txt');
		$duplicate_id = false;
		if($bounties->_('.bounty_id=' . $bounty_id_counter) !== false) {
			add_warning_message('Cannot add a bounty that already exists.');
			$duplicate_id = true;
		}
		$sufficient_currency = true;
		$profile_currency = get_profile_currency($profilename, $profiles);
		if($profile_currency >= $reward) {
			
		} else {
			add_warning_message('Insufficent currency (' . $profile_currency . ') to add a bounty with a reward of (' . $reward . ').');
			$sufficient_currency = false;
		}
		if(!$duplicate_id && $sufficient_currency) {
			$bounties->new_('<bounty>
<id>' . $bounty_id_counter . '</id>
<reward>' . $reward . '</reward>
<starttime>' . $starttime . '</starttime>
<endtime>' . $endtime . '</endtime>
<profilename>' . $profilename . '</profilename>
<description>' . $description . '</description>
<accepted>
</accepted>
<completed>
</completed>
</bounty>
', 'bounties');
			//print('$profiles in add_bounty: ');var_dump($profiles);
			$bounty_id_counter++;
			file_put_contents('bounty_id_counter.txt', $bounty_id_counter);
			$profiles = add_bounty_reward_to_profile_currency(-1 * $reward, $profilename, $profiles);
			return array(true, $profiles, $bounties);
		}
	}
	return array(false, $profiles, $bounties);
}

function add_auction($starting_bid, $buyout, $starttime, $endtime, $profilename, $description, $profiles, $auctions) {
	//print('$starting_bid, $starttime, $endtime, $profilename, $description: ');var_dump($starting_bid, $starttime, $endtime, $profilename, $description);
	if($endtime - $starttime < 1200) { // seems we're off by some minutes somewhere
		add_warning_message('Auctions must be at least 20 minutes long.');
	} else {
		$auction_id_counter = file_get_contents('auction_id_counter.txt');
		$duplicate_id = false;
		if($auctions->_('.auction_id=' . $auction_id_counter) !== false) {
			add_warning_message('Cannot add an auction that already exists.');
			$duplicate_id = true;
		}
		if($buyout === false) {
			$buyout_string = '';
		} else {
			$buyout_string = '<buyout>' . $buyout . '</buyout>
';
		}
		if(!$duplicate_id) {
			$auctions->new_('<auction>
<id>' . $auction_id_counter . '</id>
<startingbid>' . $starting_bid . '</startingbid>
' . $buyout_string . '<starttime>' . $starttime . '</starttime>
<endtime>' . $endtime . '</endtime>
<profilename>' . $profilename . '</profilename>
<description>' . $description . '</description>
<bids>
</bids>
</auction>
', 'auctions');
			//print('$profiles in add_auction: ');var_dump($profiles);
			$auction_id_counter++;
			file_put_contents('auction_id_counter.txt', $auction_id_counter);
			//$profiles = add_auction_starting_bid_to_profile_currency(-1 * $starting_bid, $profilename, $profiles);
			return array(true, $profiles, $auctions);
		}
	}
	return array(false, $profiles, $auctions);
}

function bid_on_auction($auction_id, $auction_bid, $profilename, $profiles, $auctions) {
	//print('$auction_id, $auction_bid, $profilename: ');var_dump($auction_id, $auction_bid, $profilename);
	$auction = $auctions->_('auctions_.auction_id=' . $auction_id);
	$bids = $auctions->_('bids_bid', $auction);
	//print('$auction, $bid: ');var_dump($auction, $bid);
	$satisfied_starting_bid = true;
	$last_profile_bid_amount = 0;
	if(is_array($bids) && sizeof($bids) > 0) {
		foreach($bids as $last_bid_index => $last_bid) { // check for previous bids by the same profile
			$last_bid_amount = $auctions->_('amount', $last_bid);
			if($auctions->_('bidder', $last_bid) === $profilename) {
				$last_profile_bid_amount = $last_bid_amount;
			}
		}
	} else {
		$startingbid = $auctions->_('startingbid', $auction);
		if($auction_bid < $startingbid) {
			add_warning_message('Cannot bid less than the starting bid on an auction.');
			$satisfied_starting_bid = false;
		}
	}
	$sufficient_auction_increment = false;
	if($auction_bid >= ceil(1.10 * $last_bid_amount)) {
		$sufficient_auction_increment = true;
	} else {
		add_warning_message('A new bid on an auction must be at least 10% higher than the current bid.');
	}
	$sufficient_currency = true;
	$profile_currency = get_profile_currency($profilename, $profiles);
	if($profile_currency >= $auction_bid - $last_profile_bid_amount) {
		
	} else {
		add_warning_message('Insufficent currency (' . $profile_currency . ') to bid on an auction with a bid of (' . $auction_bid . ').');
		$sufficient_currency = false;
	}
	if($satisfied_starting_bid && $sufficient_auction_increment && $sufficient_currency) {
		//print('$auctions1: ');var_dump($profiles, $auctions);
		$auctions->new_('<bid><bidder>' . $profilename . '</bidder><amount>' . $auction_bid . '</amount></bid>
', $auctions->_('bids', $auction));
		$profiles = add_to_profile_currency(-1 * ($auction_bid - $last_profile_bid_amount), $profilename, $profiles);
		//print('$auctions2: ');var_dump($profiles, $auctions);exit(0);
		return array(true, $profiles, $auctions);
	}
	return array(false, $profiles, $auctions);
}

function buyout_auction($auction_id, $auction_bid, $profilename, $profiles, $auctions) {
	$auction = $auctions->_('auctions_.auction_id=' . $auction_id);
	$bids = $auctions->_('bids_bid', $auction);
	$last_profile_bid_amount = 0;
	if(is_array($bids) && sizeof($bids) > 0) {
		foreach($bids as $last_bid_index => $last_bid) { // check for previous bids by the same profile
			$last_bid_amount = $auctions->_('amount', $last_bid);
			if($auctions->_('bidder', $last_bid) === $profilename) {
				$last_profile_bid_amount = $last_bid_amount;
			}
		}
	}
	$sufficient_currency = true;
	$profile_currency = get_profile_currency($profilename, $profiles);
	if($profile_currency >= $auction_bid - $last_profile_bid_amount) {
		
	} else {
		add_warning_message('Insufficent currency (' . $profile_currency . ') to buyout an auction with a bid of (' . $auction_bid . ').');
		$sufficient_currency = false;
	}
	if($sufficient_currency) {
		$auctions->__('endtime', time(), $auctions->_('auctions_.auction_id=' . $auction_id));
		$auctions->new_('<bid><bidder>' . $profilename . '</bidder><amount>' . $auction_bid . '</amount></bid>
', $auctions->_('bids', $auctions->_('auctions_.auction_id=' . $auction_id)));
		$auctions->new_('<acceptedbid><bid><bidder>' . $profilename . '</bidder><amount>' . $auction_bid . '</amount></bid></acceptedbid>
', $auctions->_('auctions_.auction_id=' . $auction_id));
		$auction_offerer = $auctions->_('profilename', $auctions->_('auctions_.auction_id=' . $auction_id));
		if($profilename !== $auction_offerer) {
			$profiles = add_reputation(1, $auction_offerer, $profiles);
			$profiles = add_reputation(1, $profilename, $profiles);
		}
		$reward_based_on_profile_score = round(get_profile_fully_logged_in_score($auction_offerer, $profiles) * $auction_bid, 10); // is it a problem that a bounty could be completed here when an profile is not fully logged into? why precision of 10?
		//print('$auction_id, $auction_offerer, $profilename, $auction_bid, $reward_based_on_profile_score: ');var_dump($auction_id, $auction_offerer, $profilename, $auction_bid, $reward_based_on_profile_score);exit(0);
		$profiles = add_to_profile_currency($reward_based_on_profile_score, $auction_offerer, $profiles);
		$profiles = add_to_profile_unavailablecurrency($auction_bid - $reward_based_on_profile_score, $auction_offerer, $profiles);
		$profiles = add_to_profile_currency(-1 * ($auction_bid - $last_profile_bid_amount), $profilename, $profiles);
		return array(true, $profiles, $auctions);
	}
	return array(false, $profiles, $auctions);
}

function new_bounty($id, $reward, $starttime, $endtime, $profilename, $description, $profiles, $bounties) { // alias
	return add_bounty($id, $reward, $starttime, $endtime, $profilename, $description, $profiles, $bounties);
}

function submit_bounty_as_complete($id, $completer, $completion_details, $bounties) {
	$bounty = $bounties->_('.bounty_id=' . $id);
	$bounties->new_('<completiondetails><name>' . $completer . '</name><comment>' . $completion_details . '</comment></completiondetails>
', $bounties->_('completed', $bounty));
	return $bounties;
}

function complete_bounty($id, $name_of_completer, $profiles, $bounties, $completedbounties) {
	$bounty = $bounties->_('.bounty_id=' . $id);
	$bounty_offerer = $bounties->_('profilename', $bounty);
	$reward = $bounties->_('reward', $bounty);
	// add to the reputation of the completer and the issuer (if they are not the same)
	if($name_of_completer !== $bounty_offerer) {
		$profiles = add_reputation(1, $bounty_offerer, $profiles);
		$profiles = add_reputation(1, $name_of_completer, $profiles);
	}
	$reward_based_on_profile_score = round(get_profile_fully_logged_in_score($name_of_completer, $profiles) * $reward, 10); // is it a problem that a bounty could be completed here when an profile is not fully logged into? why precision of 10?
	//print('$id, $name_of_completer, $bounty_offerer, $reward, $reward_based_on_profile_score: ');var_dump($id, $name_of_completer, $bounty_offerer, $reward, $reward_based_on_profile_score);exit(0);
	$profiles = add_bounty_reward_to_profile_currency($reward_based_on_profile_score, $name_of_completer, $profiles);
	$profiles = add_bounty_reward_to_profile_unavailablecurrency($reward - $reward_based_on_profile_score, $name_of_completer, $profiles);
	$bounties->__('endtime', time(), $bounties->_('.bounty_id=' . $id));
	$bounties->new_('<acceptedcompleter>' . $name_of_completer . '</acceptedcompleter>
', $bounties->_('.bounty_id=' . $id));
	//$completedbounties->new_($bounty, 'completedbounties');
	//$bounties->delete($bounty);
	return array($profiles, $bounties, $completedbounties);
}

function remove_bounty($id, $bounties) {
	$bounties->delete('.bounty_id=' . $id);
	return $bounties;
}

function get_profile_currency($profilename, $profiles) {
	return $profiles->_('currency', '.profile_name=' . $profiles->enc($profilename));
}

function get_currency($profilename, $profiles) { // alias
	return get_profile_currency($profilename, $profiles);
}

function get_profile_unavailablecurrency($profilename, $profiles) {
	return $profiles->_('unavailablecurrency', '.profile_name=' . $profiles->enc($profilename));
}

function get_unavailablecurrency($profilename, $profiles) { // alias
	return get_profile_unavailablecurrency($profilename, $profiles);
}

function get_profile_reputation($profilename, $profiles, $force_update = false) {
	//print('$profilename, $profiles: ');var_dump($profilename, $profiles);
	if(isset($reputations[$profilename]) && !$force_update) {
		
	} else {
		$reputations[$profilename] = $profiles->_('reputation', '.profile_name=' . $profiles->enc($profilename));
	}
	return $reputations[$profilename];
}

function get_reputation($profilename, $profiles) { // alias
	return get_profile_reputation($profilename, $profiles);
}

function get_profile_starttime($profilename, $profiles) {
	return $profiles->_('starttime', '.profile_name=' . $profiles->enc($profilename));
}

function get_starttime($profilename, $profiles) { // alias
	return get_profile_starttime($profilename, $profiles);
}

function get_profile_password($profilename, $profiles) {
	return $profiles->_('password', '.profile_name=' . $profiles->enc($profilename));
}

function get_password($profilename, $profiles) { // alias
	return get_profile_password($profilename, $profiles);
}

function get_profile_biometric($profilename, $profiles) {
	return $profiles->_('biometric', '.profile_name=' . $profiles->enc($profilename));
}

function get_biometric($profilename, $profiles) { // alias
	return get_profile_biometric($profilename, $profiles);
}

function get_profile_IP($profilename, $profiles) {
	$profile = $profiles->_('.profile_name=' . $profiles->enc($profilename));
	$IPs = $profiles->_('IP', $profile);
	//print('$IPs: ');var_dump($IPs);
	//print('$profiles->context[sizeof($profiles->context) - 1][2]: ');var_dump($profiles->context[sizeof($profiles->context) - 1][2]);
	$highest_login_count = 0;
	if($IPs) {
		if(is_array($IPs)) {
			foreach($IPs as $previous_IP_index => $previous_IP) {
				$logincount = $profiles->get_attribute('logincount', $previous_IP_index - 1); // -1 because the attributes are on the tag, not the text in the tag
				if($logincount > $highest_login_count) {
					$highest_login_count_IP = $previous_IP;
					$highest_login_count = $logincount;
				}
			}
		} else {
			$highest_login_count_IP = $IPs;
		}
	}
	//print('$highest_login_count_IP: ');var_dump($highest_login_count_IP);
	//print('$profiles->LOM: ');$profiles->var_dump_full($profiles->LOM);exit(0);
	return $highest_login_count_IP;
}

function get_IP($profilename, $profiles) {
	return get_profile_IP($profilename, $profiles);
}

function profile_has_a_password($profilename, $profiles) {
	if($profiles->_('password', '.profile_name=' . $profiles->enc($profilename)) !== false) {
		return true;
	} else {
		return false;
	}
}

function profile_has_a_biometric($profilename, $profiles) {
	if($profiles->_('biometric', '.profile_name=' . $profiles->enc($profilename)) !== false) {
		return true;
	} else {
		return false;
	}
}

function profile_has_a_IP($profilename, $profiles) {
	if($profiles->_('IP', '.profile_name=' . $profiles->enc($profilename)) !== false) {
		return true;
	} else {
		return false;
	}
}

function profile_has_an_IP($profilename, $profiles) { // alias
	return profile_has_a_IP($profilename, $profiles);
}

function update_profile_parameter($profilename, $parameter_name, $parameter_value) {
	fatal_error('update_profile_parameter is obsolete');
	if($parameter_name === 'currency' || $parameter_name === 'unavailablecurrency') {
		$parameter_value = round($parameter_value, 10);
	}
	$profiles_contents = file_get_contents('profiles.xml');
	preg_match('/<profile>
<name>' . preg_escape($profilename) . '<\/name>.+?<\/profile>/is', $profiles_contents, $profile_match);
	$profile_string = $profile_match[0];
	preg_replace('/<' . $parameter_name . '>([^<>]+)<\/' . $parameter_name . '>/is', '<' . $parameter_name . '>' . $parameter_value . '</' . $parameter_name . '>', $profile_string, 1, $count); 
	if($count > 0) {
		
	} else {
		$new_profile_string = str_replace('</profile>', '<' . $parameter_name . '>' . $parameter_value . '</' . $parameter_name . '>
</profile>', $profile_string);
	}
	$profiles_contents = str_replace($profile_string, $new_profile_string, $profiles_contents);
	file_put_contents('profiles.xml', $profiles_contents);
}

function add_reputation($reputation_amount, $profilename, $profiles) {
	$profiles->add($reputation_amount, 'reputation', $profiles->_('.profile_name=' . $profiles->enc($profilename)));
	return $profiles;
}

function add_bounty_reward_to_profile_currency($reward, $profilename, $profiles) { // alias
	return add_to_profile_currency($reward, $profilename, $profiles);
}

function add_to_profile_currency($reward, $profilename, $profiles) {
	//print('$profiles in add_bounty_reward_to_profile_currency: ');var_dump($profiles);
	$profiles->add($reward, 'currency', $profiles->_('.profile_name=' . $profiles->enc($profilename)));
	return $profiles;
}

function add_bounty_reward_to_profile_unavailablecurrency($reward, $profilename, $profiles) { // alias
	$profiles->add($reward, 'unavailablecurrency', $profiles->_('.profile_name=' . $profiles->enc($profilename)));
	return $profiles;
}

function add_to_profile_unavailablecurrency($reward, $profilename, $profiles) {
	$profiles->add($reward, 'unavailablecurrency', $profiles->_('.profile_name=' . $profiles->enc($profilename)));
	return $profiles;
}

function new_profile($access_credentials, $profiles) {
	if($access_credentials['profile_id'] == false) {
		print('$access_credentials[\'profile_id\']: ');var_dump($access_credentials['profile_id']);
		fatal_error('id cannot be false in new_profile');
	}
	// also check for name collisions?
	if($access_credentials['profile_name'] == false) {
		print('$access_credentials[\'profile_name\']: ');var_dump($access_credentials['profile_name']);
		fatal_error('name cannot be false in new_profile');
	}
	if($access_credentials['password'] == false) {
		//$password_string = '';
		$password_string = '<password></password>
';
	} else {
		if(strpos($access_credentials['password'], '&') !== false || strpos($access_credentials['password'], '"') !== false || strpos($access_credentials['password'], "'") !== false || strpos($access_credentials['password'], '<') !== false || strpos($access_credentials['password'], '>') !== false) {
			add_warning_message('password cannot contain &amp; or &quot or &#039; or &lt; or &gt;.');
			return $profiles;
		} else {
			$password_string = '<password>' . password_hash($access_credentials['password'], PASSWORD_DEFAULT) . '</password>
';
		}
	}
	if($access_credentials['biometric'] != false) {
		print('$access_credentials[\'biometric\']: ');var_dump($access_credentials['biometric']);
		fatal_error('biometric not handled in in new_profile');
	}
	if($access_credentials['IP'] == false) {
		$IP_string = '';
	} else {
		$IP_string = '<IP logincount="1">' . $access_credentials['IP'] . '</IP>
';
	}
	$new_profile_string = '<profile>
<id>' . xml_enc($access_credentials['profile_id']) . '</id>
<name>' . xml_enc($access_credentials['profile_name']) . '</name>
<page></page>
<currency>0</currency>
<reputation>0</reputation>
<starttime>' . time() . '</starttime>
<lasttime>' . time() . '</lasttime>
' . $password_string . $IP_string . '<settings>100000010000000</settings>
</profile>
'; // every profile starts with 10000 units; a number chosen to balance divisibility with ability to intuitively grasp portions therein
	$profiles->new_($new_profile_string, 'profiles');
	return $profiles;
}

function get_profile_score($access_credentials, $profiles) {
	if(!isset($access_credentials['profile_name']) || $access_credentials['profile_name'] === false || $access_credentials['profile_name'] === NULL) {
		print('$access_credentials[\'profile_name\']: ');var_dump($access_credentials['profile_name']);
		fatal_error('get_profile_score requires profilename');
	}
	//print('$access_credentials in get_profile_sccore: ');var_dump($access_credentials);
	$total_weighting = 0; // seems redundant
	
	$profile_activity_score = get_profile_activity_score(xml_enc($access_credentials['profile_name']), $profiles);
	$profile_activity_weighting = 0.3;
	$total_weighting += $profile_activity_weighting;
	
	$profile_lifetime_score = get_profile_lifetime_score(xml_enc($access_credentials['profile_name']), $profiles);
	$profile_lifetime_weighting = 0.1;
	$total_weighting += $profile_lifetime_weighting;
	
	$profile_password = get_password(xml_enc($access_credentials['profile_name']), $profiles);
	//print('$profile_password, $access_credentials[\'password\']: ');var_dump($profile_password, $access_credentials['password']);
	if($profile_password !== false && password_verify($access_credentials['password'], $profile_password)) {
		$profile_password_score = 1;
	} else {
		$profile_password_score = 0;
	}
	$profile_password_weighting = 0.5;
	$total_weighting += $profile_password_weighting;
	
	$profile_biometric = get_biometric(xml_enc($access_credentials['profile_name']), $profiles);
	//print('$profile_biometric, $access_credentials[\'biometric\']: ');var_dump($profile_biometric, $access_credentials['biometric']);
	if($profile_biometric !== false && $profile_biometric === $access_credentials['biometric']) {
		$profile_biometric_score = 1;
	} else {
		$profile_biometric_score = 0;
	}
	$profile_biometric_weighting = 0.9;
	$total_weighting += $profile_biometric_weighting;
	
	$profile_IP_score = get_profile_IP_score($access_credentials, $profiles);
	$profile_IP_weighting = 0.3;
	$total_weighting += $profile_IP_weighting;
	
	//$profile_score = $profile_activity_score * $profile_activity_weighting + $profile_lifetime_score * $profile_lifetime_weighting + $profile_password_score * $profile_password_weighting + $profile_biometric_score * $profile_biometric_weighting + $profile_IP_score * $profile_IP_weighting;
	// roughly, this multiplies factors that take time to work on by ones that take no work
	$profile_score = (($profile_activity_score * $profile_activity_weighting) + ($profile_lifetime_score * $profile_lifetime_weighting)) * (1 + ($profile_password_score * $profile_password_weighting) + ($profile_biometric_score * $profile_biometric_weighting) + ($profile_IP_score * $profile_IP_weighting));
	//print('$profile_score, $profile_activity_score, $profile_lifetime_score, $profile_password_score, $profile_biometric_score, $profile_IP_score: ');var_dump($profile_score, $profile_activity_score, $profile_lifetime_score, $profile_password_score, $profile_biometric_score, $profile_IP_score);exit(0);
	if($profile_score > 1) {
		$profile_score = 1;
	}
	if($profile_score < 0) {
		$profile_score = 0;
	}
	return $profile_score;
}

function get_profile_fully_logged_in_score($profilename, $profiles) {
	$profile_activity_score = get_profile_activity_score($profilename, $profiles);
	$profile_activity_weighting = 0.3;
	
	$profile_lifetime_score = get_profile_lifetime_score($profilename, $profiles);
	$profile_lifetime_weighting = 0.1;
	
	if(profile_has_a_password($profilename, $profiles)) {
		$profile_password_score = 1;
	} else {
		$profile_password_score = 0;
	}
	$profile_password_weighting = 0.5;
	
	if(profile_has_a_biometric($profilename, $profiles)) {
		$profile_biometric_score = 1;
	} else {
		$profile_biometric_score = 0;
	}
	$profile_biometric_weighting = 0.9;
	
	if(profile_has_a_IP($profilename, $profiles)) {
		$profile_IP_score = 1;
	} else {
		$profile_IP_score = 0;
	}
	$profile_IP_weighting = 0.9;
	
	// roughly, this multiplies factors that take time to work on by ones that take no work
	$profile_score = (($profile_activity_score * $profile_activity_weighting) + ($profile_lifetime_score * $profile_lifetime_weighting)) * (1 + ($profile_password_score * $profile_password_weighting) + ($profile_biometric_score * $profile_biometric_weighting) + ($profile_IP_score * $profile_IP_weighting));
	if($profile_score > 1) {
		$profile_score = 1;
	}
	if($profile_score < 0) {
		$profile_score = 0;
	}
	return $profile_score;
}

function get_profile_IP_score($access_credentials, $profiles) {
	//print('$access_credentials: ');var_dump($access_credentials);
	$profile = $profiles->_('.profile_name=' . $profiles->enc($access_credentials['profile_name']));
	$profile_IPs = $profiles->_('IP', $profile);
	if(!is_array($profile_IPs)) {
		if($profile_IPs === $access_credentials['IP']) {
			return 1;
		} else {
			return 0;
		}
	}
	//print('$profile_IPs: ');var_dump($profile_IPs);
	$loggedincount = 0;
	$logincount_sum = 0;
	foreach($profile_IPs as $profile_IP_index => $profile_IP_value) {
		if($profile_IP_value === $access_credentials['IP']) {
			$loggedincount = $profiles->get_attribute('logincount', $profile_IP_index - 1);;
		}
		$logincount_sum += $profiles->get_attribute('logincount', $profile_IP_index - 1);
	}
	//print('$loggedincount, $logincount_sum, $access_credentials[\'IP\']: ');var_dump($loggedincount, $logincount_sum, $access_credentials['IP']);
	if($access_credentials['IP'] === false || $logincount_sum === 0) {
		$profile_IP_score = 0;
	} else {
		$profile_IP_score = $loggedincount / $logincount_sum;
	}
	//print('$profile_IP_score: ');var_dump($profile_IP_score);exit(0);
	return $profile_IP_score;
}

function get_profile_activity_score($profilename, $profiles, $force_update = false) {
	$profile_activity_score = get_profile_reputation($profilename, $profiles, $force_update) / 100;
	//print('$profile_activity_score: ');var_dump($profile_activity_score);
	if($profile_activity_score > 1) {
		$profile_activity_score = 1;
	}
	return $profile_activity_score;
}

function get_profile_lifetime_score($profilename, $profiles) {
	//print('get_profile_starttime($profilename, $profiles): ');var_dump(get_profile_starttime($profilename, $profiles));
	$profile_starttime = get_profile_starttime($profilename, $profiles);
	if($profile_starttime === null || $profile_starttime === false) {
		$profile_lifetime_score = 0;
	} else {
		$profile_lifetime_score = (time() - $profile_starttime) / (365 * 86400);
	}
	//print('$profile_lifetime_score: ');var_dump($profile_lifetime_score);
	if($profile_lifetime_score > 1) {
		$profile_lifetime_score = 1;
	}
	return $profile_lifetime_score;
}

function get_by_request($variable) {
	if($_REQUEST[$variable] == '') {
		//warning($variable . ' not properly specified.<br>');
		return false;
	} else {
		$variable = query_decode($_REQUEST[$variable]);
	}
	return $variable;
}

function query_encode($string) {
	$string = str_replace('&', '%26', $string);
	return $string;
}

function query_decode($string) {
	$string = str_replace('%26', '&', $string);
	return $string;
}

function preg_escape($string) {
	return str_replace('/', '\/', preg_quote($string));
}

function preg_escape_replacement($string) {
	$string = str_replace('$', '\$', $string);
	$string = str_replace('{', '\{', $string);
	$string = str_replace('}', '\}', $string);
	return $string;
}

function fatal_error($message) { 
	print('<span class="fatal_error">' . $message . '</span>');exit(0);
}

function warning($message) { 
	print('<span class="warning">' . $message . '</span><br>');
}

function good_news($message) { 
	print('<span class="good_news">' . $message . '</span><br>');
}

function bad_news($message) { 
	print('<span class="bad_news">' . $message . '</span><br>');
}

function fatal_error_once($string) {
	if(!isset($printed_strings[$string])) {
		print('<span class="fatal_error">' . $string . '</span>');exit(0);
		$printed_strings[$string] = true;
	}
	return true;
}

function warning_if($string, $count) {
	if($count > 1) {
		warning($string);
	}
}

function warning_once($string) {
	if(!isset($printed_strings[$string])) {
		print('<span class="warning">' . $string . '</span><br>');
		$printed_strings[$string] = true;
	}
	return true;
}

function good_news_once($string) {
	if(!isset($printed_strings[$string])) {
		print('<span class="good_news">' . $string . '</span><br>');
		$printed_strings[$string] = true;
	}
	return true;
}

function add_message($message) { 
	//global $messages;
	$messages[] = $message . '<br>';
}

function add_warning_message($message) { 
	//global $messages;
	$messages[] = '<span class="warning">' . $message . '</span><br>';
}

function date_from_time($time) { // alias
	return formatted_date_from_time($time);
}

function formatted_date_from_time($time) {
	$format = 'Y/m/d H:i:s'; // 24-hour format
	//$format = 'Y/m/d h:i:s'; // no leading zero on the hour
	//$format = 'Y/m/d G:i:s'; // 24-hour format without leading zeroes
	return date($format, $time);
}

function time_from_formatted_date($new_bounty_endtimeformatted) {
	$year = substr($new_bounty_endtimeformatted, 0, 4);
	$month = substr($new_bounty_endtimeformatted, 5, 2);
	$day = substr($new_bounty_endtimeformatted, 8, 2);
	$hour = substr($new_bounty_endtimeformatted, 11, 2);
	$minute = substr($new_bounty_endtimeformatted, 14, 2);
	$second = substr($new_bounty_endtimeformatted, 17, 2);
	//print('$year, $month, $day, $hour, $minute, $second: ');var_dump($year, $month, $day, $hour, $minute, $second);
	//warning_once('years and months are helpful for human organization of time but quite bad for computer tracking of time... pretty much guaranteed the formula the computer uses that takes months and years in will be incorrect');
	$month_seconds = 0;
	// 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31
	if($month > 1) { // january
		$month_seconds += 31 * 86400;
	}
	if($month > 2) { // february
		if($year % 4 == 0) { // leap year
			$month_seconds += 29 * 86400;
		} else {
			$month_seconds += 28 * 86400;
		}
	}
	if($month > 3) { // march
		$month_seconds += 31 * 86400;
	}
	if($month > 4) { // april
		$month_seconds += 30 * 86400;
	}
	if($month > 5) { // may
		$month_seconds += 31 * 86400;
	}
	if($month > 6) { // june
		$month_seconds += 30 * 86400;
	}
	if($month > 7) { // july
		$month_seconds += 31 * 86400;
	}
	if($month > 8) { // august
		$month_seconds += 31 * 86400;
	}
	if($month > 9) { // september
		$month_seconds += 30 * 86400;
	}
	if($month > 10) { // october
		$month_seconds += 31 * 86400;
	}
	if($month > 11) { // november
		$month_seconds += 30 * 86400;
	}
	//if($month > 12) { // december
	//	$month_seconds += 31 * 86400;
	//}
	$new_bounty_endtime = (($year - 1970) * 365.25 * 86400) + $month_seconds + (($day - 0.54220) * 86400) + (($hour - 1) * 3600) + ($minute * 60) + $second; // why day - 0.5? why day - 0.54220? I have no idea
	//$new_bounty_endtime = strptime($format, (int)$new_bounty_endtimeformatted);
	//$new_bounty_endtime = mktime($new_bounty_endtimeformatted);
	//print('$new_bounty_endtimeformatted, $new_bounty_endtime: ');var_dump($new_bounty_endtimeformatted, $new_bounty_endtime);
	//print('$new_bounty_endtime - time(): ');var_dump($new_bounty_endtime - time());
	//print('date(\'Y/m/d H:i:s\', $new_bounty_endtime): ');var_dump(date('Y/m/d H:i:s', $new_bounty_endtime));
	$new_bounty_endtime = round($new_bounty_endtime);
	return $new_bounty_endtime;
}

function htmlize_newline_characters($string) {
	$string = str_replace('
', '<br>', $string);
	return $string;
}

function format_for_printing($string, $access_credentials, $issuer_profilename, $completer_profilename, $profiles) {
	//print('$string in format_for_printing: ');var_dump($string);
	//if(!is_string($string)) {
	//	print('$string: ');var_dump($string);
	//	fatal_error('!is_string($string) in format_for_printing');
	//}
	//$string = xml_dec($string);
	$string = htmlize_newline_characters($string);
//	$string = process_private_content($string, $access_credentials, $issuer_profilename, $completer_profilename, $profiles); // takes a LONG time!
	return $string;
}

function process_private_content($string, $access_credentials, $issuer_profilename, $completer_profilename, $profiles) {
	preg_match_all('/&lt;private&gt;(.*?)&lt;\/private&gt;/is', $string, $private_matches);
	$profile_password = get_password(xml_enc($access_credentials['profile_name']), $profiles);
	//print('$string, $access_credentials[\'profile_name\'], $issuer_profilename, $completer_profilename, $profile_password: ');var_dump($string, $access_credentials['profile_name'], $issuer_profilename, $completer_profilename, $profile_password);
	if(($access_credentials['profile_name'] === $issuer_profilename || $access_credentials['profile_name'] === $completer_profilename) && $profile_password !== false && password_verify($access_credentials['password'], $profile_password)) {
		//print('can see<br>');
	} else {
		//print('can\'t see<br>');
		foreach($private_matches[0] as $index => $value) {
			$content = $private_matches[1][$index];
			$content = preg_replace('/[^ ]/is', '&nbsp;', $content);
			$new_private = '<span class="private">' . $content . '</span>';
			$string = str_replace($value, $new_private, $string);
		}
	}
	return $string;
}

function completion_details_to_string($bounties, $bounty_you_issued, $access_credentials, $issuer_profilename, $profiles) {
	$completiondetails = $bounties->_('completiondetails', $bounty_you_issued);
	//print('$bounties->context[sizeof($bounties->context) - 1][2]: ');var_dump($bounties->context[sizeof($bounties->context) - 1][2]);
	//print('$completiondetails1: ');var_dump($completiondetails);
	$completiondetails_string = '';
	if($completiondetails === false) {
		
	} else {
		if(is_string($completiondetails)) {
			$completiondetails = array($completiondetails);
		}
		foreach($completiondetails as $index => $completiondetail) {
			$completer = $bounties->_('name', $completiondetail);
			//print('$completer1: ');var_dump($completer);
			if(strlen($completer) > 0) {
				$completiondetails_string .= '<div><em>' . $completer . '</em></div>
<div class="monospace">' . format_for_printing($bounties->_('comment', $completiondetail), $access_credentials, $issuer_profilename, $completer, $profiles) . '</div>
';
			}
		}
	}
	return array($bounties, $completiondetails_string);
}

function get_existing_comment($bounties, $bounty_you_issued, $profilename) {
	$completiondetails = $bounties->_('completiondetails', $bounty_you_issued);
	if($completiondetails === false) {
		
	} else {
		if(is_string($completiondetails)) {
			$completiondetails = array($completiondetails);
		}
		foreach($completiondetails as $index => $completiondetail) {
			$completer = $bounties->_('name', $completiondetail);
			//print('$completer1: ');var_dump($completer);
			if($completer === $profilename) {
				return $bounties->_('comment', $completiondetail);
			}
		}
	}
	return false;
}

function bounty_reported_string($bounties, $bounty_you_issued) {
	$reported = $bounties->_('reported', $bounty_you_issued);
	$reportingreason_string = '';
	if($reported === false) {
		
	} else {
		if(is_string($reported)) {
			$reported = array($reported);
		}
		foreach($reported as $index => $report) {
			$reporter = $bounties->_('reporter', $report);
			if(strlen($reporter) > 0) {
				$reportingreason_string .= '<div><em>' . $reporter . '</em></div>
<div class="monospace">' . htmlize_newline_characters($bounties->_('reason', $report)) . '</div>
';
			}
		}
	}
	return array($bounties, $reportingreason_string);
}

function auction_reported_string($auctions, $auction_you_issued) {
	$reported = $auctions->_('reported', $auction_you_issued);
	$reportingreason_string = '';
	if($reported === false) {
		
	} else {
		if(is_string($reported)) {
			$reported = array($reported);
		}
		foreach($reported as $index => $report) {
			$reporter = $auctions->_('reporter', $report);
			if(strlen($reporter) > 0) {
				$reportingreason_string .= '<div><em>' . $reporter . '</em></div>
<div class="monospace">' . format_for_printing($auctions->_('reason', $report)) . '</div>
';
			}
		}
	}
	return array($auctions, $reportingreason_string);
}

function hidden_form_inputs($access_credentials, $tab_settings, $request) {
	//print('$request: ');var_dump($request);
	// $request seems to contain what $access_credentials, $tab_settings contain; which begs the question of why these are treated separately
	$hidden_inputs_string = '';
	foreach($request as $index => $value) {
		if(!isset($access_credentials[$index])) {
			$hidden_inputs_string .= '<input type="hidden" name="' . $index . '" value="' . $value . '" />
';
		}
	}
	foreach($access_credentials as $index => $value) {
		//if($index === 'profilename') {
		//if($index === 'name') {
		if($index === 'profile_name') {
			$value = xml_enc($value);
		}
		$hidden_inputs_string .= '<input type="hidden" name="' . $index . '" value="' . $value . '" />
';
	}
	$hidden_inputs_string .= '<input type="hidden" name="tab_settings" class="tab_settings" value="' . $tab_settings . '" />
';
	//print('$hidden_inputs_string: ');var_dump($hidden_inputs_string);
	return $hidden_inputs_string;
}

function accept_as_complete_actions_string($id, $access_credentials, $bounties, $bounty_you_issued, $tab_settings, $request) {
	$actions_string = '';
	$completiondetails = $bounties->_('completiondetails', $bounty_you_issued);
	//print('$bounties->context[sizeof($bounties->context) - 1][2]: ');var_dump($bounties->context[sizeof($bounties->context) - 1][2]);
	//print('$completiondetails2: ');var_dump($completiondetails);
	if($completiondetails === false) {
		
	} else {
		if(time() - $bounties->_('starttime', $bounty_you_issued) < 1200) { // allow 20 minutes to prevent abuse. seems we're off by some minutes somewhere
			
		} else {
			if(is_string($completiondetails)) {
				$completiondetails = array($completiondetails);
			}
			foreach($completiondetails as $index => $completiondetail) {
				$completer = $bounties->_('name', $completiondetail);
				//print('$completer2: ');var_dump($completer);
				$actions_string .= '<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="completed_bounty_id" value="' . $id . '" />
<input type="hidden" name="completer" value="' . $completer . '" />
<input class="good_button" type="submit" value="accept as complete by ' . $completer . '" />
</form>
';
			}
		}
	}
	return array($bounties, $actions_string);
}

function bounties_you_accepted_actions_string($id, $access_credentials, $tab_settings, $request, $existing_comment, $issuer_profilename, $completer_profilename, $profiles) {
	// <textarea name="completion_details" rows="2" cols="50"></textarea>
	$actions_string = '<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="offer_bounty_as_complete_id" value="' . $id . '" />
<!--small>Text in a &lt;private&gt; tag will only be visible to the bounty issuer and yourself.</small-->
';
if($existing_comment === false) {
	$actions_string .= '<textarea name="completion_details" placeholder="completing details"></textarea><br>
';
} else {
	$actions_string .= '<textarea name="completion_details">' . process_private_content($existing_comment, $access_credentials, $issuer_profilename, $completer_profilename, $profiles) . '</textarea><br>
';
}
	$actions_string .= '<input class="good_button" type="submit" value="offer bounty as complete" />
</form>
';
	return $actions_string;
}

function available_bounties_actions_string($id, $access_credentials, $tab_settings, $request, $profiles) {
	$actions_string = '<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="accept_bounty_id" value="' . $id . '" />
<input class="button" type="submit" value="accept" />
</form>
';
	if(xml_enc(get_reputation($access_credentials['profile_name'], $profiles)) > -1) {
		$actions_string .= '<details>
<summary>report bounty</summary>
<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="report_bounty_id" value="' . $id . '" />
<textarea name="report_bounty_reason" rows="2" cols="20" placeholder="reporting reason"></textarea>
<input class="warning_button" type="submit" value="report bounty" />
</form>
</details>
';
	}
	return $actions_string;
}

function available_auctions_actions_string($id, $access_credentials, $minimum_next_bid, $buyout, $tab_settings, $request, $auctions, $profiles) {
	$actions_string = '<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="auction_id" value="' . $id . '" />
<input type="text" name="auction_bid" value="' . $minimum_next_bid . '" />
<input class="button" type="submit" value="bid" />
</form>
';
	if(is_numeric($buyout) && time() - $auctions->_('starttime', $auctions->_('.auction_id=' . $id)) < 1200) { // allow 20 minutes to prevent abuse. seems we're off by some minutes somewhere
		$actions_string .= '<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="auction_id" value="' . $id . '" />
<input type="hidden" name="auction_buyout" value="' . $buyout . '" />
<input class="button" type="submit" value="buyout" />
</form>
';
	}
	if(xml_enc(get_reputation($access_credentials['profile_name'], $profiles)) > -1) {
		$actions_string .= '<details>
<summary>report auction</summary>
<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="report_auction_id" value="' . $id . '" />
<textarea name="report_auction_reason" rows="2" cols="20" placeholder="reporting reason"></textarea>
<input class="warning_button" type="submit" value="report auction" />
</form>
</details>
';
	}
	return $actions_string;
}

function reported_auction_actions_string($id, $access_credentials, $tab_settings, $request) {
	return '<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="reported_auction_id" value="' . $id . '" />
<input type="hidden" name="reported_auction_vote" value="agree" />
<input class="button" type="submit" value="agree" />
</form>
<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="reported_auction_id" value="' . $id . '" />
<input type="hidden" name="reported_auction_vote" value="disagree" />
<input class="button" type="submit" value="disagree" />
</form>
';
}

function reported_bounty_actions_string($id, $access_credentials, $tab_settings, $request) {
	return '<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="reported_bounty_id" value="' . $id . '" />
<input type="hidden" name="reported_bounty_vote" value="agree" />
<input class="button" type="submit" value="agree" />
</form>
<form action="access.php" method="post">
' . hidden_form_inputs($access_credentials, $tab_settings, $request) . '
<input type="hidden" name="reported_bounty_id" value="' . $id . '" />
<input type="hidden" name="reported_bounty_vote" value="disagree" />
<input class="button" type="submit" value="disagree" />
</form>
';
}

function accepted_string($bounties, $bounty_you_issued) {
	//warning_once('problem to pass the bounties object unless it\'s by reference or something?');
	$acceptednames = $bounties->_('acceptedname', $bounty_you_issued);
	$accepted_string = '<div class="accepted_list">
<ul>
';
	if($acceptednames) {
		if(!is_array($acceptednames)) {
			$acceptednames = array($acceptednames);
		}
		//print('$acceptednames: ');var_dump($acceptednames);
		foreach($acceptednames as $acceptedname) {
			$accepted_string .= '<li>' . $acceptedname . '</li>
';
		}
	}
	$accepted_string .= '</ul>
</div>
';
	return array($bounties, $accepted_string);
}

function guid($string) {
	include_once('feed_generator' . DS . 'FeedWriter.php');
	$TestFeed = new FeedWriter(ATOM);
	return $TestFeed->uuid($string, '');
}

function countdown_timer_script($id, $timeleft, $prefix = '') {
	if($timers[$prefix . 'timer' . $id] === true) {
		return '';
	}
	$timers[$prefix . 'timer' . $id] = true;
	return '<script type="text/javascript">
$(document).ready(function(){
var ' . $prefix . 'count' . $id . ' = ' . $timeleft . ';
var ' . $prefix . 'counter' . $id . ' = setInterval(' . $prefix . 'timer' . $id . ', 1000); // 1000 will run it every 1 second
// days + "d" + hours + "h" + minutes + "m" + seconds + "s"
var days = Math.floor(' . $prefix . 'count' . $id . ' / 86400);
var hours = (Math.floor(' . $prefix . 'count' . $id . ' / 3600) % 24);
if(days > 0) {
	if(hours < 10) {
		hours = "0" + hours;
	}
	var countdown_string = days + "d" + hours + "h";
} else {
	var minutes = (Math.floor(' . $prefix . 'count' . $id . ' / 60) % 60);
	if(hours > 0) {
		if(minutes < 10) {
			minutes = "0" + minutes;
		}
		var countdown_string = hours + "h" + minutes + "m";
	} else {
		var seconds = ' . $prefix . 'count' . $id . ' % 60;
		if(minutes > 0) {
			if(seconds < 10) {
				seconds = "0" + seconds;
			}
			var countdown_string = minutes + "m" + seconds + "s";
		} else {
			var countdown_string = seconds + "s";
		}
	}
}
//document.getElementById("' . $prefix . 'timer' . $id . '").innerHTML = countdown_string;
var x = document.getElementsByClassName("' . $prefix . 'timer' . $id . '");
var i;
for (i = 0; i < x.length; i++) {
	x[i].innerHTML = countdown_string;
} 
//$(".' . $prefix . 'timer' . $id . '").text = countdown_string;
function ' . $prefix . 'timer' . $id . '() {
	' . $prefix . 'count' . $id . ' = ' . $prefix . 'count' . $id . ' - 1;
	if (' . $prefix . 'count' . $id . ' <= 0) {
		clearInterval(' . $prefix . 'counter' . $id . ');
		//counter ended, do something here
		//document.getElementById("' . $prefix . 'timer' . $id . '").innerHTML = "ended";
		var x = document.getElementsByClassName("' . $prefix . 'timer' . $id . '");
		var i;
		for (i = 0; i < x.length; i++) {
			x[i].innerHTML = "ended";
		} 
		//$(".' . $prefix . 'timer' . $id . '").text = "ended";
		return;
	}
	//Do code for showing the number of seconds here
	var days = Math.floor(' . $prefix . 'count' . $id . ' / 86400);
	var hours = (Math.floor(' . $prefix . 'count' . $id . ' / 3600) % 24);
	if(days > 0) {
		if(hours < 10) {
			hours = "0" + hours;
		}
		var countdown_string = days + "d" + hours + "h";
	} else {
		var minutes = (Math.floor(' . $prefix . 'count' . $id . ' / 60) % 60);
		if(hours > 0) {
			if(minutes < 10) {
				minutes = "0" + minutes;
			}
			var countdown_string = hours + "h" + minutes + "m";
		} else {
			var seconds = ' . $prefix . 'count' . $id . ' % 60;
			if(minutes > 0) {
				if(seconds < 10) {
					seconds = "0" + seconds;
				}
				var countdown_string = minutes + "m" + seconds + "s";
			} else {
				var countdown_string = seconds + "s";
			}
		}
	}
	//document.getElementById("' . $prefix . 'timer' . $id . '").innerHTML = countdown_string;
	var x = document.getElementsByClassName("' . $prefix . 'timer' . $id . '");
	var i;
	for (i = 0; i < x.length; i++) {
		x[i].innerHTML = countdown_string;
	} 
	//$(".' . $prefix . 'timer' . $id . '").text = countdown_string;
}
});
</script>';
}

function clean_up($auctions) {
	// move expired bounties and reimburse bounty issuer
	
	// once the time on an auction runs out we have to call the bidder with the highest bid the winner
	$auctions = process_ended_auctions($profiles, $auctions, $completedauctions, $expiredauctions);
	// move expired auctions and give the auction issuer the winning bid amount and (do what to give the content of the auction to auction winner) and reimburse bids that didn't win
	
	// reports...
	
}

function clean_bounties() {
	// need a function to clean bounties that expire before being completed
	// ability to extend bounties
}

function process_ended_auctions($profiles, $auctions, $completedauctions, $expiredauctions) {
	$all_auctions = $auctions->_('auctions_auction');
	if(is_array($all_auctions) && sizeof($all_auctions) > 0) {
		// have to go in reverse order
		$counter = sizeof($all_auctions) - 1;
		//foreach($all_auctions as $auction) {
		while($counter > -1) {
			$auction = $all_auctions[$counter];
			if(time() > ($auctions->_('endtime', $auction) + 604800) && $auctions->_('acceptedbid', $auction) !== false) { // show completed auctions for a week
				$completedauctions->new_($auction, 'completedauctions');
				$auctions->delete($auction);
			} elseif(time() > $auctions->_('endtime', $auction) && $auctions->_('acceptedbid', $auction) === false) {
				$bids = $auctions->_('bids_bid', $auction);
				$auction_id = $auctions->_('id', $auction);
				$last_profile_bid_amount = 0;
				if(is_array($bids) && sizeof($bids) > 0) {
					foreach($bids as $last_bid_index => $last_bid) {  }
					$last_bid_amount = $auctions->_('amount', $last_bid);
					$last_bid_profilename = $auctions->_('bidder', $last_bid);
					$auctions->__('endtime', time(), $auctions->_('.auction_id=' . $auction_id));
					$auctions->new_('<acceptedbid><bidder>' . $last_bid_profilename . '</bidder><amount>' . $last_bid_amount . '</amount></acceptedbid>
', $auctions->_('.auction_id=' . $auction_id));
					$auction_offerer = $auctions->_('profilename', $auctions->_('.auction_id=' . $auction_id));
					if($last_bid_profilename !== $auction_offerer) {
						$profiles = add_reputation(1, $auction_offerer, $profiles);
						$profiles = add_reputation(1, $last_bid_profilename, $profiles);
					}
					$reward_based_on_profile_score = round(get_profile_fully_logged_in_score($auction_offerer, $profiles) * $auction_bid, 10); // is it a problem that a bounty could be completed here when an profile is not fully logged into? why precision of 10?
					//print('$auction_id, $auction_offerer, $last_bid_profilename, $auction_bid, $reward_based_on_profile_score: ');var_dump($auction_id, $auction_offerer, $last_bid_profilename, $auction_bid, $reward_based_on_profile_score);exit(0);
					$profiles = add_to_profile_currency($reward_based_on_profile_score, $auction_offerer, $profiles);
					$profiles = add_to_profile_unavailablecurrency($auction_bid - $reward_based_on_profile_score, $auction_offerer, $profiles);
					//$completedauctions->new_($auction, 'completedauctions');
					//$auctions->delete($auction);
				} else {
					// dummy value since LOM would return false when looking for the acceptedbid if there were no characters in the tag
					$auctions->new_('<acceptedbid>-1</acceptedbid>
', $auctions->_('.auction_id=' . $auction_id));
					$expiredauctions->new_($auctions->_('.auction_id=' . $auction_id), 'expiredauctions');
					$auctions->delete($auctions->_('.auction_id=' . $auction_id));
				}
			}
			$counter--;
		}
	}
	return array($profiles, $auctions, $completedauctions, $expiredauctions);
}

function process_ended_bounties($profiles, $bounties, $completedbounties, $expiredbounties) {
	$all_bounties = $bounties->_('bounties_bounty');
	if(is_array($all_bounties) && sizeof($all_bounties) > 0) {
		// have to go in reverse order
		$counter = sizeof($all_bounties) - 1;
		//foreach($all_bounties as $bounty) {
		while($counter > -1) {
			$bounty = $all_bounties[$counter];
			if(time() > ($bounties->_('endtime', $bounty) + 604800) && $bounties->_('acceptedcompleter', $bounty) !== false) { // show completed bounties for a week
				$completedbounties->new_($bounty, 'completedbounties');
				$bounties->delete($bounty);
			} elseif(time() > $bounties->_('endtime', $bounty) && $bounties->_('acceptedcompleter', $bounty) === false) {
				$bounty_id = $bounties->_('id', $bounty);
				//print('expired bounty: ');$bounties->var_dump_full($bounty);
				$bounty_offerer = $bounties->_('profilename', $bounties->_('.bounty_id=' . $bounty_id));
				$reward_based_on_profile_score = round(get_profile_fully_logged_in_score($bounty_offerer, $profiles) * $bounties->_('reward', $bounty), 10); // is it a problem that a bounty could be completed here when an profile is not fully logged into? why precision of 10?
				//print('$bounty_id, $bounty_offerer, $last_bid_profilename, $bounty_bid, $reward_based_on_profile_score: ');var_dump($bounty_id, $bounty_offerer, $last_bid_profilename, $bounty_bid, $reward_based_on_profile_score);exit(0);
				$profiles = add_to_profile_currency($reward_based_on_profile_score, $bounty_offerer, $profiles);
				$profiles = add_to_profile_unavailablecurrency($bounties->_('reward', $bounties->_('.bounty_id=' . $bounty_id)) - $reward_based_on_profile_score, $bounty_offerer, $profiles);
				$expiredbounties->new_($bounties->_('.bounty_id=' . $bounty_id), 'expiredbounties');
				$bounties->delete($bounties->_('.bounty_id=' . $bounty_id));
				//$bounties->generate_LOM($bounties->to_string($bounties->LOM));
				//print('$bounties->LOM after deleting: ');$bounties->var_dump_full($bounties->LOM);
				//break;
			}
			$counter--;
		}
	}
	return array($profiles, $bounties, $completedbounties, $expiredbounties);
}

function complete_ended_reports($profiles, $auctions, $bounties) {
	$all_auctions = $auctions->_('auctions_auction');
	if(is_array($all_auctions) && sizeof($all_auctions) > 0) {
		// have to go in reverse order
		$counter = sizeof($all_auctions) - 1;
		//foreach($all_auctions as $auction) {
		while($counter > -1) {
			$auction = $all_auctions[$counter];
			if($auctions->_('reported', $auction) !== false && time() > $auctions->_('reportedtime', $auction) + 86400) {
				$results_array = complete_reported_auction($auction, $profiles, $auctions);
				$profiles = $results_array[0];
				$auctions = $results_array[1];
			}
			$counter--;
		}
	}
	$all_bounties = $bounties->_('bounties_bounty');
	if(is_array($all_bounties) && sizeof($all_bounties) > 0) {
		// have to go in reverse order
		$counter = sizeof($all_bounties) - 1;
		//foreach($all_bounties as $bounty) {
		while($counter > -1) {
			$bounty = $all_bounties[$counter];
			if($bounties->_('reported', $bounty) !== false && time() > $bounties->_('reportedtime', $bounty) + 86400) {
				$results_array = complete_reported_bounty($bounty, $profiles, $bounties);
				$profiles = $results_array[0];
				$bounties = $results_array[1];
			}
			$counter--;
		}
	}
	return array($profiles, $auctions, $bounties);
}

function complete_reported_auction($auction, $profiles, $auctions) {
	$agree_count = 1;
	$disagree_count = 0;
	$votes = $auctions->_('reported_vote', $auction);
	//print('$auction, $votes: ');var_dump($auction, $votes);
	if(is_array($votes) && sizeof($votes) > 0) {
		foreach($votes as $vote) {
			if($auctions->_('choice', $vote) === 'agree') {
				$agree_count++;
			} else {
				$disagree_count++;
			}
		}
	}
	if($agree_count > $disagree_count) {
		$profiles = add_reputation(2, $auctions->_('reporter', $auction), $profiles);
		if(is_array($votes) && sizeof($votes) > 0) {
			foreach($votes as $vote) {
				if($auctions->_('choice', $vote) === 'agree') {
					$profiles = add_reputation(2, $auctions->_('voter', $vote), $profiles);
				}
			}
		}
		$profiles = add_reputation(-4, $auctions->_('profilename', $auction), $profiles);
		$bids = $auctions->_('bids_bid', $auction);
		$highest_bids = array();
		if(is_array($bids) && sizeof($bids) > 0) {
			foreach($bids as $bid) {
				if(!isset($highest_bids[$auctions->_('bidder', $bid)]) || $auctions->_('amount', $bid) > $highest_bids[$auctions->_('bidder', $bid)]) {
					$highest_bids[$auctions->_('bidder', $bid)] = $auctions->_('amount', $bid);
				}
			}
		}
		foreach($highest_bids as $bidder => $amount) {
			$profiles->add($amount, 'currency', $profiles->_('profiles_.profile_name=' . $bidder));
		}
		$auctions->delete($auction);
	} elseif($agree_count < $disagree_count) {
		$auctions->delete('reported', $auction);
	} else { // it's a tie
		$profiles = add_reputation(1, $auctions->_('reporter', $auction), $profiles);
		if(is_array($votes) && sizeof($votes) > 0) {
			foreach($votes as $vote) {
				$profiles = add_reputation(1, $auctions->_('voter', $vote), $profiles);
			}
		}
		$auctions->delete('reported', $auction);
	}
	return array($profiles, $auctions);
}

function complete_reported_bounty($bounty, $profiles, $bounties) {
	$agree_count = 1;
	$disagree_count = 0;
	$votes = $bounties->_('reported_vote', $bounty);
	if(is_array($votes) && sizeof($votes) > 0) {
		foreach($votes as $vote) {
			if($bounties->_('choice', $vote) === 'agree') {
				$agree_count++;
			} else {
				$disagree_count++;
			}
		}
	}
	if($agree_count > $disagree_count) {
		$profiles = add_reputation(2, $bounties->_('reporter', $bounty), $profiles);
		if(is_array($votes) && sizeof($votes) > 0) {
			foreach($votes as $vote) {
				if($bounties->_('choice', $vote) === 'agree') {
					$profiles = add_reputation(2, $bounties->_('voter', $vote), $profiles);
				}
			}
		}
		$profiles = add_reputation(-4, $bounties->_('profilename', $bounty), $profiles);
		$profiles->add($bounties->_('reward', $bounty), 'currency', $profiles->_('profiles_.profile_name=' . $bounties->_('profilename', $bounty)));
		$bounties->delete($bounty);
	} elseif($agree_count < $disagree_count) {
		$bounties->delete('reported', $bounty);
	} else { // it's a tie
		$profiles = add_reputation(1, $bounties->_('reporter', $bounty), $profiles);
		if(is_array($votes) && sizeof($votes) > 0) {
			foreach($votes as $vote) {
				$profiles = add_reputation(1, $bounties->_('voter', $vote), $profiles);
			}
		}
		$bounties->delete('reported', $bounty);
	}
	return array($profiles, $bounties);
}

function filename_minus_extension($string) {
	return substr($string, 0, O::strpos_last($string, '.'));
}

function file_extension($string) {
	if(strpos($string, '.') === false || strpos_last($string, '.') < strpos_last($string, DS)) {
		return false;
	}
	return substr($string, strpos_last($string, '.'));
}

function strpos_last($haystack, $needle) {
	//print('$haystack, $needle: ');var_dump($haystack, $needle);
	if(strlen($needle) === 0) {
		return false;
	}
	$len_haystack = strlen($haystack);
	$len_needle = strlen($needle);		
	$pos = strpos(strrev($haystack), strrev($needle));
	if($pos === false) {
		return false;
	}
	return $len_haystack - $pos - $len_needle;
}

function delayed_print($string) {
	$delayed_print_string .= $string;
}

function xml_enc($string) {
	$string = htmlspecialchars($string, ENT_QUOTES);
	return $string;
}

function xml_dec($string) {
	$string = htmlspecialchars_decode($string, ENT_QUOTES);
	return $string;
}

function getmicrotime() {
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

function dump_total_time_taken() {
	$time_spent = getmicrotime() - $GLOBALS['OES_initial_time'];
	//$time_spent = getmicrotime() - $OES_initial_time;
	print('Total time spent: ' . $time_spent . ' seconds.<br>');
}

function var_dump_full() {
	$arguments_array = func_get_args();
	foreach($arguments_array as $index => $value) {
		$data_type = gettype($value);
		if($data_type == 'array') {
			$biggest_array_size = get_biggest_sizeof($value);
			if($biggest_array_size > 2000) {
				ini_set('xdebug.var_display_max_children', '2000');
			} elseif($biggest_array_size > ini_get('xdebug.var_display_max_children')) {
				ini_set('xdebug.var_display_max_children', $biggest_array_size);
			}
		} elseif($data_type == 'string') {
			$biggest_string_size = strlen($value);
			if($biggest_string_size > 10000) {
				ini_set('xdebug.var_display_max_data', '10000');
			} elseif($biggest_string_size > ini_get('xdebug.var_display_max_data')) {
				ini_set('xdebug.var_display_max_data', $biggest_string_size);
			}
		} elseif($data_type == 'integer' || $data_type == 'float' || $data_type == 'chr' || $data_type == 'boolean' || $data_type == 'NULL') {
			// these are already compact enough
		} else {
			warning('Unhandled data type in var_dump_full: ' . gettype($value));
		}
		var_dump($value);
	}
}

function get_biggest_sizeof($array, $biggest = 0) {
	if(sizeof($array) > $biggest) {
		$biggest = sizeof($array);
	}
	foreach($array as $index => $value) {
		if(is_array($value)) {
			$biggest = get_biggest_sizeof($value, $biggest);
		}
	}
	return $biggest;
}

?>