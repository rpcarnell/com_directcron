<?php
// Check to ensure this file is included in Joomla!
defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('radio');

class JFormFieldDIRCBoolean extends JFormFieldRadio
{
	/**
	 * The form field type.s
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'DIRCBoolean';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getOptions()
	{	
                // Load admin language file first:
		$lang =& JFactory::getLanguage();
		$lang->load("com_diretcron", JPATH_ADMINISTRATOR);
		$options = array ();
		$options[] = JHTML::_('select.option', 0, JText::_("DIRC_NO"));
		$options[] = JHTML::_('select.option', 1, JText::_("DIRC_YES"));

		return $options;

		
	}
}
