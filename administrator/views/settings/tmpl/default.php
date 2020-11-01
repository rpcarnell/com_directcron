<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: edit16.php 2983 2011-11-10 14:02:23Z geraintedwards $
 * @package     JEvents
 * @copyright   Copyright (C)  2008-2009 GWE Systems Ltd
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.jevents.net
 */
defined('_JEXEC') or die('Restricted access');
//$version = JEventsVersion::getInstance();
$config = JPATH_ADMINISTRATOR . DS . 'components/com_directcron/config.xml';
				if (file_exists($config))
				{
                                       
					$layoutform = JForm::getInstance("com_directcron.config.layouts", $config, array('control' => 'jform', 'load_data' => true), true, "/config");
					$layoutform->bind($this->params);

					$fieldSets = $layoutform->getFieldsets();
                                        
                                }
                               
?>

<form action="index.php" method="post" name="adminForm" autocomplete="off" id="adminForm">

	<fieldset>
		<legend>
			<?php echo JText::_('DIRECTCRON_SETTINGS'); ?>
		</legend>
		



 
		<?php
              echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-Group', array('useCookie' => 1));
		//echo JHtml::_('tabs.start', 'config-tabs-_configuration', array('useCookie' => 1));
                 
		//$fieldSets = $this->form->getFieldsets();
                $fieldsets = array();
                $fieldsets[1] = 'Component Configuration';
                $fieldsets[2] = 'SEF/Performance Options';
                $fieldsets[3] = 'Permissions';
                $fieldsets[4] = 'RSS';
                $fieldsets[5] = 'Comments';
                $fieldsets[6] = 'Cache';
              
                $i = 1;
                
                
		foreach ($fieldSets as $name => $fieldSet)
		{  
                    $label =  $name;
                   
                    if ($name == "permissions")
			{
				continue;
			}
                        
                     echo JHtml::_('bootstrap.addTab','ID-Tabs-Group',  'tab1_id_'.$i, JText::_( $label));
			  
			//empty($fieldSet) ? $name : $fieldSet;
			//echo JHtml::_('tabs.panel', JText::_( $label), 'publishing-details');
                 
                        $html = array();
			$html[] = '<table width="100%" class="paramlist admintable">';
                        $pp = 1;
                        foreach ($layoutform->getFieldset($name) as $field)
			{ 
                       
                        $fieldsets2 = array();
                
                        $fieldsets2[1] = 'configuration';
                        $fieldsets2[2] = 'RSS';
                        $fieldsets2[3] = 'Technology';
			
				 $html[] = "<tr>";
				 $html[] = '<td width="25%" valign="top" class="paramlist_label"><span class="settingsLabel">'.JText::_($field->label).'</span><br /><span class="settingsDesc">'.JText::_($field->description).'</span></td>';
                                 $sr = '';
                                 if ( strip_tags($field->label) == 'DIRCRON_COMMENTEDITOR')
                                 {
                                     $sr .= "<select id='directcron_commenteditor' name='jform[directcron_commenteditor]'>";
                                     if (is_array($this->editors))
                                     {
                                         foreach ($this->editors as $editor)
                                         {
                                             $selected = ($this->params->get('directcron_commenteditor') == $editor->value) ? 'selected=selected' : '';
                                             $sr .= "\n<option $selected value='$editor->value'>".$editor->name."</option>";
                                         }
                                     }
                                     $sr .= "</select>";
                                     $html[] = '<td class="paramlist_value">'. $sr ."</td>";
                                 }
                                 elseif ( strip_tags($field->label) == 'DIRCRON_ITEMEDITOR')
                                 {
                                     $sr .= "<select id='directcron_itemeditor' name='jform[directcron_itemeditor]'>";
                                     if (is_array($this->editors))
                                     {
                                         foreach ($this->editors as $editor)
                                         {
                                             $selected = ($this->params->get('directcron_itemeditor') == $editor->value) ? 'selected=selected' : '';
                                             $sr .= "\n<option $selected value='$editor->value'>$editor->name</option>";
                                         }
                                     }
                                     $sr .= "</select>";
                                     $html[] = '<td class="paramlist_value">'. $sr ."</td>";
                                 }
                                 else $html[] = '<td class="paramlist_value">'.$field->input.' </td>';
				 
 
				$html[] = '</tr>';
                                
			}
           
			$html[] = '</table>';
                        
			echo implode("\n", $html);
                         echo JHtml::_('bootstrap.endTab');
                      
                        $i++;
                }
               
                echo JHtml::_('bootstrap.endTabSet');
			//echo JHtml::_('tabs.end');
		 
            ?>
            </fieldset>
    <input type="hidden" name="view" value="settings" />
    <input type="hidden" name="option" value="com_directcron" />
    <input type="hidden" name="task" value="" />
</form>    