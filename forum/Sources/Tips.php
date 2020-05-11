<?php 

if (!defined('SMF'))
	die('Hacking attempt...');

function Tips()
{
	global $context, $smcFunc, $user_info, $txt;

	$context['page_title'] = $txt['tip_list_title'];
	loadPosts();
	loadTemplate('Tips');
}

function loadPosts()
{
	global $memberContext, $context, $smcFunc, $user_info;
	
	$tippedPosts = array();

	$query = "
		SELECT id_msg, id_message, msg.id_member as tippee_id, tip.id_member as tipper_id, body, id_topic, poster_name, poster_time, icon, subject, smileys_enabled, coins, item
		FROM {db_prefix}messages as msg
		INNER JOIN {db_prefix}message_tips as tip
		ON msg.id_msg = tip.id_message";

	// Searching for posts tipped by a specific user?
	if (!empty($_GET['tipper']) && !$context['user']['is_guest'])
	{
		// Find user id for searched user
		$userSearch = $smcFunc['db_query']('', "SELECT id_member from {db_prefix}members where real_name = '" . mysql_escape_string(urldecode($_GET['tipper'])) . "'");
		$result = $smcFunc['db_fetch_assoc']($userSearch)['id_member'];
		$query .= " WHERE tip.id_member = '" . $result . "'";
	}
	
	// Searching for posts made by a specific user?
	if (!empty($_GET['poster']) && !$context['user']['is_guest'])
	{
		if (!empty($_GET['tipper']))
		{
			$query .= " AND poster_name = '" . mysql_escape_string(urldecode($_GET['poster'])) . "'";
		}
		else
		{
			$query .= " WHERE poster_name = '" . mysql_escape_string(urldecode($_GET['poster'])) . "'";
		}
	}

	$query .= " ORDER BY id_message_tip DESC LIMIT 15";

	$tipsQuery = $smcFunc['db_query']('', $query);
	
	// Get post associated with each tip
	while($tip = $smcFunc['db_fetch_assoc']($tipsQuery))
	{
		// Load OP data into memberContext
		loadMemberData(array($tip['tippee_id']), false, 'minimal');
		loadMemberContext($tip['tippee_id']);

		// Load Tipper data into memberContext
		loadMemberData(array($tip['tipper_id']), false, 'minimal');
		loadMemberContext($tip['tipper_id']);
		
		// Store the name of the tipper with the tip.
		$tip['tipper'] = $memberContext[$tip['tipper_id']]['name'];

		if ($tip['item'] != 0)
		{
			$tip['item'] = dbGetIteminfo($tip['item']);
		}

		$tippedPosts[$tip['id_message']] =
		array(
			'poster' => $memberContext[$tip['tippee_id']],
			'post' => $tip,
			'tips' => array_merge_recursive( (array)$tippedPosts[$tip['id_msg']]['tips'], array($tip)),
		);
	}
	$context['recent_tipped_posts'] = $tippedPosts;
}
?>
