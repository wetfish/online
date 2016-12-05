<?php

function template_main()
{

}

function template_view_items()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	<div id="admincenter">';

	echo '<div class="cat_bar">
			<h3 class="catbg">',$txt['manage_items_view_all'],'</h3>
		</div>';


	echo '<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl>
					<dt>
						TODO
					</dt>
					<dd>
						
					</dd>
				</dl>
			</div>
			<span class="botslice"><span></span></span>
		</div>';

	echo '</div>';
}

function template_add_new_item()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

// header
echo '
	<div id="admincenter">';
echo '<div class="cat_bar">
			<h3 class="catbg">',$txt['manage_items_add_new'],'</h3>
		</div>';

// start form
echo '<form class="windowbg2" action="', $scripturl, '?action=admin;area=addnewitem" method="post" accept-charset="ISO-8859-1" name="postForm" id="postForm" enctype="multipart/form-data">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl class="register_form">';

// item name
echo '
					<dt>
						<strong><label for="itemname_input">',$txt['admin_new_item_name'],'</label></strong>
					</dt>
					<dd>
						<input type="text" name="itemname" id="itemname_input" tabindex="1" size="30" maxlength="25" class="input_text">
					</dd>';

// item type
echo '
					<dt>
						<strong><label for="itemtype_input">',$txt['admin_new_item_type'],'</label></strong>
					</dt>
					<dd>
						<select name="itemtype" id="itemtype_input">';

$types = ItemType::getConstants();
foreach ($types as $name => $value) {
    echo  					'<option value="', $value, '">', $txt['item_type_' . $value], '</option>';
}

echo '
						</select>
					</dd>';



// equip slot
echo '
					<dt>
						<strong><label for="equipslot_input">',$txt['admin_new_item_slot'],'</label></strong>
						<span class="smalltext">',$txt['admin_new_item_slot_desc'],'</span>
					</dt>
					<dd>
						<select name="equipslot" id="equipslot_input">';

$slots = EquipSlot::getConstants();
foreach ($slots as $name => $value) {
    echo  					'<option value="', $value, '">', $txt['item_equip_slot_' . $value], '</option>';
}
						 

echo '
						</select>
					</dd>';


// cost
echo '
					<dt>
						<strong><label for="itemcost_input">',$txt['admin_new_item_cost'],'</label></strong>
						<span class="smalltext">',$txt['admin_new_item_cost_desc'],'</span>
					</dt>
					<dd>
						<input type="number" name="itemcost" id="itemcost_input" checked>
					</dd>';

// availability
echo '
					<dt>
						<strong><label for="itemavailability_input">',$txt['admin_new_item_availability'],'</label></strong>
						<span class="smalltext">',$txt['admin_new_item_availability_desc'],'</span>
					</dt>
					<dd>
						<select name="itemavailability" id="itemavailability_input">';

$availabilities = ItemAvailability::getConstants();
foreach ($availabilities as $name => $value) {
    echo  					'<option value="', $value, '">', $txt['item_availability_' . $value], '</option>';
}
						 
echo '
						</select>
						<br>';


foreach ($availabilities as $name => $value) {
    echo  					'<span class="smalltext availabilityoption_desc" id="availabilityoption_desc_', $value, '" style="display:none;">',$txt['item_availability_desc_' . $value],'</span>';
}
						 

echo '
						<script type="text/javascript">
						    $(document).ready(function() {

						    	refresh();

							    $("#itemavailability_input").change(function(e) {

								   refresh();
								});
						    });

						    function refresh()
						    {
						    	$( ".availabilityoption_desc" ).hide();

						    	var index = document.getElementById(\'itemavailability_input\').selectedIndex;
						    	$( "#availabilityoption_desc_" + index ).show();
						    }
						</script>

					</dd>';



// can users delete this item from their inventory?
echo '
					<dt>
						<strong><label for="candeleteitem_input">',$txt['admin_new_item_can_delete'],'</label></strong>
						<span class="smalltext">',$txt['admin_new_item_can_delete_desc'],'</span>
					</dt>
					<dd>
						<input type="checkbox" name="candeleteitem" id="candeleteitem_input" checked>
					</dd>';


// item icon
echo '

					<dt>
						<strong><label for="itemicon_input">',$txt['admin_new_item_icon'],'</label></strong>
						<span class="smalltext" id="itemicon_desc">',$txt['admin_new_item_icon_desc'],'</span>
						<img id="iconpreview" class="item-icon-button">
					</dt>
					<dd>
						<input type="file" name="itemicon" id="itemicon_input">
						<script type="text/javascript">
						    $(document).ready(function() {
						    $("#itemicon_input").change(function(e) {

						    for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {
						        var file = e.originalEvent.srcElement.files[i];

						        var img = document.getElementById("iconpreview");
						        var reader = new FileReader();
						        reader.onloadend = function() {
						            img.src = reader.result;
						        }
						        reader.readAsDataURL(file);
						    }
						});
						    });
						</script>
					</dd>';


// item image
echo '

					<dt>
						<strong><label for="itemimg_input">',$txt['admin_new_item_img'],'</label></strong>
						<span class="smalltext" id="itemimg_desc">',$txt['admin_new_item_img_desc'],'</span>
						<img id="imagepreview" width=120 height=150>
					</dt>
					<dd>
						<input type="file" name="itemimg" id="itemimg_input" >
						<script type="text/javascript">
						    $(document).ready(function() {
						    $("#itemimg_input").change(function(e) {

						    for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {
						        var file = e.originalEvent.srcElement.files[i];

						        var img = document.getElementById("imagepreview");
						        var reader = new FileReader();
						        reader.onloadend = function() {
						            img.src = reader.result;
						        }
						        reader.readAsDataURL(file);
						    }
						});
						    });
						</script>
					</dd>';


// submit button and end form
echo '
				</dl>
				<div class="righttext">
					<input type="submit" name="itemSubmit" value="',$txt['admin_new_item_submit'],'" tabindex="7" class="btn btn-danger btn-sm">
					<input type="hidden" name="sa" value="addnewitem">
				</div>
			</div>
			<span class="botslice"><span></span></span>
		</form>';

	echo '</div>';
}


function template_search_items()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	<div id="admincenter">';

	echo '<div class="cat_bar">
			<h3 class="catbg">',$txt['manage_items_search'],'</h3>
		</div>';


	echo '<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl>
					<dt>
						TODO
					</dt>
					<dd>
						
					</dd>
				</dl>
			</div>
			<span class="botslice"><span></span></span>
		</div>';

	echo '</div>';
}

?>