<?php

if (!defined('SMF'))
	die('Hacking attempt...');



function ViewItems()
{
	global $context, $txt, $smcFunc;

	$context['page_title'] = $txt['manage_items'];
	$context['sub_template'] = 'view_items';

	loadTemplate('ManageItems');
}

function SearchItems()
{
	global $context, $txt, $smcFunc;

	$context['page_title'] = $txt['manage_items'];
	$context['sub_template'] = 'search_items';

	loadTemplate('ManageItems');
}

function AddNewItem()
{
	global $context, $txt, $smcFunc;

	$context['page_title'] = $txt['manage_items'];
	$context['sub_template'] = 'add_new_item';

	loadTemplate('ManageItems');
}




?>