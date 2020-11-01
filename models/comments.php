<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');
class dccrModelComments extends JModelLegacy 
{
    var $cronDb;
    public function __construct() { parent::__construct(); }
    public function getUserComments($userid)
    {
        if (!is_numeric($userid)) return;
        $this->cronDb = CronDb::getInstance( 'crondb');
    }
    public function getItemComments($itemid)
    {  
        if (!is_numeric($itemid)) return;
        $this->cronDb = CronDb::getInstance( 'crondb');
        $query = "SELECT * FROM #__directcron_comments WHERE item_id = $itemid ORDER BY id DESC LIMIT 20";
        $rows = $this->cronDb->getRows($query);
        return $rows;
    }
 }

?>

