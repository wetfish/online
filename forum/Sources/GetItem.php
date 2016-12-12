<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function BuyDailyItem()
{
	global $context, $smcFunc, $user_info, $txt;

	is_not_guest();

	$_GET = htmltrim__recursive($_GET);
	$_GET = htmlspecialchars__recursive($_GET);

	$item = dbGetDailyFeatureItem();

	$context['page_title'] = $txt['featured_item_title'];

	if($context['user']['last_feature_purchase'] == date('Ymd'))
	{
		// already purchased this item
		$context['fail_message'] = $txt['featured_item_already_purchased'];
	}
	else if (empty($_GET['id']) || $item['id'] != $_GET['id'])
	{
		// this item is not currently featured
		$context['fail_message'] = $txt['featured_item_not_for_sale'];
	}
	else if($context['user']['coins'] < $item['cost'])
	{
		// you can't afford this
		$context['fail_message'] = $txt['featured_item_cant_afford'];
	}
	else
	{
		// add item to inventory
		dbInsertNewInventoryItems(array($item['id'] => $item), $context['user']['id']);

		// spend the dollars
		 spendCoins($context['user']['id'], $item['cost']);

		// update last purchase date for member
		$smcFunc['db_query']('', '
	        UPDATE {db_prefix}members
			SET last_feature_purchase = {string:purchase_date}
	        WHERE id_member = {int:id_member}',
                array(
                	'purchase_date' => date('Ymd'),
                    'id_member' => $context['user']['id'],
                )
    	); 

		// set flag
		$context['daily_item_purchased'] = $item;
	}

	loadTemplate('GetItem');
}


function BuyNpcPostShopItem()
{
	global $context, $smcFunc, $user_info, $txt;

	is_not_guest();

	$_GET = htmltrim__recursive($_GET);
	$_GET = htmlspecialchars__recursive($_GET);

	$context['page_title'] = $txt['buy_item_title'];

	// verify this item is for sale
	$allitems = dbGetNpcTopicShopItems($_GET['msg']);
	$item = null;
	foreach ($allitems as $key => $value) {
		if($value['id'] == $_GET['id'])
		{
			$item = $value;
			break;
		}
	}
	
	if ($item == null)
	{
		// item not for sale / invalid GET data
		$context['fail_message'] = $txt['featured_item_not_for_sale'];
	}
	else if($item['expire_time'] != -1 && time() > $item['expire_time'])
	{
		// expired
		$context['fail_message'] = sprintf($txt['npc_shop_expired_on'], timeformat($item['expire_time']));
	}
	else if($item['cost'] == 0 && !empty($context['user']['inventory'][$item['id']]))
	{
		// only 1 free item per person
		$context['fail_message'] = $txt['npc_shop_already_own_free_item_fail'];
	}
	else if($context['user']['coins'] < $item['cost'])
	{
		// you can't afford this
		$context['fail_message'] = $txt['featured_item_cant_afford'];
	}
	else
	{
		// add item to inventory
		dbInsertNewInventoryItems(array($item['id'] => $item), $context['user']['id']);

		// spend the dollars
		 spendCoins($context['user']['id'], $item['cost']);

		// update last purchase date for member
		$smcFunc['db_query']('', '
	        UPDATE {db_prefix}members
			SET last_feature_purchase = {string:purchase_date}
	        WHERE id_member = {int:id_member}',
                array(
                	'purchase_date' => date('Ymd'),
                    'id_member' => $context['user']['id'],
                )
    	); 

		// set flag
		$context['daily_item_purchased'] = $item;
	}

	loadTemplate('GetItem');
}

?>