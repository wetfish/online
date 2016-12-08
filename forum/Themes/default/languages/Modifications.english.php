<?php
// Version: 2.0; Modifications
global $context;

// Justin nov 25 2016 - topic ban strings
$txt['ban_from_topic'] = "Ban From Topic";
$txt['ban_from_topic_form_body'] = "Ban <b>%s</b> from your topic for the following post:";
$txt['ban_from_topic_reason'] = "Reason";
$txt['ban_from_topic_submit'] = "Submit";
$txt['ban_from_topic_return'] = "Return";
$txt['ban_from_topic_back'] = "Back";
$txt['ban_from_topic_success'] = "<b>%s</b> is now banned from your topic.";
$txt['ban_from_topic_fail'] = "Unable to ban <b>%s</b> from your topic.";
$txt['ban_from_topic_fail_reason_empty'] = "You must enter a reason.";
$txt['banned_from_topic_post_footer'] = "USER WAS BANNED FROM THIS TOPIC";
$txt['banned_from_topic_post_failed'] = "You are banned from this topic.";

// inventory / fish equipper stuff
$txt['inv_fish_avatar'] = "Avatar";
$txt['inv_items'] = "Items";
$txt['inv_equipment'] = "Accessories";
$txt['inv_body_type'] = "Body Type";
$txt['fish_avatar_img_alt'] = "%s's Avatar";
$txt['fish_demo_title'] = "Demo";
$txt['fish_try_me'] = "Customize this abomination";
$txt['fish_register_now_button'] = "<b>Register now</b>";
$txt['fish_register_now_text'] = " to unlock more accessories!";
$txt['fish_avatar_item_locked'] = " (LOCKED)";
$txt['permissionname_manage_items'] = "Manage items";
$txt['manage_items'] = "Items";
$txt['manage_items_search'] = "Search Items";
$txt['manage_items_add_new'] = "New Item";
$txt['manage_items_view_all'] = "View All Items";
$txt['featured_item_title'] = "Featured Item";
$txt['featured_item_cost'] = "Cost: ";
$txt['featured_item_buy'] = "Buy now";
$txt['featured_item_preview'] = "Preview";
$txt['featured_item_already_purchased'] = "You already purchased this item.";
$txt['featured_item_not_for_sale'] = "Item is no longer on sale.";
$txt['featured_item_cant_afford'] = "Not enough Coral";
$txt['featured_item_fail'] = "Error purchasing item.";
$txt['featured_item_success'] = "You got <b>%s</b>.";
$txt['featured_item_edit_avatar'] = "Edit your avatar";
$txt['featured_item_purchased'] = "Purchased!";

// currency stuff
$txt['coins'] = "Coral";
$txt['coins_earn_' . CoinEarnReason::None] = "(+%d)";
$txt['coins_earn_' . CoinEarnReason::Posting] = "(+%d for posting)";

// ItemTypes
$txt['item_type_' . ItemType::Equipment] = "Equipment";

// EquipSlots
$txt['item_equip_slot_' . EquipSlot::None] = "None";
$txt['item_equip_slot_' . EquipSlot::BodyBase] = "Body Type";
$txt['item_equip_slot_' . EquipSlot::Chest1] = "Chest 1";
$txt['item_equip_slot_' . EquipSlot::Chest2] = "Chest 2";
$txt['item_equip_slot_' . EquipSlot::Hair] = "Hair";
$txt['item_equip_slot_' . EquipSlot::Feet] = "Feet";
$txt['item_equip_slot_' . EquipSlot::Mouth] = "Mouth";
$txt['item_equip_slot_' . EquipSlot::Legs1] = "Legs 1";
$txt['item_equip_slot_' . EquipSlot::Legs2] = "Legs 2";
$txt['item_equip_slot_' . EquipSlot::Hat] = "Hat";
$txt['item_equip_slot_' . EquipSlot::LeftHand] = "Left Hand";
$txt['item_equip_slot_' . EquipSlot::RightHand] = "Right Hand";
$txt['item_equip_slot_' . EquipSlot::Neck] = "Neck";
$txt['item_equip_slot_' . EquipSlot::Eyes] = "Eyes";
$txt['item_equip_slot_' . EquipSlot::Face] = "Face";

// ItemAvailabilitys
$txt['item_availability_' . ItemAvailability::Unlisted] = "Unlisted";
$txt['item_availability_' . ItemAvailability::StartingItem] = "Starting Item";
$txt['item_availability_' . ItemAvailability::StartingItemLocked] = "Starting Item (Locked)";
$txt['item_availability_' . ItemAvailability::DailyFeature] = "Daily Feature";
$txt['item_availability_' . ItemAvailability::NPCShop] = "NPC Shop";

$txt['item_availability_desc_' . ItemAvailability::Unlisted] = "Users will not be able to find this item anywhere, but it can still be used if they happen to have it.";
$txt['item_availability_desc_' . ItemAvailability::StartingItem] = "All users start with this item and it is equippable on the demo avatar for guests.";
$txt['item_availability_desc_' . ItemAvailability::StartingItemLocked] = "All users start with this item and it is visible but locked for guests.";
$txt['item_availability_desc_' . ItemAvailability::DailyFeature] = "This item may randomly appear in the daily item sale.";
$txt['item_availability_desc_' . ItemAvailability::NPCShop] = "This item appears in an NPC shop. (Not implemented yet)";


// admin panel for items
$txt['admin_new_item_name'] = "Item Name: ";
$txt['admin_new_item_type'] = "Item Type: ";
$txt['admin_new_item_slot'] = "Equip Slot: ";
$txt['admin_new_item_slot_desc'] = "Required if Item Type is Equipment";
$txt['admin_new_item_img'] = "Sprite";
$txt['admin_new_item_img_desc'] = "120 x 150 PNG with alpha";
$txt['admin_new_item_icon'] = "Icon";
$txt['admin_new_item_icon_desc'] = "38 x 38 PNG with alpha";
$txt['admin_new_item_can_delete'] = "Removable";
$txt['admin_new_item_can_delete_desc'] = "Users can remove this item from their inventory";
$txt['admin_new_item_cost'] = "Cost";
$txt['admin_new_item_cost_desc'] = "The price for this item in Sand Dollars";
$txt['admin_new_item_availability'] = "Availability";
$txt['admin_new_item_availability_desc'] = "How users obtain this item";
$txt['admin_new_item_submit'] = "Submit";
$txt['admin_new_item_success'] = "Successfully added new item!";
$txt['admin_new_item_fail'] = "Failed to add new item.";
$txt['admin_new_item_fail_upload_error'] = "Upload failed";
$txt['admin_new_item_fail_unknown_type'] = "Unknown file type";
$txt['admin_new_item_fail_invalid_type'] = "Invalid file type";
$txt['admin_new_item_fail_name_empty'] = "Item name required";


// misc
$txt['website_title'] = "Wetfish Online";
$txt['website_description'] = "A pointless forum where you can dress up a shitty fish avatar and ban anybody from topics that you create.";
$txt['chat'] = "Chat";
?>