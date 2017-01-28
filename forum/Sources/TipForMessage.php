<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function TipForMessage()
{
	global $context, $smcFunc, $user_info;

	is_not_guest();

	$context['page_title'] = $txt['tip_for_message'];

	$context['tipconfirmed'] = false;

	$context['topic'] = $_GET['topic'];
	$context['msg'] = $_GET['msg'];

	updateContext();

	loadTemplate('TipForMessage');
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

	$context['tip_for_message_target_userid'] = $msgresults['id_member'];
	$context['tip_for_message_target_post_body'] = $msgresults['body'];
	$context['tip_for_message_target_poster_name'] = $msgresults['poster_name'];


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
	
	// Can't tip yo'self
	$context['canTipForMessage'] =
								(
									$context['tip_for_message_target_userid'] &&
									$context['tip_for_message_target_userid'] != $user_info['id']
								);
}

function do_tip()
{
	global $context, $smcFunc, $user_info;

	$context['tipsuccess'] = false;
	$context['tiperror'] = '';

	$amount = 0;
	if (!preg_match("/^[0-9]+$/", $context['amount']))
	{
		$context['tiperror'] = 'invalidamount';
		return;
	}
	else
	{
		$amount = (int)$context['amount'];
		// Wouldn't it be nice if you can take coral from someone with
		// a negative tip?
		if ($amount <= 0) {
			$context['tiperror'] = 'invalidamount';
			return;
		}
	}

	if ($context['user']['coins'] < $amount)
	{
		$context['tiperror'] = 'cantafford';
		return;
	}

	
	if($context['canTipForMessage'] == false)
	{
		return;
	}

	// Checking current coins and transferring money should be in a
	// transaction but I don't know how the db stuff works.
	spendCoins($context['tip_for_message_target_userid'], -$amount);
	spendCoins($user_info['id'], $amount);

	$smcFunc['db_insert']('',
		'{db_prefix}message_tips',
		array(
			'id_message' => 'int',
			'id_member' => 'int',
			'coins' => 'int'
		),
		array(
			$context['msg'],
			$user_info['id'],
			$amount
		),
		array('id_message', 'id_member', 'coins')
	);

	// still good
	$context['tipsuccess'] = true;
}

function TipForMessageConfirm()
{
	global $context, $smcFunc, $user_info;

	is_not_guest();

	$_POST = htmltrim__recursive($_POST);
	$_POST = htmlspecialchars__recursive($_POST);

	$context['topic'] = $_POST['topic'];
	$context['msg'] = $_POST['msg'];
	$context['amount'] = $_POST['amount'];

	updateContext();

	$context['tipconfirmed'] = true;
	do_tip();

	loadTemplate('TipForMessage');
}

?>
