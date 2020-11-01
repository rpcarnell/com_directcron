<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class drcViewItems extends JViewLegacy
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
             $doc = JFactory::getDocument();//$doc = new JDocumentFeed();
             $app		= JFactory::getApplication();
             $doc->link = JRoute::_('index.php');
              $r = 0;
             // print_r($this->items);
              foreach ($this->items as $it)
{
                   $itemFeed 				= new JFeedItem();
			$itemFeed->title		= $it->item;
			$itemFeed->link			=JRoute::_('index.php?option=com_directcron&amp;view=items&amp;task=oneitem&amp;id='.$it->id);
			$itemFeed->date			= $date;
			$itemFeed->category		= $item->category_title;
			$itemFeed->author		= "mydvdtrader.com";//redacron warning. This needs to be fixed
                        $default_thumb = unserialize($it->image); 
	     $src = str_replace('_thumbnails/', '_thumbnails/tn_', JURI::root().'images'.DS.$default_thumb['thumbnail']);  
             //alt="<?php echo $row['titolo']" width="35" height="50" style="border-left : 1px solid #fef; border-top : 1px solid #fef; border-right: 1px solid #eee; border-bottom: 1px solid #ddd; border-right-style: outset; border-bottom-style: outset">
	    
                        $itemFeed->description		= "<img src='$src' alt='$it->item'> ".$it->description;
?>

<?php $doc->addItem($itemFeed);
             }
             
           $doc->render(); 
   
        }
        }
?>
