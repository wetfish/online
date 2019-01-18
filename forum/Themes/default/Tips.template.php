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

function template_main()
{
	global $context, $txt, $scripturl;

	// header
	echo '<div class="cat_bar">
			<h3 class="catbg">Recent Tips</h3>
		</div>';

	// begin content
	echo '<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<div class="content">';

	foreach ($context['recent_tipped_posts'] as $tippedPost)
	{
		// Sum tips
		$sum = 0;
		foreach($tippedPost['tips'] as $tip)
		{
			$sum += $tip['coins'];
		}
		
		echo sprintf("Post #<a href=index.php?topic=%d#msg%d>%d</a> by %s tipped %d times. total: %d <br />", 
		$tippedPost['post']['id_topic'],
		$tippedPost['post']['id_msg'],
		$tippedPost['post']['id_msg'],
		$tippedPost['post']['poster_name'],
		count($tippedPost['tips']),
		$sum);	
	}

	// end content
	echo'
		</div>
		<span class="botslice"><span></span></span>
	</div>';
}
