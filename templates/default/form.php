<?php

?>
<form action="<?php echo JRoute::_('index.php?option=com_directcron&task=getData&view=enterdata');?>" method="post" enctype="multipart/form-data">
    <div id="item"><span class="fieldname"><?php echo ucwords(JText::_('item')); ?></span>: <input type="text" name="item" /></div>
    <div id="description"><br />
        <table width="100%"><tr><td valign='top' width="10%">
            <span class="fieldname"><?php echo ucwords(JText::_('description')); ?>:</span></td>
                <td><textarea name="description" rows="10" cols="70"></textarea></td>
            </tr></table></div>
    <div id="itemurl"><span class="fieldname"><?php echo ucwords(JText::_('itemurl')); ?></span>: <input type="text" name="item_url" style="width: 400px" /></div>
    <div id="category"><span class="fieldname"><?php echo ucwords(JText::_('category')); ?></span>: <input type="text" name="category" style="width: 400px" /></div>
    <div id="image"><span class="fieldname"><?php echo ucwords(JText::_('image')); ?></span>: <input type="file" name="upload_file" style="width: 400px" /></div>
    <div id="submit"><input type="submit" value="Submit" /></div>
</form>