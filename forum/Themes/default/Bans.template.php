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

include_once("Paginate.php");

function template_main()
{
	global $context, $txt, $scripturl, $settings;

	// header
	echo '<div class="cat_bar">
			<h3 class="catbg">Recent Bans</h3>
		</div>';

		if (!$context['user']['is_guest'])
		{
	// Search form
	echo '<div class="windowbg2">
			<div class="content" style="text-align: center">
			<form action="", method="get" name="ban_search" id="ban_search" class="flow_hidden" enctype="multipart/form-data">',
					'<input type="hidden" name="action" value="bans" />',
					'<div class="col-md-12">
						User:<br />
						<input type="text" name="user" value="',$_GET['user'],'" autocomplete="off" />
					</div>',
					'<div class="col-md-12" style="margin-top: 0.25em">
						<input type="submit" value="Search" onclick="return submitThisOnce(this);" accesskey="s" class="button_submit" />
					</div>
				</form>
			</div>
		</div>';
	}

	echo '<div class="pagesection">
			<div class="pagelinks floatleft">',
				$context['pages'],
			'</div>
		</div><br />';

	// begin content
	echo '<div class="forumposts">';
	
	if (empty($context['recent_bans']))
	{
		echo 'No results! ';
		if (!empty($_GET['user']))
		{
			echo '<a style="text-decoration: underline" href=', $scripturl ,'?action=bans>Go back?</a>';
		}
	}

	// Count each unique ban so that each fish canvas has a unique id
	$count = 0;
	foreach ($context['recent_bans'] as $ban)
	{
		$count++;

		if (!$ban['post'])
		{
			continue;
		}
		
		echo '
			<div class="body_message">
				<div class="row">
					<div class="poster col-md-2">';
					// Load avatar
					echo '
							<script type="text/javascript">
								$(document).ready(function() { 
									loadAvatar(\'',json_encode(loadInventory($ban['member']['id'], true)),'\', "fishcanvas_',  $count, '", "fish_avatar_img_',  $count,'");
								});
							</script>';
					// Display avatar
					echo '
							<canvas id="fishcanvas_',$count,'" width="',FISH_WIDTH,'" height="',FISH_HEIGHT,'" style="display:none"></canvas>
							<img id="fish_avatar_img_',$count,'" alt="', sprintf($txt['fish_avatar_img_alt'], $ban['member']['name']),'">
							<h4>
								<a href="', $ban['member']['href'], '">', $ban['member']['name'], '</a>
							</h4>
					</div>';
		
					echo '
					<div class="col-md-10">
						<div class="body_content">
							<span class="arrow-left"></span>
							<div class="postarea">
								<div class="keyinfo">
									<div class="messageicon">
										<img src="' . $settings['images_url'] . '/post/' . $ban['post']['icon'] . '.gif"/>
									</div>
									<h5 id="subject_', $ban['post']['id'], '">
										<a href="index.php?topic=', $ban['post']['id_topic'], '.msg', $ban['post']['id_msg'], '#msg', $ban['post']['id_msg'], '">', $ban['post']['subject'], '</a>
									</h5>
									<div class="smalltext">&#171; <strong>', $txt['on'], ':</strong> ', timeformat($ban['post']['poster_time']), ' &#187;</div>
									<div id="msg_', $message['id'], '_quick_mod"></div>
								</div>';
								
								echo '
								<div class="post">
									<div class="inner" id="msg_' . $ban['post']['id_msg'] . '">';
									echo parse_bbc($ban['post']['body'], $ban['post']['smileys_enabled'], $ban['post']['id_msg']);
								echo '
									</div>
								</div>';
									echo sprintf('<br /><p class="post-ban-reason">USER WAS BANNED FROM THIS TOPIC<br/>Reason: %s', $ban['reason']);

							echo '
							</div>
						</div>
					</div>
				</div>
			</div>';
	}
	// end content
	echo '</div>';
	echo '<div class="pagesection">
			<div class="pagelinks floatleft">',
				$context['pages'],
			'</div>
	</div>';
}
