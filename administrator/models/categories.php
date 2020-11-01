<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');
class dccrModelCategories extends JModelLegacy  //( 'itemFetch', 'dccr');
{
    var $dcron;
    public function __construct() { $this->dcron = new CronDb(); parent::__construct(); }
    public function getCategories()
    {
         $query = "SELECT * FROM #__directcron_categories ORDER BY category";
         $values = $this->dcron->getRows($query);
         return $values;
    }
}
?>
