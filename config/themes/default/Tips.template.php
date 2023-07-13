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
	global $context, $txt, $scripturl, $boardurl, $settings;

	// header
	echo '<div class="cat_bar">
			<h3 class="catbg">Recent Tips</h3>
		</div>';

	if (!$context['user']['is_guest'])
	{
	// Search form
	echo '<div class="windowbg2">
			<div class="content" style="text-align: center">
			<form action="", method="get" name="tip_search" id="tip_search" class="flow_hidden" enctype="multipart/form-data">',
					'<input type="hidden" name="action" value="tips" />',
					'<div class="col-md-6">
						Posts made by:<br />
						<input type="text" name="poster" value="',$_GET['poster'],'" autocomplete="off" />
					</div>',
					'<div class="col-md-6">
						Posts tipped by:<br />
						<input type="text" name="tipper" value="',$_GET['tipper'],'" autocomplete="off"/>
					</div>',
					'<div class="col-md-12">
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
	
	if (empty($context['recent_tipped_posts']))
	{
		echo 'No results!';
	}

	foreach ($context['recent_tipped_posts'] as $tippedPost)
	{
		echo '
			<div class="body_message">
				<div class="row">
					<div class="poster col-md-2">';
					// Load avatar
					if ($tippedPost['poster']['id'])
					{
						echo '
							<script type="text/javascript">
								$(document).ready(function() { 
									loadAvatar(\'',json_encode(loadInventory($tippedPost['poster']['id'], true)),'\', "fishcanvas_',  $tippedPost['post']['id_msg'], '", "fish_avatar_img_',  $tippedPost['post']['id_msg'],'");
								});
							</script>';
					// Display avatar
						echo '
							<canvas id="fishcanvas_',$tippedPost['post']['id_msg'],'" width="',FISH_WIDTH,'" height="',FISH_HEIGHT,'" style="display:none"></canvas>
							<img id="fish_avatar_img_',$tippedPost['post']['id_msg'],'" alt="', sprintf($txt['fish_avatar_img_alt'], $tippedPost['poster']['name']),'">';
					}
					echo    '<h4>';
					if ($tippedPost['poster']['href'])
					{
						echo '<a href="', $tippedPost['poster']['href'] ? $tippedPost['poster']['href'] : '', '">', $tippedPost['poster']['name'], '</a>';
					}
					else
					{
						echo $tippedPost['poster']['name'];
					}
						echo '</h4>
					</div>';

					echo '
					<div class="col-md-10">
						<div class="body_content">
							<span class="arrow-left"></span>
							<div class="postarea">
								<div class="keyinfo">
									<div class="messageicon">
										<img src="' . $settings['images_url'] . '/post/' . $tippedPost['post']['icon'] . '.gif"/>
									</div>
									<h5 id="subject_', $tippedPost['post']['id'], '">
										<a href="index.php?topic=', $tippedPost['post']['id_topic'], '.msg', $tippedPost['post']['id_msg'], '#msg', $tippedPost['post']['id_msg'], '">', $tippedPost['post']['subject'], '</a>
									</h5>
									<div class="smalltext">&#171; <strong>', $txt['on'], ':</strong> ', timeformat($tippedPost['post']['poster_time']), ' &#187;</div>
									<div id="msg_', $message['id'], '_quick_mod"></div>
								</div>';
								
								echo '
								<div class="post">
									<div class="inner" id="msg_' . $tippedPost['post']['id_msg'] . '">';
									echo parse_bbc($tippedPost['post']['body'], $tippedPost['post']['smileys_enabled'], $tippedPost['post']['id_msg']);
								echo '
									</div>
								</div>';

								foreach ($tippedPost['tips'] as $tip)
								{                                
									//echo print_r($tip);
									echo '<p class="post-tip-notice">';
									if (!$tip['coins'] && $tip['item'])
									{
										echo strtoupper($tip['tipper']), ' TIPPED <img class="item-icon" src="', $boardurl . $tip['item']['icon_url'] ,'" title="', $tip['item']['name_eng'], '"/> FOR THIS POST';
									}
									else
									{
										echo sprintf($txt['tip_for_message_post_footer'], strtoupper($tip['tipper']), $tip['coins']);
									} 
									'</p>';
								}

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
	</div><br />';
}
