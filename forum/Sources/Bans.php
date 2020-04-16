<?php 

if (!defined('SMF'))
	die('Hacking attempt...');

function Bans()
{
	global $context, $smcFunc, $user_info, $txt;

	$context['page_title'] = $txt['ban_list_title'];
	loadBans();
	loadTemplate('Bans');
}

function loadBans()
{
	global $memberContext, $context, $smcFunc, $user_info;

	$bans = array();

	// Find bans
    $query = "SELECT id_topic_ban, id_topic, id_member, reason, id_msg FROM {db_prefix}topic_bans";
	
	// Searching for user?
	if (!empty($_GET['user']) && !$context['user']['is_guest'])
	{
		$userSearch = $smcFunc['db_query']('', "SELECT id_member from smf_members where real_name = '" . mysql_escape_string(urldecode($_GET['user'])) . "'");
		$result = $smcFunc['db_fetch_assoc']($userSearch)['id_member'];
		$query .= " WHERE id_member = '" . $result . "'";
	}
	
	$query .= " ORDER BY id_topic_ban DESC LIMIT 15";
	$bansQuery = $smcFunc['db_query']('', $query);
	
	while($ban = $smcFunc['db_fetch_assoc']($bansQuery))
	{
        // Load banned user data
		loadMemberData(array($ban['id_member']), false, 'minimal');
		loadMemberContext($ban['id_member']);
		
		// Load post data
		$query = "
		SELECT id_msg, id_member, body, id_topic, poster_name, poster_time, icon, subject, smileys_enabled
		FROM {db_prefix}messages
		WHERE id_msg = {int:id_msg}";

		$postQuery = $smcFunc['db_query']('', $query, array('id_msg' => $ban['id_msg']));
		$post = $smcFunc['db_fetch_assoc']($postQuery);

        $bans[$ban['id_topic_ban']] = 
        array(
			'member'    => $memberContext[$ban['id_member']],
			'post'		=> $post,
            'reason'    => $ban['reason'],
        );
	}
	$context['recent_bans'] = $bans;
}
?>