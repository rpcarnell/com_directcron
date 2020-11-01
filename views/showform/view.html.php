<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class drcViewShowForm extends JView
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
            $style = new DRCStyles();
            $style->setPathway(DRCCATEGORY);
            $style->setPageTitle(DRCCATEGORY);
            $keywords = array();
            $keywords = $this->oneitem->item;
            $style->setMetaData(DRCCATEGORY, $keywords);
            $this->addTemplatePath($style->getTemplatePath());
            if (isset($this->oneitem['templatefile']) && trim($this->oneitem['templatefile']) != '') { $templatefile = $this->oneitem['templatefile']; }
            else  $templatefile = 'form'; 
            $this->setLayout($templatefile);
            jscssScripts::jsInclude('com_directcron', 'templates/'.$style->getTemplateDirectory()."/css/style.css");
            parent::display($tpl);
        }
}
?>