<?php 

if (!defined('SMF'))
	die('Hacking attempt...');

function Tips()
{
	global $context, $smcFunc, $user_info, $txt;

	is_not_guest();

	$context['page_title'] = $txt['tip_list_title'];
	
	loadPosts();

	loadTemplate('Tips');
}

function loadPosts()
{
	global $context, $smcFunc, $user_info;
	
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
							SELECT id_msg, id_member, body, id_topic, poster_name
							FROM {db_prefix}messages
							WHERE id_msg = {int:id_msg}',
							array(
								'id_msg' => $tip['id_message']
							));

		// Store posts and tips in array 'post_id' = [post, tips[]]
		$tippedPosts[$tip['id_message']] =
		array(
			'post' => $smcFunc['db_fetch_assoc']($tippedPostsQuery),
			'tips' => array_merge_recursive( (array)$tippedPosts[$tip['id_message']]['tips'], array($tip))
		);
	}
	
	$context['recent_tipped_posts'] = $tippedPosts;
}
?>