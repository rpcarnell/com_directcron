<div style="float: right"><a style="text-decoration: none;" href="<?php echo JRoute::_('index.php?option=com_directcron&format=feed&type=rss');?>">RSS <img src="<?php echo Juri::base()."components/com_directcron/images/icons/rss.jpg"?>" alt="rss icon"/></a></div>
<div style="clear: both"></div><br /><br />
<?php
if (isset($this->subcategories) && is_array($this->subcategories)) {
foreach ($this->subcategories as $subcat)
{
    echo "<a href='".JRoute::_('index.php?option=com_directcron&view=items&task=viewItems&catid=2')."'>".$subcat->category."</a>";   
}
}
$end = dircron_get('oneitem:count');
for ($i = 0; $i < $end; $i++) 
{
?>
<div class="itemslist">
    <a style="font-size: 16px;" href="<?php echo JRoute::_('index.php?option=com_directcron&view=items&task=oneitem&id='.dircron_get('oneitem:id'));?>"><?php echo dircron_get('oneitem:item');?></a>
    <br />
    <a href="<?php echo JRoute::_('index.php?option=com_directcron&view=items&task=oneitem&id='.dircron_get('oneitem:id'));?>">
    <?php echo dircron_imageHandler('image', false, dircron_get('oneitem:item'), "style='max-height: 220px; width: 150px; float: right;'");?></a><?php 
          echo $this->formatt->shorten_pr(dircron_get('oneitem:description'), 20);?>

<div style="clear:both"></div>
</div>
<?php
dircron_shift('oneitem');
}
?>
<div style="clear:both"></div>
<p><?php echo $this->pagination; ?></p>
 