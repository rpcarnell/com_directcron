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

class DirectcronViewHome extends JViewLegacy
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
         //   JToolBarHelper::title( JText::_( 'EDIT GROUP' ).': '.$row->group.($superAdmin ? ' ['.JText::_('WRITE PROTECTED').']' : ''), 'lbgroups' );

		//Disabled for Super Administrator
		 
               jimport('joomla.html.pane');
                JToolBarHelper::divider();
             JToolBarHelper::help( 'groups.html', true );
             JToolBarHelper::spacer();
		
 
		$groups		= $this->get( 'Groupsinfo' );
		$community	= $this->get( 'Communityinfo' );

		$this->assignRef( 'groups'		, $groups );
		$this->assignRef( 'community'	, $community );
		//$this->assignRef( 'pane'		, $pane );
		parent::display( $tpl );

	  


}
function setToolBar()
	{

		// Set the titlebar text
		JToolBarHelper::title( JText::_( 'CC JOMSOCIAL' ), 'community' );
	}
function addIcon( $image , $url , $text , $newWindow = false )
	{
		$lang		= JFactory::getLanguage();
		
		$newWindow	= ( $newWindow ) ? ' target="_blank"' : '';
?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $url; ?>"<?php echo $newWindow; ?>>
					<?php echo JHTML::_('image', JURI::root().DS.'administrator/components/com_directcron/icons/' . $image , NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
<?php
	}
}
?>