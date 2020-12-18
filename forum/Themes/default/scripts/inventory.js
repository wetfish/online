// TODO this whole function needs to be optimized and moved to the server.
// Why is the inventory an assoc array? would a numeric array improve performance in general?
function loadAvatar($inventory, $canvasId, $imgId)
{
	var inv = JSON.parse($inventory);
	var equipped = new Array();

	var canvas =document.getElementById($canvasId);
	var context =canvas.getContext("2d");


	// convert the assoc array into a numeric array and sort it
	for (var id in inv)
	{
		var item = inv[id];
		if(item['is_equipped'] == false)
		{
			continue;
		}

		for (var imgIndex in item['imgs'])
		{
			if(item['imgs'][imgIndex]['url'] !== '')
			{
				 equipped.push( item['imgs'][imgIndex] );
			}
			
		}
	   
	}

	// sort the inventory by layer
	equipped.sort(
          function(x, y)
          {
             return x['layer'] - y['layer'];
          }
        );

	var numLoaded = 0;
	var allImages = new Array();

	for (var i = 0; i < equipped.length; i++) {
		var item = equipped[i];
	    var image = new Image();

	    allImages.push( image );

	    image.onload = function() {
	        	numLoaded++;

	            if(numLoaded === equipped.length)
	            {
					// all of the images are finished loading. merge them
					context.clearRect(0, 0, canvas.width, canvas.height);

					for(j = 0; j < allImages.length; j++)
					{
						if(allImages[j])
						{
							context.drawImage(allImages[j], 0, 0, canvas.width, canvas.height);
						}
					}

		           	var dataURL = canvas.toDataURL();
	      			document.getElementById($imgId).src = dataURL;
	           }
	    };

	    image.src = '/forum/' + item['url'];
	}
}

function refreshAvatar()
{
	loadAvatar($displayedInventory, "fishcanvas", "fish_avatar_img");
}


function isSlotRequired($slot)
{
	return $slot == 0 || $slot == 1;	// body or face
}

function setItemEquipped(clickedItemId)
{
	var inv = JSON.parse($displayedInventory);
	var item = inv[clickedItemId];
	
	var wasEquipped = item['is_equipped'];

	// unequip the thing in this slot
	for (var key in inv) {
		var otherItem = inv[key];

	    if(otherItem['equip_slot'] === item['equip_slot'])
	    {
	    	otherItem['is_equipped'] = false;

	    	var otherIcon = document.getElementById("item_" + otherItem['id'] + "_img");
	    	if(otherIcon)
	    	{
	    		otherIcon.setAttribute("class", "item-icon-button");
	    	}

	    	var otherIconInput = document.getElementById("item_" + otherItem['id']);
	    	if(otherIconInput)
	    	{
				otherIconInput.setAttribute("value", 0);
	    	}
	    }
	}

	// equip/unequip the selected thing
	if(wasEquipped == false || isSlotRequired(item['equip_slot']))	//  if this is in slot 0, it is the body base and cannot be unequipped 
	{
		item['is_equipped'] = true;

		var clickedItemIcon = document.getElementById("item_" + clickedItemId + "_img");
		clickedItemIcon.setAttribute("class", "item-icon-button-equipped");

		var clickedItemIconInput = document.getElementById("item_" + clickedItemId);
		clickedItemIconInput.setAttribute("value", 1);
	}
	
	$displayedInventory = JSON.stringify(inv);
	refreshAvatar();
}

