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

class DirectcronViewCatgData extends JViewLegacy
{
	function display($tpl = null)
	{  
              jimport('joomla.html.pane');
            JToolBarHelper::title( JText::_( 'EDIT GROUP' ));//.': '.$row->group.($superAdmin ? ' ['.JText::_('WRITE PROTECTED').']' : ''), 'lbgroups' );
            JToolBarHelper::addNew('additem');
            JToolBarHelper::divider();
            JToolBarHelper::checkin('edititem');
            JToolBarHelper::divider();
            
            JToolBarHelper::deleteList('Are you sure you want to delete this category?', 'remove');
             JToolBarHelper::divider();
            JToolBarHelper::help( 'groups.html', true );
            JToolBarHelper::spacer();
          
           
            
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