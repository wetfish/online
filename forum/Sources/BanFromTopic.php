<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function BanFromTopic()
{
	global $context, $smcFunc, $user_info;

	$context['page_title'] = $txt['ban_from_topic'];

	$context['banconfirmed'] = false;

	$context['topic'] = $_GET['topic'];
	$context['msg'] = $_GET['msg'];

	updateContext();

	loadTemplate('BanFromTopic');
}

function updateContext()
{
	global $context, $smcFunc, $user_info;

	// find the offending post
	$msgrequest = $smcFunc['db_query']('', '
                SELECT id_member, body, poster_name
				FROM {db_prefix}messages
                WHERE id_msg = {int:id_msg}
                AND id_topic = {int:id_topic}
                LIMIT 1',
                array(
                    'id_msg' => $context['msg'],
                    'id_topic' => $context['topic'],
                )
            ); 

	$msgresults = $smcFunc['db_fetch_assoc']($msgrequest);

	$context['ban_from_topic_target_userid'] = $msgresults['id_member'];
	$context['ban_from_topic_target_post_body'] = $msgresults['body'];
	$context['ban_from_topic_target_poster_name'] = $msgresults['poster_name'];


	// find the OP of the topic to which it belongs
	$topicrequest = $smcFunc['db_query']('', '
                SELECT id_member_started
				FROM {db_prefix}topics
                WHERE id_topic = {int:id_topic}
                LIMIT 1',
                array(
                    'id_topic' => $context['topic'],
                )
            ); 

	$topicresults = $smcFunc['db_fetch_assoc']($topicrequest);
	

	// check if the current user can ban ppl from this topic
	$context['canBanFromTopic'] = $user_info['is_admin'] ||
								(
									$context['ban_from_topic_target_userid'] &&
									$context['ban_from_topic_target_userid'] != $user_info['id'] &&
									$topicresults['id_member_started'] == $user_info['id']
								);
}

function BanFromTopicConfirm()
{
	global $context, $smcFunc, $user_info;

	$context['banconfirmed'] = true;

	$_POST = htmltrim__recursive($_POST);
	$_POST = htmlspecialchars__recursive($_POST);

	$context['topic'] = $_POST['topic'];
	$context['msg'] = $_POST['msg'];
	$context['reason'] = $_POST['reason'];

	updateContext();

	// check if user entered a reason
	$reasonLength = strlen(trim($context['reason']));
	$context['bansuccess'] = $reasonLength > 0 && $reasonLength <= 80;

	if($context['canBanFromTopic'] == false)
	{
		$context['bansuccess'] = false;
	}

	if($context['bansuccess'])
	{
		// check if user is already banned...
		$bancheckRequest = $smcFunc['db_query']('', '
                SELECT id_topic_ban
				FROM {db_prefix}topic_bans
                WHERE id_topic = {int:id_topic}
                AND id_member = {int:id_member}
                LIMIT 1',
                array(
                    'id_topic' => $context['topic'],
                    'id_member' =>$context['ban_from_topic_target_userid'],
                )
            ); 

		$banCheckResult = $smcFunc['db_fetch_assoc']($bancheckRequest);

		// if there are results, user is already banned
		if($banCheckResult)
		{
			$context['bansuccess'] = false;
		}
	}

	if($context['bansuccess'])
	{
		// banning is OK so far. update the database.
		$smcFunc['db_insert']('',
			'{db_prefix}topic_bans',
			array('id_topic' => 'int', 'id_member' => 'int', 'reason' => 'string'),
			array($context['topic'], $context['ban_from_topic_target_userid'], $context['reason']),
			array('id_topic', 'id_member', 'reason')
		);
	}

	loadTemplate('BanFromTopic');
}

?>