<?php 

if (!defined('SMF'))
	die('Hacking attempt...');

include_once("Paginate.php");

function Tips()
{
	global $context, $smcFunc, $user_info, $txt, $modSettings;

	$context['page_title'] = $txt['tip_list_title'];
	$modSettings['disableQueryCheck'] = true;
	loadPosts();
	unset($modSettings['disableQueryCheck']);
	loadTemplate('Tips');
}

function loadPosts()
{
	global $memberContext, $context, $smcFunc, $user_info;
	
	$tippedPosts = array();
	$postsPerPage = 15;
	$maxPages = 10;

	// Get posts that have been tipped
	$query = "
		SELECT id_msg, id_message, msg.id_member, id_topic, poster_name, poster_time, icon, subject, body, smileys_enabled 
		FROM {db_prefix}messages AS msg 
		INNER JOIN ( 
			SELECT t.id_message, t.id_message_tip, t.id_member 
				FROM smf_message_tips t 
				INNER JOIN ( 
						SELECT id_message, max(id_message_tip) AS max 
						FROM smf_message_tips 
						GROUP BY id_message ) q 
				ON t.id_message = q.id_message 
				AND t.id_message_tip = q.max ) AS tip 
		ON msg.id_msg = tip.id_message";

	// Searching for posts tipped by a specific user?
	if (!empty($_GET['tipper']) && !$context['user']['is_guest'])
	{
		// Find user id for searched user
		$userSearch = $smcFunc['db_query']('', "SELECT id_member from {db_prefix}members where real_name = '" . mysql_escape_string(urldecode($_GET['tipper'])) . "'");
		$result = $smcFunc['db_fetch_assoc']($userSearch)['id_member'];
		$query .= " WHERE tip.id_member = '$result'";
	}

	// Searching for posts made by a specific user?
	if (!empty($_GET['poster']) && !$context['user']['is_guest'])
	{
		$userSearch = $smcFunc['db_query']('', "SELECT id_member from {db_prefix}members where real_name = '" . mysql_escape_string(urldecode($_GET['poster'])) . "'");
		$result = $smcFunc['db_fetch_assoc']($userSearch)['id_member'];
		if (!empty($_GET['tipper']))
		{
			$query .= " AND msg.id_member = '$result'";
		}
		else
		{
			$query .= " WHERE msg.id_member = '$result'";
		}
	}

	$query .= " ORDER BY id_message_tip DESC";
	$pageCount = (int)ceil(mysql_num_rows($smcFunc['db_query']('', "$query LIMIT " . $postsPerPage * $maxPages)) / $postsPerPage);

	// On a specific page?
	$page = 1;
	if (isset($_GET['page']) && is_numeric($_GET['page']))
	{
		$page = (int)mysql_escape_string(urldecode($_GET['page']));
		$page = min(max($page, 1), $pageCount);
	}
	$query .= " LIMIT " . ($page - 1) * $postsPerPage . ",$postsPerPage";

	$tippedPostsQuery = $smcFunc['db_query']('', $query);
	while($post = $smcFunc['db_fetch_assoc']($tippedPostsQuery))
	{
		// Load tips for this post
		$query = "
			SELECT id_message_tip, id_message, id_member, coins, item
			FROM {db_prefix}message_tips
			WHERE id_message = " . $post['id_msg'];

		$tipsQuery = $smcFunc['db_query']('', $query);

		// Load this post's tips into an array
		$tips = array();
		while ($tip = $smcFunc['db_fetch_assoc']($tipsQuery))
		{
			loadMemberData(array($tip['id_member']), false, 'minimal');
			loadMemberContext($tip['id_member']);
			
			// Store the name of the tipper and any potential tipped item data with the tip.
			$tip['tipper'] = $memberContext[$tip['id_member']]['name'];
			if ($tip['item'] != 0)
			{
				$tip['item'] = dbGetIteminfo($tip['item']);
			}
			$tips[] = $tip;
		}

		// Load OP data into memberContext
		loadMemberData(array($post['id_member']), false, 'minimal');
		loadMemberContext($post['id_member']);

		$tippedPosts[$post['id_message']] =
		array(
			'poster' => $memberContext[$post['id_member']],
			'post' => $post,
			'tips' => $tips,
		);
	}
	$context['recent_tipped_posts'] = $tippedPosts;
	$context['pages'] = Paginate($_SERVER['QUERY_STRING'], $page, $pageCount, 15);
}
?>
