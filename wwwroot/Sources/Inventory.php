<?php

if (!defined('SMF'))
	die('Hacking attempt...');

define('FISH_WIDTH', '120');
define('FISH_HEIGHT', '150');

abstract class BasicEnum {
    private static $constCacheArray = NULL;

    public static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
}

abstract class ItemType extends BasicEnum
{
    const Equipment = 0;		// pairs with equip_slot
}

abstract class EquipSlot extends BasicEnum
{
	const None = -1;

	// unequippable base slots
    const BodyBase = 0;
    const FaceBase = 1;

    // accessory slots
    const Chest1 = 101;
    const Chest2 = 102;
    const Head1 = 103;
    const Head2 = 104;
    const Neck = 105;
    const Legs1 = 106;
    const Legs2 = 107;
    const LeftHandHeld = 108;
    const RightHandHeld = 109;
    const Face1 = 110;
    const Face2 = 111;
    const Face3 = 112;
    const Hands = 113;
    const Feet = 114;
    const Back = 115;
    const Face4 = 116;
    const Background1 = 117;
    const Background2 = 118;
    const Background3 = 119;
}

abstract class ItemAvailability extends BasicEnum
{
	const Normal = 0;				
	const StartingItem = 1;			// available in the guest demo and given to new members
	const StartingItemLocked = 2;	// visible but locked in the guest demo, given to new members
	const DailyFeature = 3;			// this item may randomly appear as the daily feature item
}

abstract class CoinEarnReason extends BasicEnum
{
	const None = 0;
	const Hidden = 1;
	const Posting = 2;
	const Registration = 3;
}

function slotToLayer($slot)
{
	// there's probably a better way to do this but i don't care!
	switch($slot)
	{
		case EquipSlot::BodyBase:
			return 20;
		case EquipSlot::FaceBase:
			return 55;
		case EquipSlot::Chest1:
			return 40;
		case EquipSlot::Chest2:
			return 80;
		case EquipSlot::Head1:
			return 50;
		case EquipSlot::Head2:
			return 75;
		case EquipSlot::Neck:
			return 65;
		case EquipSlot::Legs1:
			return 25;
		case EquipSlot::Legs2:
			return 35;
		case EquipSlot::LeftHandHeld:
			return 15;
		case EquipSlot::RightHandHeld:
			return 95;
		case EquipSlot::Face1:
			return 60;
		case EquipSlot::Face2:
			return 70;
		case EquipSlot::Face3:
			return 45;
		case EquipSlot::Face4:
			return 85;
		case EquipSlot::Hands:
			return 90;
		case EquipSlot::Feet:
			return 30;
		case EquipSlot::Back:
			return 10;
		case EquipSlot::Background1:
			return 0;
		case EquipSlot::Background2:
			return 2;
		case EquipSlot::Background3:
			return 4;
	}

	// return a high layer by default so it overlaps everything 
	return 1000;
}

function getSlotsUsingLayer($layer)
{
	$slots = EquipSlot::getConstants();

	$result = array();

	foreach ($slots as $name => $value) {

		if($layer == slotToLayer($value))
		{
			$result[] = $value;
		}
	}

	return $result;
}

function isSlotRequired($slot)
{
	return $slot == EquipSlot::BodyBase || $slot == EquipSlot::FaceBase;
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
		                SELECT id_item, item_type, name_eng, icon_url,
		                	equip_slot, cost, availability,
		                	img_url, img_0_layer, img_sec_1_url, img_1_layer,
		                	img_sec_2_url, img_2_layer
						FROM {db_prefix}items ' .
		                $whereClause
		            ); 


		while ($row = mysql_fetch_assoc($itemRequest)) {
	  		$item = $result[$row['id_item']];

	  		// load all the attributes for this item and store them back in the result array
	  		$item['item_type'] = $row['item_type'];
	  		$item['name_eng'] = $row['name_eng'];
			$item['icon_url'] = $row['icon_url'];
			$item['equip_slot'] = $row['equip_slot'];
			$item['cost'] = $row['cost'];
			$item['availability'] = $row['availability'];

			$item['imgs'] = array();
			$item['imgs'][] = array (
								'url' => $row['img_url'],
								'layer' => $row['img_0_layer'] == -1 ? slotToLayer($item['equip_slot']) : $row['img_0_layer'],
							);
			$item['imgs'][] = array (
								'url' => $row['img_sec_1_url'],
								'layer' => $row['img_1_layer'] == -1 ? slotToLayer($item['equip_slot']) : $row['img_1_layer'],
							);
			$item['imgs'][] = array (
								'url' => $row['img_sec_2_url'],
							'layer' => $row['img_2_layer'] == -1 ? slotToLayer($item['equip_slot']) : $row['img_2_layer'],
							);

			// TODO retrieve override layer from DB?
			$item['layer'] = slotToLayer($item['equip_slot']);

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

	// list of base items that must be equipped, e.g. body types and face types
	$baseItems = array();

	// get all item data for the items 
	$itemRequest = $smcFunc['db_query']('', '
	                SELECT id_item, item_type, name_eng, icon_url,
		                	equip_slot, cost, availability,
		                	img_url, img_0_layer, img_sec_1_url, img_1_layer,
		                	img_sec_2_url, img_2_layer

					FROM {db_prefix}items
	                WHERE availability = {int:startingItem}
	                OR availability = {int:startingItemLocked}',
	                array(
	                	'startingItem' => ItemAvailability::StartingItem,
	                    'startingItemLocked' => ItemAvailability::StartingItemLocked,
	                )
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
		$item['icon_url'] = $row['icon_url'];
		$item['equip_slot'] = $row['equip_slot'];
		$item['is_locked'] = $row['availability'] == ItemAvailability::StartingItemLocked;

		$item['imgs'] = array();
		$item['imgs'][] = array (
							'url' => $row['img_url'],
							'layer' => $row['img_0_layer'] == -1 ? slotToLayer($item['equip_slot']) : $row['img_0_layer'],
						);
		$item['imgs'][] = array (
							'url' => $row['img_sec_1_url'],
							'layer' => $row['img_1_layer'] == -1 ? slotToLayer($item['equip_slot']) : $row['img_1_layer'],
						);
		$item['imgs'][] = array (
							'url' => $row['img_sec_2_url'],
							'layer' => $row['img_2_layer'] == -1 ? slotToLayer($item['equip_slot']) : $row['img_2_layer'],
						);

		// TODO retrieve override layer from DB?
		$item['layer'] = slotToLayer($item['equip_slot']);

		if(isSlotRequired($item['equip_slot']) && !$item['is_locked'])
		{
			$baseItems[] = $item;
		}

		$result[$row['id_item']] = $item;
	}

	// randomly equip items in the required slots
	// shuffle the array and choose the first one we find of each type
	shuffle($baseItems);
	$usedSlots = array();
	foreach ($baseItems as $item) 
	{
		if (!in_array($item['equip_slot'], $usedSlots))
		{
			$usedSlots[] = $item['equip_slot'];
			$result[$item['id']]['is_equipped'] = true;
		}
	}

	return $result;
}

// Make sure the inventory has been validated before calling this function
function dbInsertNewInventoryItems($inventory, $userid)
{
	global $smcFunc;	

	// TODO optimize this
	foreach ($inventory as $id => $item) 
	{
		// check if user has this item already
		$itemRequest = $smcFunc['db_query']('', '
			                SELECT count
							FROM {db_prefix}inventory
			                WHERE id_item = {int:id_item}
			                AND id_member = {int:id_member}
			                LIMIT 1',
			                array(
			                	'id_item' => $id,
			                	'id_member' => $userid,
			                )
			            ); 
		if($result = $smcFunc['db_fetch_assoc']($itemRequest))
		{
			// update table to increase count
			$smcFunc['db_query']('', '
                UPDATE {db_prefix}inventory
                SET count = {int:count}
                WHERE id_item = {int:id_item}
                AND id_member = {int:id_member}',
                array(
                	'count' => $result['count'] + $item['count'],
                    'id_item' => $id,
                    'id_member' => $userid,
                )
            ); 
		}
		else
		{
			// insert new entry
			$smcFunc['db_insert']('',
				'{db_prefix}inventory',
				array('id_member' => 'int', 'id_item' => 'int', 'count' => 'int', 'is_equipped' => 'int'),
				array($userid,				 $id, 		 		 $item['count'],	$item['is_equipped'] ? 1 : 0),
				array('id_member', 			'id_item',			'count', 			'is_equipped')
			);
		}
	
	}
}

function dbRemoveInventoryItem($itemID, $userid)
{
    global $smcFunc;

    $itemRequest = $smcFunc['db_query']('', '
                        SELECT count
                        FROM {db_prefix}inventory
                        WHERE id_item = {int:id_item}
                        AND id_member = {int:id_member}
                        LIMIT 1',
                        array(
                            'id_item' => $itemID,
                            'id_member' => $userid,
                        )
                    );
    if($result = $smcFunc['db_fetch_assoc']($itemRequest))
    {
        if ($result['count'] > 1)
        {
            $smcFunc['db_query']('', '
                UPDATE {db_prefix}inventory
                SET count = {int:count}
                WHERE id_item = {int:id_item}
                AND id_member = {int:id_member}',
                array(
                    'count' => $result['count'] - 1,
                    'id_item' => $itemID,
                    'id_member' => $userid,
                )
            ); 
        }
        else if ($result['count'] == 1)
        {
            $smcFunc['db_query']('', '
            DELETE FROM {db_prefix}inventory
            WHERE id_item = {int:id_item}
            AND id_member = {int:id_member}',
            array(
                'id_item' => $itemID,
                'id_member' => $userid
            ));
        }
    }
}

function dbGetItemInfo($itemID)
{
	global $smcFunc;

	$itemRequest = $smcFunc['db_query']('', '
					SELECT * FROM {db_prefix}items
					WHERE id_item = {int:itemID}
					LIMIT 1',
					Array(
						'itemID' => $itemID
					));

	$result = $smcFunc['db_fetch_assoc']($itemRequest);
	return $result;
}

// get a random item from the DB using today's date as the seed. 
// this isn't the best method as it could return different results throughout the day if the items table changes
function dbGetDailyFeatureItem()
{
	global $smcFunc;

	$seed = date('Ymd');
	$availability = ItemAvailability::DailyFeature;

	// TODO optimize this, i hear it crashes the server if the table is very large!!
	$itemRequest = $smcFunc['db_query']('', "
                SELECT id_item, item_type, name_eng, icon_url,
		                	equip_slot, cost, availability,
		                	img_url, img_0_layer, img_sec_1_url, img_1_layer,
		                	img_sec_2_url, img_2_layer
				FROM {db_prefix}items
				WHERE availability = {$availability}
                ORDER BY RAND({$seed})
                LIMIT 1"
            ); 

	// TODO cache the result
	$item = $smcFunc['db_fetch_assoc']($itemRequest);

	// TODO only pull the columns we care about. for now just copy the id to where we'd expect it to be
	$item['id'] = $item['id_item'];

	// featured items can't be sold in batches. for now, at least.
	$item['count'] = 1;

	// TODO retrieve override layer from DB?
	$item['layer'] = slotToLayer($item['equip_slot']);


	$item['imgs'] = array();
	$item['imgs'][] = array (
						'url' => $item['img_url'],
						'layer' => $item['img_0_layer'] == -1 ? slotToLayer($item['equip_slot']) : $item['img_0_layer'],
					);
	$item['imgs'][] = array (
						'url' => $item['img_sec_1_url'],
						'layer' => $item['img_1_layer'] == -1 ? slotToLayer($item['equip_slot']) : $item['img_1_layer'],
					);
	$item['imgs'][] = array (
						'url' => $item['img_sec_2_url'],
						'layer' => $item['img_2_layer'] == -1 ? slotToLayer($item['equip_slot']) : $item['img_2_layer'],
					);

	return $item;
}

function dbGetNpcTopicShopItems($msgId)
{
	global $smcFunc;

	$results = array();

	$itemIdRequest = $smcFunc['db_query']('', "
            SELECT id_item, expire_time
			FROM {db_prefix}npc_post_shop_items
			WHERE id_msg = {$msgId}
			"
        ); 

	while($row = $smcFunc['db_fetch_assoc']($itemIdRequest))
	{
		$item = array(
			'id' => $row['id_item'],
			'expire_time' => $row['expire_time'],
		);

		$itemDataRequest = $smcFunc['db_query']('', "
            SELECT name_eng, icon_url, equip_slot, cost
			FROM {db_prefix}items
			WHERE id_item = {$row['id_item']}
			"
        ); 

        if ($itemData = $smcFunc['db_fetch_assoc']($itemDataRequest))
        {
			$item['name_eng'] = $itemData['name_eng'];
			$item['icon_url'] = $itemData['icon_url'];
			$item['equip_slot'] = $itemData['equip_slot'];
			$item['cost'] = $itemData['cost'];
			$item['count'] = 1;

			$results[] = $item;
        }

  		
	}

	return $results;
}

function addCoins($userid, $amount, $earnReason = 0)
{
	global $smcFunc, $txt;

	$smcFunc['db_query']('', '
	        UPDATE {db_prefix}members
			SET coins = coins + {int:amount}
	        WHERE id_member = {int:id_member}',
                array(
                	'amount' => $amount,
                    'id_member' => $userid,
                )
    	); 

	if($earnReason === CoinEarnReason::Hidden)
	{
		$_SESSION['coinsEarned'] = 0;
		$_SESSION['coinsEarnedMsg'] = '';
	}
	else
	{

		$_SESSION['coinsEarned'] = $amount;
		$_SESSION['coinsEarnedMsg'] = sprintf($txt['coins_earn_' . $earnReason], number_format($amount));
	}
}

function getLastCoinsEarned()
{
	$result = array();

	$result['amount'] = $_SESSION['coinsEarned'];
	$result['msg'] = $_SESSION['coinsEarnedMsg']; 

	$_SESSION['coinsEarned'] = 0;
	$_SESSION['coinsEarnedMsg'] = '';

	return $result;
}

function spendCoins($userid, $amount)
{
	global $smcFunc, $txt;

	$smcFunc['db_query']('', '
	        UPDATE {db_prefix}members
			SET coins = coins - {int:amount}
	        WHERE id_member = {int:id_member}',
                array(
                	'amount' => $amount,
                    'id_member' => $userid,
                )
    	); 
}

?>