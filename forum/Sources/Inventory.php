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
    const BodyBase = 0;			// cannot be unequiped
    const Chest1 = 1;
    const Chest2 = 2;
    const Hair = 3;
    const Feet = 4;
    const Mouth = 5;
    const Legs1 = 6;
    const Legs2 = 7;
    const Hat = 8;
    const LeftHandHeld = 9;
    const RightHandHeld = 10;
    const Neck = 11;
    const Eyes = 12;
    const Face = 13;
    const Hands = 14;
}

abstract class ItemAvailability extends BasicEnum
{
	const Unlisted = 0;				// this item is not available for purchase, but can still be used/equipped
	const StartingItem = 1;			// available in the guest demo and given to new members
	const StartingItemLocked = 2;	// visible but locked in the guest demo, given to new members
	const DailyFeature = 3;			// this item may randomly appear as the daily feature item
	const NPCShop = 4;				// available in an npc shop. pairs with npcShopId
}

abstract class CoinEarnReason extends BasicEnum
{
	const None = 0;
	const Hidden = 1;
	const Posting = 2;
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
			$item['cost'] = $row['cost'];
			$item['availability'] = $row['availability'];

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

	// get all item data for the items 
	$itemRequest = $smcFunc['db_query']('', '
	                SELECT *
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
		$item['img_url'] = $row['img_url'];
		$item['icon_url'] = $row['icon_url'];
		$item['equip_slot'] = $row['equip_slot'];
		$item['can_delete'] = $row['can_delete'];
		$item['is_locked'] = $row['availability'] == ItemAvailability::StartingItemLocked;

		$result[$row['id_item']] = $item;
	}

	// equip a random body
	$bodyTypes = array();
	foreach ($result as $id => $item) 
	{
		if($item['equip_slot'] === (string) EquipSlot::BodyBase)
		{
			$bodyTypes[] = $id;
		}
	}

	if (!empty($bodyTypes))
	{
		$bodyId = $bodyTypes[array_rand($bodyTypes)];
		$result[$bodyId]['is_equipped'] = true;
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

// get a random item from the DB using today's date as the seed. 
// this isn't the best method as it could return different results throughout the day if the items table changes
function dbGetDailyFeatureItem()
{
	global $smcFunc;

	$seed = date('Ymd');
	$availability = ItemAvailability::DailyFeature;

	// TODO optimize this, i hear it crashes the server if the table is very large!!
	$itemRequest = $smcFunc['db_query']('', "
                SELECT *
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

	return $item;
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