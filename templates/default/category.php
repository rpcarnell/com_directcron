<?php
$params = $this->catgdata->params;
if ($params->get('catAuthor',0)) $added_by = & JFactory::getUser($this->catgdata->added_by);
//************Show a category image
if ($params->get('catImage',0))
{  
    $default_thumb = unserialize($this->catgdata->categoryimage); 
    if ($default_thumb['image']) {
    if ($params->get('catimagesize', 'thumbnail') == 'thumbnail')
    {
        $default_thumb = $default_thumb['thumbnail'];
        $height = "height: 150px;";
    }
    else { 
        $default_thumb = $default_thumb['image']; 
        $height = '';
    }
?>
<div style="float: right">
    <a style="text-decoration: none;" href="<?php echo JRoute::_('index.php?option=com_directcron&view=items&task=viewItems&catid='.$this->catgdata->id);?>&format=fe‌ed&type=rss">RSS <img src="<?php echo Juri::base()."components/com_directcron/images/icons/rss.jpg"?>" alt="rss icon"/></a></div>
<p><?php echo "\n".dircron_get('category:category', '<b>'); ?><br /> 
<?php echo "\n".dircron_get('category:added_by'); ?>
</p>
<p><?php echo "\n".dircron_get('category:description', '<div>', "style='background: #eee; padding: 5px;'");?></p>
<?php
      if ( $params->get('categoryHits', 0) ) 
      {
                echo "\n<p>Visited: ";
                echo "\n".dircron_get('category:visited', '<i>', "style='background: #def; padding: 5px;'");
                echo "\n</p>";
      }
      echo "\n".dircron_get('category:categoryimage',  '<img>', "class='categoryPic'");
      $end = dircron_get('subcategory:count');
      //Now let's deal with the subcategories: 
      ?>
<ul id='subcategoriestList'>
      <?php
      for ($i = 0; $i < $end; $i++) 
      {
          
          echo "<li>";
          if ( $params->get('showSubCatgTitle', 0) ) { echo "\n".dircron_get('subcategory:category', '<p>'); }
          if ( $params->get('subcatImage', 0) ) { echo "\n".dircron_get('subcategory:categoryimage',  '<img>'); }
          echo "\n".dircron_get('subcategory:description', '<p>'); 
          dircron_shift('subcategory');
          echo "</li>";
      }
      ?>
</ul>
<br />
<?php } }//end of category image option


 
$end = dircron_get('oneitem:count');
      for ($i = 0; $i < $end; $i++) {

    
?>
<div class='itemsList'>
     
<a href="<?php echo JRoute::_('index.php?option=com_directcron&view=items&task=oneitem&id='.dircron_get('oneitem:id'));?>"><?php echo dircron_get('oneitem:item');?></a><br />
 
<br />
<div><?php dircron_imageHandler('image', false, dircron_get('oneitem:item'), "style='float: left;'"); ?> 
<?php echo $this->formattask->shorten_pr(dircron_get('oneitem:description'), $params->get('shorten_description', 50))?>
<?php if ($params->get('moreend_description',0)):?>
<a href='<?php echo JRoute::_('index.php?option=com_directcron&view=items&task=oneitem&id='.dircron_get('oneitem:id'));?>'>More</a>
<?php endif; ?>
</div>
<div class='clear'></div>
</div>
<?php 
dircron_shift('oneitem');
} 
?>
<div style="padding: 5px; margin: 5px;">
<?php
echo $this->pagination;
?>
</div>