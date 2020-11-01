<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class DirectcronViewCategory extends JViewLegacy
{
    
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{   
                 if (version_compare(JVERSION, '1.6.0', 'ge'))
        {
            jimport('joomla.form.form');//redacron warning, this stuff isn't mine
            $form = JForm::getInstance('categoryForm', JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'forms'.DS.'categories.xml');
            $values = array('params' => json_decode($this->category->params));
            $form->bind($values);
            $inheritFrom = (isset($values['params']->inheritFrom)) ? $values['params']->inheritFrom : 0;
        }
        else
        {
            $form = new JParameter('', JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'forms'.DS.'categories.xml');
            $form->loadINI($this->category->params);
            $inheritFrom = $form->get('inheritFrom');
        }
          
        $this->assignRef('form', $form);
                //Disabled for Super Administrator
		JToolBarHelper::save('save');//    JToolBarHelper::save('saveupdate', 'Save Log');
		JToolBarHelper::divider();
               
                JToolBarHelper::apply('applychanges', 'Apply');
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		JToolBarHelper::spacer();
                
                jimport('joomla.html.pane');
		// $pane	=& JPane::getInstance('sliders');
                //   echo "russia 7"; exit;
		$groups		= $this->get( 'Groupsinfo' );
		$community	= $this->get( 'Communityinfo' );
             // 
                $this->assignRef( 'groups'		, $groups );
		$this->assignRef( 'community'	, $community );
		$this->assignRef( 'pane'		, $pane );
                $this->assign( 'form'		, $form );
		parent::display( $tpl );
}
}
?>