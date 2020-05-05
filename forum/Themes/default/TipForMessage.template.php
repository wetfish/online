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
	global $boardurl;
	
	// Scripts to display coral text box or inventory and set active buttons.
	echo '
	<script type="text/javascript">
		// Displays or Hides relevant divs depending on selection
		function updateTipType(isCoralTip)
		{
			if (isCoralTip)
			{
				document.getElementById("coralTip").style.display = "inline-block";
				document.getElementById("itemTip").style.display = "none";
			}
			else
			{
				document.getElementById("itemTip").style.display = "inline-block";
				document.getElementById("coralTip").style.display = "none";
			}           
		}

		function setActive(button)
		{
			var current = document.getElementsByClassName("item-icon-button-equipped");
			if (typeof(current[0]) != "undefined")
			{
				current[0].className = "item-icon-button";
			}
			button.className = "item-icon-button-equipped";
		}
	</script>';

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

		echo '
		<form action="', $scripturl, '" method="post" name="tip_for_message" id="tip_for_message" class="flow_hidden" enctype="multipart/form-data">
		<div class="btn-group" data-toggle="buttons">
		<label class="btn btn-primary active" onclick="javascript:updateTipType(true);">
			Coral
			<input type="radio" name="tipType" value="coral" checked/>
		</label>
		<label class="btn btn-primary" onclick="javascript:updateTipType(false);">
			Item
			<input type="radio" name="tipType" value="item"/>
		 </label>
		</div>
		<dd style="margin-top:5px; margin-inline-start:0px">';

		echo '<input type="hidden" name="action" value="tipformessageconfirm" />';
		echo '<input type="hidden" name="msg" value="',$context['msg'],'" />';
		echo '<input type="hidden" name="topic" value="',$context['topic'],'" />';
		
		// Stuff to display when tipping coral
		echo '
		<div id="coralTip">
		Amount: <br />
		<input type="text" name="amount" size="10" maxlength="10" class="input_text" autocomplete="off" />
		</div>';

		// Stuff to display when tipping items
		echo '
		<div id="itemTip" style="display:none; width:50%;">';
		foreach ($context['user']['inventory'] as $key => $value) {
			echo '<label style="position: relative" onclick="setActive(this);" class="item-icon-button">
			<img class="item-icon-button-img" src="', $boardurl, $value['icon_url'], '" title="', $value['name_eng'],'"/>
			<input style="display: none" type="radio" name="itemID" value="', $value['id'], '"/>';
			if ($value['count'] > 1)
			{
				echo "<div class='item-icon-button-text'>{$value['count']}</div>";
			}
			echo '</label>';
		}
		echo '</div>';
		
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
			case 'noitem':
				echo $txt['tip_for_message_fail_item_noitem'];
				break;
			case 'notenough':
				echo $txt['tip_for_message_fail_item_notenough'];
				break;
			case 'bodyfacelimit':
				echo $txt['tip_for_message_fail_item_bodyfacelimit'];
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
