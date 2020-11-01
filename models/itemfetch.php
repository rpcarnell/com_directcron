<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');
class dccrModelItemFetch extends JModelLegacy
{
    public function __construct() { parent::__construct(); }
    public function getAllCategories($orderBy = 1)
    {
        $order = ($orderBy == 2) ?  "ORDER BY id DESC" : "ORDER BY category";
        $query = "SELECT * FROM #__directcron_categories WHERE published = 1 $order";
        $dcron = CronDb::getInstance( 'crondb');
        $categories = $dcron->getRows($query);
        if ($dcron->getRowsAffected() > 0 ) return $categories;
        else return false;
    }
    public function getCategoryData($id)
    {
        $drcc = DRCCategories::getInstance('ddrcateg');
        $row = $drcc->getCategoryInfo($id);
        $cparams = class_exists('JParameter') ? new JParameter($row->params) : new JRegistry($row->params);
        $row->params = & $cparams;  
        return $row;
    }
    public function getItems($catid, & $params)
    {
        $drcc = new DRCCategories();
        list($rows, $limitstart, $pagination) = $drcc->getItemsbyID($catid, $params);
        return array($rows, $limitstart, $pagination);
    }
    public function getIntroItems(& $params, $type='html')
    {
        $drcc = new DRCCategories();
        list($rows, $limitstart, $pagination) = $drcc->getItemsbyParams($params, $type);
        return array($rows, $limitstart, $pagination);
    }
    public function getOneItem($id, & $params)
    {
        $query = "SELECT * FROM #__directcron_items WHERE id = $id AND published = 1 LIMIT 1";
        //$useCache = $params->get('com_cache');
        //$cacheLast = $params->get('com_cachelast');
       // echo "use Cache is $useCache $cacheLast ";
        /*if ($useCache) {
           $cache = new Cache($params->get('com_cache_debug'));
           $cache->setdirUsingID($id);
           $item = $cache->Obtain($query, $cacheLast);
           $item = $item[0];
        }
        else*/
        {
            $dcron = CronDb::getInstance('crondb');
            $item = $dcron->getRow($query);
        }
        return $item;
    }
    public function getSubCategories($id)
    {
        if (!is_numeric($id)) return false;
        $query = "SELECT * FROM #__directcron_categories WHERE parentcategory = $id AND published = 1";
        $dcron = CronDb::getInstance( 'crondb');
        $items = $dcron->getRows($query);
        if ($dcron->getRowsAffected() > 0 ) {return $items;}
        else return false;
        
    }
}
?>

