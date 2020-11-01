<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class drcViewItems extends JView
{
	function display($tpl = null)//ifd tpl = 2, then the template will be default_2, and so on
	{
            //$style = new DRCStyles();
            $this->style->setPathway(DRCCATEGORY);
            $this->style->setPageTitle(DRCCATEGORY);
            $keywords = array();
            //echo "<hr/>";
            for ($i = 0; $i < 20; $i++)
            {
                if (isset($this->items[$i]) && trim($this->items[$i]->item) != '') { $keywords[$i] = $this->items[$i]->item; }
                else continue;
            }
            $this->style->setMetaData(DRCCATEGORY, $keywords);
            $this->addTemplatePath($this->style->getTemplatePath());
           
            $this->setLayout($this->catgdata->templatefile);
            parent::display($tpl);
            
        }
}
?>