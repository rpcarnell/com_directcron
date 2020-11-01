<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class drcViewItemsList extends JViewLegacy
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
             $doc = JFactory::getDocument();//$doc = new JDocumentFeed();
             $doc->link = JRoute::_('index.php');
             $title = $this->params->get('dirc_rss_title');
             $title = ($title == '') ? 'Items' : $title;
             $this->formatt->setPageTitle($title);
             foreach ($this->items as $it)
             {
                   $itemFeed = new JFeedItem();
		   $itemFeed->title = $it->item."..";
		   $itemFeed->link = JRoute::_('index.php?option=com_directcron&amp;view=items&amp;task=oneitem&amp;id='.$it->id);
		   $default_thumb = unserialize($it->image); 
	           $src = str_replace('_thumbnails/', '_thumbnails/tn_', JURI::root().'images'.DS.$default_thumb['thumbnail']);  
                   $itemFeed->description = "<img src='$src' alt='$it->item'> ".$it->description;
                   $doc->addItem($itemFeed);
             }
             $doc->render(); 
         }
}
?>
