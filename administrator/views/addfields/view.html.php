<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the AlphaRegistration component
 *
 */

class DirectcronViewAddFields extends JViewLegacy
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
         //   JToolBarHelper::title( JText::_( 'EDIT GROUP' ).': '.$row->group.($superAdmin ? ' ['.JText::_('WRITE PROTECTED').']' : ''), 'lbgroups' );
  
		//Disabled for Super Administrator
	    JToolBarHelper::deleteList('Are you sure you want to delete this category?', 'remove');//    JToolBarHelper::save('saveupdate', 'Save Log');
	    JToolBarHelper::divider();
	    JToolBarHelper::publishList();
            JToolBarHelper::divider();
            JToolBarHelper::unpublishList();
            JToolBarHelper::divider();
	    JToolBarHelper::help( 'groups.html', true );
            
            JToolBarHelper::spacer();
           // jimport('joomla.html.pane');
	   // $pane	=& JPane::getInstance('sliders');
	    $groups		= $this->get( 'Groupsinfo' );
	    $community	= $this->get( 'Communityinfo' );
            $this->assignRef( 'groups'		, $groups );
	    $this->assignRef( 'community'	, $community );
	    $this->assignRef( 'pane'		, $pane );
	    parent::display( $tpl );
}
 
}
?>