function showFields(category)
{
    cronframe.jQuery('#addfields').html('is that a skill');
    alert(category);
}
function submitChanges()
{
    var $category = cronframe.jQuery('select[name=category]').val();
    var $type = cronframe.jQuery('select[name=type]').val();
    var $id = cronframe.jQuery('input[name=cid]').val();
    var $nullornot = cronframe.jQuery('select[name=nullornot]').val();
    var $name = cronframe.jQuery('input[name=name]').val();
    var $extra = cronframe.jQuery('input[name=extra]').val();
    var $style_id = cronframe.jQuery('input[name=style_id]').val();
    var $style_class = cronframe.jQuery('input[name=style_class]').val();
    var $ordering = cronframe.jQuery('input[name=ordering]').val();
    var $published = cronframe.jQuery('input[name=published]').val();
    cronframe.jQuery.post(tasksURL + "?option=com_directcron&view=addfields&task=ajaxeditfield", {id: $id, published: $published, category: $category, type: $type, nullornot: $nullornot, name: $name, extra: $extra, ordering: $ordering, style_id: $style_id, style_class: $style_class},
    function(data) { alert(data); cronframe.jQuery('#formresult').html(data); });
}
function closePopUp(popID)
{
    cronframe.jQuery('#' + popID).fadeOut('fast');
    cronframe.jQuery('a.close').remove();
}
function editFields($this)
{
    var popID = cronframe.jQuery($this).attr('href'); //Get Popup Name, it is Rel in the link, but the ID for the popup
    
    var fieldUsed = popID.split('=')[0].replace('#', '');
    var id = ''; 
    var catid = '';
    if (fieldUsed == 'catid') {catid = popID.split('=')[1];}
    else id = popID.split('=')[1];
    popID = "editfield";
    cronframe.jQuery('#' + popID).fadeIn('fast');
    if (! cronframe.jQuery('#' + popID).find('.close').length) //let's make sure close image is not added again
    { cronframe.jQuery('#' + popID).prepend('<a href="#" class="close"><img src="'+ tasksURL+ 'components/com_directcron/images/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>'); }
    cronframe.jQuery('.close').bind('click', function() { closePopUp(popID); } );
    var popMargTop = (cronframe.jQuery('#' + popID).height() + 40) / 2;
    var popMargLeft = 10;
    //Apply Margin to Popup
    cronframe.jQuery('#' + popID).css({'margin-top' : -popMargTop, 'margin-left' : -popMargLeft });
    cronframe.jQuery('#editfieldcontent').html('isolation here');
    cronframe.jQuery.post(tasksURL + "?option=com_directcron&view=addfields&task=ajaxfieldinfo", {id: id, catid: catid},
    function(data) { cronframe.jQuery('#editfieldcontent').html(data); });
    return false;
}



