<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class drcViewcatgList extends JView
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
            $style = new DRCStyles();
            $style->setPathway(DRCCATEGORY, CATEGORIES);
            $style->setPageTitle(DRCCATEGORY);
            $keywords = array();
            for ($i = 0; $i < 20; $i++)
            {
                if (isset($this->categories[$i]) && trim($this->categories[$i]->category) != '') { $keywords[$i] = $this->categories[$i]->category; }
                else continue;
            }
            $style->setMetaData(DRCCATEGORY, $keywords);
            $this->addTemplatePath($style->getTemplatePath());
            $this->setLayout('main');
            parent::display($tpl);
        }
}
?>