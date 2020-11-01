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
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
            
         //   JToolBarHelper::title( JText::_( 'EDIT GROUP' ).': '.$row->group.($superAdmin ? ' ['.JText::_('WRITE PROTECTED').']' : ''), 'lbgroups' );
  JToolBarHelper::title(JText::_('COM_CONTENT_PAGE_'.($checkedOut ? 'VIEW_ARTICLE' : ($isNew ? 'ADD_ARTICLE' : 'EDIT_ARTICLE'))), 'article-add.png');

		 
	 JToolBarHelper::apply('apply');
			JToolBarHelper::save('save');
			JToolBarHelper::save2new('save2new');
			JToolBarHelper::cancel('cancel');
                        JToolBarHelper::divider();
               JToolBarHelper::help('JHELP_CONTENT_ARTICLE_MANAGER_EDIT');
		
		JToolBarHelper::spacer();
            JToolBarHelper::addNew('edit');
           JToolBarHelper::checkin('editcategory');
            
            jimport('joomla.html.pane');
		//$pane	=& JPane::getInstance('sliders');
		// echo "hgdf";   print_r($_POST); exit; 
		$this->assignRef( 'pane', $pane );
		parent::display( $tpl );

	  


}
 
}
?>