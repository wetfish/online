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
	global $context, $txt, $scripturl, $boardurl;

	// header
	echo '<div class="cat_bar">
			<h3 class="catbg">',$txt['buy_item_title'],'</h3>
		</div>';

	// begin content
		echo '<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content" style="text-align:center;">';

//echo '<pre>', print_r($context), '</pre>';

	if(!empty($context['daily_item_purchased']))
	{
		$item = $context['daily_item_purchased'];
		echo '<p style="font-size:1.8em;">', sprintf($txt['featured_item_success'], $item['name_eng']),'</p>
			  <img src="', $boardurl, $item['icon_url'], '" class="item-icon" title="',$item['name_eng'], '" id="item_',$item['id'],'_img"/><br><br>';
		echo  '<a href="', $scripturl, '?action=profile;area=forumprofile;" class="btn btn-danger btn-sm">', $txt['featured_item_edit_avatar'],'</a>';
	}
	else if (isset($context['fail_message']))
	{
		echo '<p>',$context['fail_message'],'</p>';
	}
	else
	{
		echo '<p>',$txt['featured_item_fail'],'</p>';
	}

	// end content
	echo'
		</div>
		<span class="botslice"><span></span></span>
	</div>';

}

