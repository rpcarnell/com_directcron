<?php
 
/**
 * JEvents Locations Component for Joomla 1.5.x
 *
 * @version     $Id: jevinfo.php 1331 2010-10-19 12:35:49Z geraintedwards $
 * @package     JEvents
 * @copyright   Copyright (C) 2008-2009 GWE Systems Ltd
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.jevents.net
 */
// Check to ensure this file is included in Joomla!

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('spacer');

// Must load admin language files
$lang = & JFactory::getLanguage();
$lang->load("com_directcron", JPATH_ADMINISTRATOR);

/**
 * JEVMenu Field class for the JEvents Component
 *
 * @package		JEvents.fields
 * @subpackage	com_banners
 * @since		1.6
 */
class JFormFieldDIRCInfo extends JFormFieldSpacer
{

	/**
	 * The form field type.s
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'DIRCInfo';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getInput()
	{
 
		// Must load admin language files
		$lang = & JFactory::getLanguage();
		$lang->load("com_directcron", JPATH_ADMINISTRATOR);

		$node = $this->element;
		$value = $this->value;
		$name = $this->name;
		$help = $node['help'];
                echo $this->help();
		/*if ((!is_null($help)) && (version_compare(JVERSION, '1.6.0', ">=")))
		{
			if (is_object($help))
				$help = (string) $help;
			$help = ( (isset($help)) && (strlen($help) <= 0)) ? null : $help;
		}
		if (!is_null($help))
		{
			$parts = explode(",", $value);
			$helps = explode(",", $help);
			foreach ($parts as $key => $valuepart)
			{
				$help = $helps[$key];
				list($helpfile, $varname, $part) = explode("::", $help);
				$lang = & JFactory::getLanguage();
				$langtag = $lang->getTag();
				if (file_exists(JPATH_COMPONENT_ADMINISTRATOR . '/help/' . $langtag . '/' . $helpfile))
				{
					$jeventHelpPopup = JPATH_COMPONENT_ADMINISTRATOR . '/help/' . $langtag . '/' . $helpfile;
				}
				else
				{
					$jeventHelpPopup = JPATH_COMPONENT_ADMINISTRATOR . '/help/en-GB/' . $helpfile;
				}
				include($jeventHelpPopup);
				$help = $this->help($$varname, $part);
				$parts[$key] = JText::_($valuepart) . $help;
			}
			$value = implode(", ", $parts);
		}*/
		return "<strong style='color:#993300'>" . JText::_($value) . "</strong>";

	}

	/**
	 * Creates a help icon with link to help information as onclick event
	 *
	 * if $help is url, link opens a new window with target url
	 * if $help is text, text is shown in a sticky overlib window with close button
	 *
	 * @static
	 * @param	$help		string	help text (html text or url to target)
	 * @param	$caption	string	caption of overlib window
	 * @return				string	html sting
	 */
	public function help($help = 'help text', $caption = '')
	{   
            $compath = JURI::root() . 'administrator/components/';
		$imgpath = $compath . '/assets/images';
            $str = '<img border="0" style="float: none; vertical-align:bottom; cursor:help;" alt="' . JText::_('JEV_HELP') . '"'
				. ' title="' . JText::_('JEV_HELP') . '"'
				. ' src="' . $imgpath . '/help_ques_inact.gif" />';
                return '<a href="#" title="That&apos;s what this widget is">'.$str.'</a>';
		

		if (empty($caption))
			$caption = '&nbsp;';

		static $counthelps = 0;
		$counthelps++;
		
		if (substr($help, 0, 7) == 'http://' || substr($help, 0, 8) == 'https://')
		{
			//help text is url, open new window
			$onclick_cmd = "window.open(\"$help\", \"help\", \"height=700,width=800,resizable=yes,scrollbars\");return false";
		}
		else
		{
			// help text is plain text with html tags
			// prepare text as overlib parameter
			// escape ", replace new line by space
			//$help = htmlspecialchars($help, ENT_QUOTES);
			//$help = str_replace('&quot;', '\&quot;', $help);
			$help = addslashes(str_replace("\n", " ", $help));

			$onclick_cmd = "SqueezeBox.initialize({});SqueezeBox.setOptions(SqueezeBox.presets,{'handler': 'iframe','size': {'x': 400, 'y': 500},'closeWithOverlay': 0});SqueezeBox.setContent('clone', $('helpdiv".$counthelps."'));";

		}

		// RSH 10/11/10 - Added float:none for 1.6 compatiblity - The default template was floating images to the left
		$str = '<img border="0" style="float: none; vertical-align:bottom; cursor:help;" alt="' . JText::_('JEV_HELP') . '"'
				. ' title="' . JText::_('JEV_HELP') . '"'
				. ' src="' . $imgpath . '/help_ques_inact.gif"'
				//. ' onmouseover="this.src="' . $imgpath . '/help_ques.gif'.'" '
				//. ' onmouseout="this.src="' . $imgpath . '/help_ques_inact.gif'.'" '
				. ' onclick="' . $onclick_cmd . '" /><div style="display:none;"><div id="helpdiv'.$counthelps.'" >'.$help.'</div></div>';

		return $str;

	}

}

