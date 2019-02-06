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
	
	// Find tips in descending order
	$query = "SELECT id_message_tip, id_message, id_member, coins FROM {db_prefix}message_tips";

	// Searching for posts tipped by a specific user?
	if (!empty($_GET['tipper']) && !$context['user']['is_guest'])
	{
		// Find user id for searched user
		$userSearch = $smcFunc['db_query']('', "SELECT id_member from smf_members where real_name = '" . mysql_escape_string(urldecode($_GET['tipper'])) . "'");
		$result = $smcFunc['db_fetch_assoc']($userSearch)['id_member'];
		$query .= " WHERE id_member = '" . $result . "'";
	}

	$query .= " ORDER BY id_message_tip DESC LIMIT 15";
	$tipsQuery = $smcFunc['db_query']('', $query);
	
	// Get post associated with each tip
	while($tip = $smcFunc['db_fetch_assoc']($tipsQuery))
	{
		$query = "
				SELECT id_msg, id_member, body, id_topic, poster_name, poster_time, icon, subject, smileys_enabled
				FROM {db_prefix}messages
				WHERE id_msg = {int:id_msg}";

		if (!empty($_GET['poster']) && !$context['user']['is_guest'])
		{
			$query .= " AND poster_name = '" . mysql_escape_string(urldecode($_GET['poster'])) . "'";
		}

		$postQuery = $smcFunc['db_query']('', $query, array('id_msg' => $tip['id_message']));
		$result = $smcFunc['db_fetch_assoc']($postQuery);

		if (!$result)
			continue;

		// Load OP data into memberContext
		loadMemberData(array($result['id_member']), false, 'minimal');
		loadMemberContext($result['id_member']);

		// Load Tipper data into memberContext
		loadMemberData(array($tip['id_member']), false, 'minimal');
		loadMemberContext($tip['id_member']);

		// Store the name of the tipper with the tip.
		$tip['tipper'] = $memberContext[$tip['id_member']]['name'];

		$tippedPosts[$tip['id_message']] =
		array(
			'poster' => $memberContext[$result['id_member']],
			'post' => $result,
			'tips' => array_merge_recursive( (array)$tippedPosts[$tip['id_message']]['tips'], array($tip)),
		);
	}
	$context['recent_tipped_posts'] = $tippedPosts;
}
?>
