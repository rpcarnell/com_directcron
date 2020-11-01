<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<br /><div class="row-fluid">
			<div id="cpanel" class="col-cm-12">
                            <div class="col-cm-3"><?php echo $this->addIcon('configuration.gif','index.php?option=com_directcron&view=settings', JText::_('COM_DIRECTCRON_TOOLBAR_SETTINGS'));?></div>
                            <div class="col-cm-3"><?php echo $this->addIcon('template.gif','index.php?option=com_directcron&view=layout', JText::_('COM_DIRECTCRON_LAYOUT_SETTINGS'));?></div>
                             <div class="col-cm-3">   <?php echo $this->addIcon('category.gif','index.php?option=com_directcron&view=category', JText::_('COM_DIRECTCRON_CATEGORY'));?></div>
				<div class="col-cm-3"><?php echo $this->addIcon('file_add.gif','index.php?option=com_directcron&view=addfields', JText::_('COM_DIRECTCRON_ADDFIELDS'));?></div>
				<div class="col-cm-3"><?php echo $this->addIcon('category_items.gif','index.php?option=com_directcron&view=catgdata', JText::_('COM_DIRECTCRON_CATEGORY_DATA'));?></div>
                            <div class="col-cm-3">   <?php echo $this->addIcon('about.gif','index.php?option=com_directcron&view=about', JText::_('COM_DIRECTCRON_ABOUT'));?></div>
				 
			</div>

</div>           
