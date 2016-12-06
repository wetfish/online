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
	global $context, $txt, $smcFunc, $boarddir;

	$context['page_title'] = $txt['manage_items'];
	$context['sub_template'] = 'add_new_item';


	if (isset($_POST["itemSubmit"]) && !empty($_POST["itemSubmit"])) 
	{
		$context['post_errors'] = array();

		// cleanup POST vars
		$_POST = htmltrim__recursive($_POST);
		$_POST = htmlspecialchars__recursive($_POST);

		// validate files
		$img = validateFile($_FILES['itemimg']);
		$icon = validateFile($_FILES['itemicon']);

		if($img !== true)
		{
			$context['post_errors'][] = $txt['admin_new_item_img'] . ': ' . $img;
		}
		
		if($icon !== true)
		{
			$context['post_errors'][] =  $txt['admin_new_item_icon'] . ': ' . $icon;
		}

		if(empty($_POST['itemname']))
		{
			$context['post_errors'][] =  $txt['admin_new_item_fail_name_empty'];
		}

		if(!$context['post_errors'])
		{
			// generate file names
			$imgFileName = preg_replace('/\PL/u', '',  $_POST['itemname']) . '_' . uniqid();
			$iconFileName = 'icon_' . $imgFileName . '.' . end((explode(".", $_FILES['itemicon']['name'])));
			$imgFileName = $imgFileName . '.' .  end((explode(".", $_FILES['itemimg']['name'])));	// add file extension

			// prepend directories
			$iconRelPath = '/fish/img/items/' . $iconFileName;
			$imgRelPath =  '/fish/img/items/' . $imgFileName;

			$iconFullPath = $boarddir . $iconRelPath;
			$imgFullPath =  $boarddir . $imgRelPath;

			// move the files
			if(move_uploaded_file($_FILES["itemicon"]["tmp_name"], $iconFullPath) && move_uploaded_file($_FILES["itemimg"]["tmp_name"], $imgFullPath) )
			{
				//insert all the things into the db

				$smcFunc['db_insert']('',
						'{db_prefix}items',
						array(
							'item_type' => 'int',
							'name_eng' => 'string', 
							'img_url' => 'string', 
							'icon_url' => 'string', 
							'equip_slot' => 'int', 
							'can_delete' => 'int', 
							'cost' => 'int',
							'availability' => 'int'
						),

						array(
							$_POST['itemtype'], 
							$_POST['itemname'],
							$imgRelPath,
							$iconRelPath,
							$_POST['equipslot'],
							$_POST['candeleteitem'] ? 1 : 0,
							$_POST['itemcost'],
							$_POST['itemavailability'],
						),

						array(
							'item_type',
							'name_eng', 
							'img_url',
							'icon_url',
							'equip_slot',
							'can_delete',
							'cost',
							'availability',

						)
					);

				$context['item_updated'] = $txt['admin_new_item_success'];
			}
			else
			{
				$context['post_errors'][] = $txt['admin_new_item_fail_upload_error'];
			}
		}

	}

	loadTemplate('ManageItems');
}

function validateFile($file)
{
	global $txt;

	if ($file['error'] !== UPLOAD_ERR_OK) {
	   return $txt['admin_new_item_fail_upload_error'];
	}

	$info = getimagesize($file['tmp_name']);
	if ($info === FALSE) {
	  return $txt['admin_new_item_fail_unknown_type'];
	}

	if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_PNG)) {
	   return $txt['admin_new_item_fail_invalid_type'];
	}

	return true;
}




?>