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

class DirectcronViewAbout extends JView
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
              JToolBarHelper::divider();
            JToolBarHelper::help( 'groups.html', true );
            JToolBarHelper::spacer();
        parent::display( $tpl );

 
	}
}
?>