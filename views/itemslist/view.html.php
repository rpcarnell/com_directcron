<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class drcViewItemsList extends JViewLegacy
{
    function display($tpl = null)
    {
        $keywords = array();
        $description = array();
        for ($i = 0; $i < 20; $i++)
        {
            if (isset($this->items[$i]) && trim($this->items[$i]->item) != '') 
            { 
                $items_2 = explode(' ', $this->items[$i]->item);
                $description[$i] = trim($this->items[$i]->item);
                foreach ($items_2 as $it)
                {
                   $it = preg_replace("/\W/", '', $it);
                   if ($it == '' || is_numeric($it)) continue;
                   if (! in_array($it, $keywords)) $keywords[$i] = $it;
                }
            } else continue;
        }
        $description = implode(', ', $description);
        $title = $this->params->get('dircron_frontpagetitle');
        $title = ($title == '') ? 'Items' : $title;
        $this->formatt->setMetaData($title, $keywords, $description);
        $this->formatt->setPathway($title, '');
        $this->addTemplatePath($this->style->getTemplatePath());
        $this->setLayout('itemslist');
        parent::display($tpl);
    }
}
?>