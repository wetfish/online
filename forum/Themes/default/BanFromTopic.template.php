<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.10
 */

// The main template for the post page.
function template_main()
{
	global $context, $txt, $scripturl;

	if($context['banconfirmed'])
	{
		// form submitted. show result message
		template_confirm();
	}
	else
	{
		// prompt for reason
		template_form();
	}

}

function template_form()
{
	global $context, $txt, $scripturl;

	if($context['canBanFromTopic'])
	{
		echo 
			'<dt>',
				sprintf($txt['ban_from_topic_form_body'], $context['ban_from_topic_target_poster_name']),
			'</dt>
			 <dd>
				<i>', $context['ban_from_topic_target_post_body'], 
				'</i>
			</dd>';

		echo "<br>";

		echo '<dt>', $txt['ban_from_topic_reason'], ': </dt>
		<dd><form action="', $scripturl, '" method="post" name="ban_from_topic" id="ban_from_topic" class="flow_hidden" enctype="multipart/form-data">';

		echo '<input type="hidden" name="action" value="banfromtopicconfirm" />';
 		echo '<input type="hidden" name="msg" value="',$context['msg'],'" />';
		echo '<input type="hidden" name="topic" value="',$context['topic'],'" />';
		echo '<input type="text" name="reason" size="80" maxlength="80" class="input_text" />';

		echo '<br><br>
		<input type="submit" value="',$txt['ban_from_topic_submit'], '" onclick="return submitThisOnce(this);" accesskey="s" class="button_submit" />';

		echo '</dd></form>';
	}
}

function template_confirm()
{
	global $context, $txt, $scripturl;


	if(!$context['bansuccess'])
	{
		// display error message
		if(strlen(trim($context['reason'])) <= 0)
		{
			echo $txt['ban_from_topic_fail_reason_empty'];

			echo '<br>';

			// display back button
			backbutton();
		}
		else
		{
			echo sprintf($txt['ban_from_topic_fail'], $context['ban_from_topic_target_poster_name']);

			echo '<br>';
			returnbutton();
		}
		
	}
	else
	{
		// display success message and return to topic button
		echo sprintf($txt['ban_from_topic_success'], $context['ban_from_topic_target_poster_name']);

		echo '<br>';

		returnbutton();
	}
	
}

function backbutton()
{
	global $context, $txt, $scripturl;

	echo '<a class="button_submit" href="javascript:history.back()">',$txt['ban_from_topic_back'],'</a>';

}

function returnbutton()
{
	global $context, $txt, $scripturl;

	echo '<a class="button_submit" href="',$scripturl, '?topic=', $context['topic'], '.msg', $context['msg'], '#msg',$context['msg'],  '">',$txt['ban_from_topic_return'],'</a>';

}
