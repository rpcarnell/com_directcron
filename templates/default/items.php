<?php

 
$formattask = new strFormat(20);
foreach ($this->subcategories as $subcat)
{
    echo "<a href='".JRoute::_('index.php?option=com_directcron&view=items&task=viewItems&catid=2')."'>".$subcat->category."</a>";   
}
foreach ($this->items as $it)
{
?>
<div style="background: #a00; margin-bottom: 5px; padding: 5px; color: #fff;"><a href="<?php echo JRoute::_('index.php?option=com_directcron&view=items&task=oneitem&id='.$it->id);?>"><?php echo $it->item;?></a><br />
    <table style="background: #fff;"><tr>
             <?php if ($it->thumbnail) { ?> <td><?php echo JURI::base()."components/com_directcron/images/thumbnails/".$it->thumbnail;?><img src="<?php echo JURI::base()."components/com_directcron/images/thumbnails/".$it->thumbnail;?>" alt="<?php echo $it->item;?> image" /></td> <?php } ?> 
  
            <td valign="top"><?php echo $formattask->shorten_pr($it->description, 20);?></td>
   </tr></table>

</div>
<?php
}
?>
 
