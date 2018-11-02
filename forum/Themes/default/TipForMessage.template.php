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

	// header
	echo '<div class="cat_bar">
			<h3 class="catbg">',$txt['tip_for_message'],'</h3>
		</div>';

	// begin content
		echo '<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">';

	if($context['tipconfirmed'])
	{
		// form submitted. show result message
		template_confirm();
	}
	else
	{
		// prompt for reason
		template_form();
	}


	// end content
	echo'
		</div>
		<span class="botslice"><span></span></span>
	</div>';

}

function template_form()
{
	global $context, $txt, $scripturl;

	if($context['canTipForMessage'])
	{
		echo 
			'<dt>',
				sprintf($txt['tip_for_message_form_body'], $context['tip_for_message_target_poster_name']),
			'</dt>
			 <dd>
				<i>', $context['tip_for_message_target_post_body'], 
				'</i>
			</dd>';

		echo "<br>";

		echo '<dt>', $txt['tip_for_message_amount'], ': </dt>
		<dd><form action="', $scripturl, '" method="post" name="tip_for_message" id="tip_for_message" class="flow_hidden" enctype="multipart/form-data">';

		echo '<input type="hidden" name="action" value="tipformessageconfirm" />';
		echo '<input type="hidden" name="msg" value="',$context['msg'],'" />';
		echo '<input type="hidden" name="topic" value="',$context['topic'],'" />';
		echo '<input type="text" name="amount" size="10" maxlength="10" class="input_text" autocomplete="off" />';

		echo '<br><br>
		<input type="submit" value="',$txt['tip_for_message_submit'], '" onclick="return submitThisOnce(this);" accesskey="s" class="button_submit" />';

		echo '</dd></form>';

	}
}

function template_confirm()
{
	global $context, $txt, $scripturl;


	if(!$context['tipsuccess'])
	{
		// display error message
		if($context['tiperror'])
		{
			switch ($context['tiperror']) {
			case 'invalidamount':
				echo $txt['tip_for_message_fail_invalidamount'];
				break;
			case 'cantafford':
				echo $txt['tip_for_message_fail_cantafford'];
				break;
			}

			echo '<br>';
		
			// display back button
			backbutton();
		}
		else
		{
			echo sprintf($txt['tip_for_message_fail'], $context['tip_for_message_target_poster_name']);

			echo '<br>';
			returnbutton();
		}
		
	}
	else
	{
		// display success message and return to topic button
		echo sprintf($txt['tip_for_message_success']);

		echo '<br>';

		returnbutton();
	}
	
}

function backbutton()
{
	global $context, $txt, $scripturl;

	echo '<a class="button_submit" href="javascript:history.back()">',$txt['tip_for_message_back'],'</a>';

}

function returnbutton()
{
	global $context, $txt, $scripturl;

	echo '<a class="button_submit" href="',$scripturl, '?topic=', $context['topic'], '.msg', $context['msg'], '#msg',$context['msg'],  '">',$txt['tip_for_message_return'],'</a>';

}
