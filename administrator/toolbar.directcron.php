<?php
defined('_JEXEC') or die('Restricted access');
$view	= JRequest::getCmd('view','directcron');
$subViews['directcron']  = array(
            'directcron'			=> JText::_('COM_DIRECTCRON_TOOLBAR_HOME'),
            'settings'		=> JText::_('COM_DIRECTCRON_TOOLBAR_SETTINGS'),
            'layout'		=> JText::_('COM_DIRECTCRON_LAYOUT_SETTINGS'),
            'category'				=> JText::_('COM_DIRECTCRON_CATEGORY'),
            'addfields'			=> JText::_('COM_DIRECTCRON_ADDFIELDS'),
            'catgdata'			=> JText::_('COM_DIRECTCRON_CATEGORY_DATA'),
             'about'				=> JText::_('COM_DIRECTCRON_ABOUT')
    );
 			
$currentView    = '';
if(! array_key_exists($currentView, $subViews)) { $currentView    = 'directcron'; }
foreach( $subViews[$currentView] as $key => $val )
{
    $active	= ( $view == $key );
    JSubMenuHelper::addEntry( $val , 'index.php?option=com_directcron&view=' . $key , $active );
}
