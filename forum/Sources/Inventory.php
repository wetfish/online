<?php

if (!defined('SMF'))
	die('Hacking attempt...');

abstract class ItemType
{
    const Equipment = 0;
}

abstract class EquipSlot
{
    const BodyBase = 0;
    const Chest = 1;
    const Hair = 2;
}

function loadInventory($userid, $equipped_only = false)
{
	global $smcFunc;

	$result = array();

	if($equipped_only)
	{
		// get only equipped items
		$invRequest = $smcFunc['db_query']('', '
                SELECT id_item, count, is_equipped
				FROM {db_prefix}inventory
                WHERE id_member = {int:id_member}
                AND is_equipped = {int:is_equipped}',
                array(
                    'id_member' => $userid,
                    'is_equipped' => 1,
                )
            ); 
	}
	else
	{
		// get all item ids in members inventory
		$invRequest = $smcFunc['db_query']('', '
                SELECT id_item, count, is_equipped
				FROM {db_prefix}inventory
                WHERE id_member = {int:id_member}',
                array(
                    'id_member' => $userid,
                )
            ); 
	}
	

	$whereClause = '';

	while ($row = mysql_fetch_assoc($invRequest)) {
	  	$result[$row['id_item']] = array(
			'id' => $row['id_item'],
			'count' => $row['count'],
			'is_equipped' => $row['is_equipped'],
			);

	  	// build the item query
	  	if($whereClause == '')
		{
			$whereClause = 'WHERE id_item = ' . $row['id_item'];
		}
		else
		{
			$whereClause = $whereClause . ' OR id_item = ' . $row['id_item'];
		}
	}
	

	if($whereClause != '')
	{
		// get all item data for the items owned by the member
		$itemRequest = $smcFunc['db_query']('', '
		                SELECT *
						FROM {db_prefix}items ' .
		                $whereClause
		            ); 


		while ($row = mysql_fetch_assoc($itemRequest)) {
	  		$item = $result[$row['id_item']];

	  		// load all the attributes for this item and store them back in the result array
	  		$item['item_type'] = $row['item_type'];
	  		$item['name_eng'] = $row['name_eng'];
			$item['img_url'] = $row['img_url'];
			$item['icon_url'] = $row['icon_url'];
			$item['equip_slot'] = $row['equip_slot'];
			$item['can_delete'] = $row['can_delete'];

	  		$result[$row['id_item']] = $item;
		}
	}

//	echo '<pre>', print_r($result), '</pre>';
	return $result;
}

// Make sure the inventory has been validated before calling this function
function dbUpdateEquippedInventoryItems($userid, $inventory)
{
	global $smcFunc;

	foreach ($inventory as $key => $value) 
	{
		$smcFunc['db_query']('', '
	        UPDATE {db_prefix}inventory
			SET is_equipped = {int:is_equipped}
	        WHERE id_member = {int:id_member}
	        AND id_item = {int:id_item}',
                array(
                	'is_equipped' => $value['is_equipped'] ? 1 : 0,
                    'id_member' => $userid,
                    'id_item' => $value['id'],
                )
    	); 
	}
}

function generateStarterInventory()
{
	global $smcFunc;

	$result = array();

	// choose some item ids
	$ids = array(
		2,	// Gold Body
		4, 	// Blue Body
		3, 	// Pink Body
		7, 	// Red Jersey
		8,	// Green Jersey
		10,	// Dirty Socks
		12,	// Sweatshorts
		9,	// Kawaii Hairdo
		6, 	// Bikini Top
	);

	$whereClause = '';

	for($i = 0; $i < count($ids); $i++)
	{
		// build the item query
	  	if($whereClause == '')
		{
			$whereClause = 'WHERE id_item = ' . $ids[$i];
		}
		else
		{
			$whereClause = $whereClause . ' OR id_item = ' . $ids[$i];
		}
	}

	if($whereClause != '')
	{
		// get all item data for the items 
		$itemRequest = $smcFunc['db_query']('', '
		                SELECT *
						FROM {db_prefix}items ' .
		                $whereClause
		            ); 

		while ($row = mysql_fetch_assoc($itemRequest)) 
		{
			$item = array();

			// load all the attributes for this item and store them back in the result array
			$item['id'] = $row['id_item'];
			$item['count'] = 1;
			$item['is_equipped'] = false;

			$item['item_type'] = $row['item_type'];
			$item['name_eng'] = $row['name_eng'];
			$item['img_url'] = $row['img_url'];
			$item['icon_url'] = $row['icon_url'];
			$item['equip_slot'] = $row['equip_slot'];
			$item['can_delete'] = $row['can_delete'];

			$result[$row['id_item']] = $item;
		}
	}

	// equip a body
	$result[2]['is_equipped'] = true;

	// "lock" some items
	$result[9]['is_locked'] = true;
	$result[6]['is_locked'] = true;

	return $result;
}

// Make sure the inventory has been validated before calling this function
function dbInsertNewInventoryItems($inventory, $userid)
{
	global $smcFunc;	

	// TODO optimize this
	foreach ($inventory as $id => $item) {
		$smcFunc['db_insert']('',
			'{db_prefix}inventory',
			array('id_member' => 'int', 'id_item' => 'int', 'count' => 'int', 'is_equipped' => 'int'),
			array($userid,				 $id, 		 		 $item['count'],	$item['is_equipped'] ? 1 : 0),
			array('id_member', 			'id_item',			'count', 			'is_equipped')
		);
	}
}



?>