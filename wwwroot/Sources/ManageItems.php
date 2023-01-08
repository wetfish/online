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
	global $context, $txt, $smcFunc, $boarddir, $user_info;

	$context['page_title'] = $txt['manage_items'];
	$context['sub_template'] = 'add_new_item';


	if (isset($_POST["itemSubmit"]) && !empty($_POST["itemSubmit"])) 
	{
		$context['post_errors'] = array();

		// cleanup POST vars
		$_POST = htmltrim__recursive($_POST);
		$_POST = htmlspecialchars__recursive($_POST);

		// validate icon
		$icon = validateFile($_FILES['itemicon']);
		if($icon !== true)
		{
			$context['post_errors'][] =  $txt['admin_new_item_icon'] . ': ' . $icon;
		}

		// validate primary image
		$img = validateFile($_FILES['itemimg_0']);
		if($img !== true)
		{
			$context['post_errors'][] = $txt['admin_new_item_img'] . ': ' . $img;
		}

		// validate optional secondary images
		if (!empty($_FILES['itemimg_1']['tmp_name']))
		{
			$img1 = validateFile($_FILES['itemimg_1']);
			if($img1 !== true)
			{
				$context['post_errors'][] = sprintf($txt['admin_new_item_img_sec'] , 1) . ': ' . $img1;
			}
		}
		
		if (!empty($_FILES['itemimg_2']['tmp_name']))
		{
			$img2 = validateFile($_FILES['itemimg_2']);
			if($img2 !== true)
			{
				$context['post_errors'][] = sprintf($txt['admin_new_item_img_sec'] , 2) . ': ' . $img2;
			}
		}
		
		// validate name
		if(empty($_POST['itemname']))
		{
			$context['post_errors'][] =  $txt['admin_new_item_fail_name_empty'];
		}

		if(!$context['post_errors'])
		{
			// generate file names
			$imgFileName = preg_replace('/\PL/u', '',  $_POST['itemname']) . '_' . uniqid();
			$iconFileName = $imgFileName . '_icon.' . end((explode(".", $_FILES['itemicon']['name'])));
			$imgFileName0 = $imgFileName . '_0.' .  end((explode(".", $_FILES['itemimg_0']['name'])));	
			$imgFileName1 = $imgFileName . '_1.' .  end((explode(".", $_FILES['itemimg_1']['name'])));
			$imgFileName2 = $imgFileName . '_2.' .  end((explode(".", $_FILES['itemimg_2']['name'])));

			// prepend directories
			$iconRelPath = '/fish/img/items/' . $iconFileName;
			$imgRelPath0 =  '/fish/img/items/' . $imgFileName0;
			$imgRelPath1 =  '/fish/img/items/' . $imgFileName1;
			$imgRelPath2 =  '/fish/img/items/' . $imgFileName2;

			$iconFullPath = $boarddir . $iconRelPath;
			$imgFullPath0 =  $boarddir . $imgRelPath0;
			$imgFullPath1 =  $boarddir . $imgRelPath1;
			$imgFullPath2 =  $boarddir . $imgRelPath2;


			
			// move the files
			$uploadFailed = false;
			if (!move_uploaded_file($_FILES["itemicon"]["tmp_name"], $iconFullPath))
			{
				$uploadFailed = true;
			}

			if (!move_uploaded_file($_FILES["itemimg_0"]["tmp_name"], $imgFullPath0))
			{
				$uploadFailed = true;
			}

			if (!empty($_FILES['itemimg_1']['tmp_name']))
			{
				if (!move_uploaded_file($_FILES["itemimg_1"]["tmp_name"], $imgFullPath1))
				{
					$uploadFailed = true;
				}
			}

			if (!empty($_FILES['itemimg_2']['tmp_name']))
			{
				if (!move_uploaded_file($_FILES["itemimg_2"]["tmp_name"], $imgFullPath2))
				{
					$uploadFailed = true;
				}
			}

			if(!$uploadFailed)
			{
				//insert all the things into the db

				$smcFunc['db_insert']('',
						'{db_prefix}items',
						array(
							'item_type' => 'int',
							'name_eng' => 'string', 
							'icon_url' => 'string', 
							'img_url' => 'string', 
							'img_sec_1_url' => 'string', 
							'img_sec_2_url' => 'string', 
							'equip_slot' => 'int', 
							'cost' => 'int',
							'availability' => 'int',
							'date_added' => 'int',
							'last_modified' => 'int',
							'created_by_userid' => 'int',
							'img_0_layer' => 'int',
							'img_1_layer' => 'int',
							'img_2_layer' => 'int',
						),

						array(
							$_POST['itemtype'], 
							$_POST['itemname'],
							$iconRelPath,
							$imgRelPath0,
							!empty($_FILES['itemimg_1']['tmp_name']) ? $imgRelPath1 : '',
							!empty($_FILES['itemimg_2']['tmp_name']) ? $imgRelPath2 : '',
							$_POST['equipslot'],
							$_POST['itemcost'],
							$_POST['itemavailability'],
							time(),
							time(),
							$user_info['id'],
							$_POST['img_layer_input_0'],
							$_POST['img_layer_input_1'],
							$_POST['img_layer_input_2'],
						),

						array(
							'item_type',
							'name_eng', 
							'icon_url',
							'img_url',
							'img_sec_1_url',
							'img_sec_2_url',
							'equip_slot',
							'cost',
							'availability',
							'date_added',
							'last_modified',
							'created_by_userid',
							'img_0_layer',
							'img_1_layer',
							'img_2_layer',

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