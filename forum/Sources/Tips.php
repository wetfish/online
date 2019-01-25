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
	
	// Find all tips in descending order
	// TO-DO: custom-limit: pagination support, etc.
	$tipsQuery = $smcFunc['db_query']('', '
				SELECT id_message_tip, id_message, id_member, coins
				FROM {db_prefix}message_tips
				ORDER BY id_message_tip DESC
				');
	
	// Get post associated with each tip
	while($tip = $smcFunc['db_fetch_assoc']($tipsQuery))
	{
		$tippedPostsQuery = $smcFunc['db_query']('', '
							SELECT id_msg, id_member, body, id_topic, poster_name, poster_time, icon, subject
							FROM {db_prefix}messages
							WHERE id_msg = {int:id_msg}',
							array(
								'id_msg' => $tip['id_message']
							));
		$result = $smcFunc['db_fetch_assoc']($tippedPostsQuery);

		// Load OP data into memberContext
		loadMemberData(array($result['id_member']), false, 'minimal');
		loadMemberContext($result['id_member']);

		// Load Tipper data into memberContext
		loadMemberData(array($tip['id_member']), false, 'minimal');
		loadMemberContext($tip['id_member']);

		$tippedPosts[$tip['id_message']] =
		array(
			'poster' => $memberContext[$result['id_member']],
			'tipper' => $memberContext[$tip['id_member']],
			'post' => $result,
			'tips' => array_merge_recursive( (array)$tippedPosts[$tip['id_message']]['tips'], array($tip))
		);
		$tippedPosts[$tip['id_message']]['post']['href'] = 'index.php?topic=' . $result['id_topic'] . '.msg' . $result['id_msg'] . '#msg' . $result['id_msg'];
	}
	$context['recent_tipped_posts'] = $tippedPosts;
}
?>
