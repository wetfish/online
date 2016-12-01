
function loadAvatar($inventory, $canvasId, $imgId)
{
	var inv = JSON.parse($inventory);

	var canvas =document.getElementById($canvasId);
	var context =canvas.getContext("2d");
	
	// first count the equipped items so we can encode them image when the last one finishes loading
	var numEquipped = 0;
	for (var key in inv) {
		var item = inv[key];

		if(item['is_equipped'] == false)
		{
			continue;
		}

		numEquipped += 1;
	}

	var numLoaded = 0;
	var allImages = new Array();

	for (var key in inv) {
		var item = inv[key];

		if(item['is_equipped'] == false)
		{
			continue;
		}

	    var image = new Image();

	    // put this image in the appropriate layer so we can draw it later
	    allImages[item['equip_slot']] = image;

	    image.onload = function() {
	        	numLoaded++;

	            if(numLoaded === numEquipped)
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

	    image.src = '/forum/' + item['img_url'];
	}
}

function refreshAvatar()
{
	loadAvatar($displayedInventory, "fishcanvas", "fish_avatar_img");
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
	if(wasEquipped == false || item['equip_slot'] == 0)	//  if this is in slot 0, it is the body base and cannot be unequipped 
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

