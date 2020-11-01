<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');
include_once('fieldsinterface.php');
include_once(JPATH_COMPONENT.'/libraries/fields.php');
include_once(JPATH_COMPONENT.'/libraries/imageupload.php');
class dccrModelFieldsFetch extends JModelLegacy implements fieldsFetch
{
    public function __construct() { parent::__construct(); }
    public function getFieldValues($id, & $params)
    {
        if (!is_numeric($id)) return;
        $useCache = $params->get('com_cache');
        $cacheLast = $params->get('com_cachelast');
        $cacheLast = $cacheLast * 3600;
        if ($useCache) {
            $cache = new Cache($params->get('com_cache_debug'));
            $cache->setdirUsingID($id);
            $data = $cache->Fetch('extra_fields_'.$id);
            if ($data) { $thefields = $data[0];
            $thefieldstypes = $data[1]; 
            return array($thefields, $thefieldstypes); }
        }
        $values = $this->getItemFields($id, $params);
        if (!isset($values) || !is_array($values)) return;
        $dcron = CronDb::getInstance( 'crondb');
        $thefields = array();
        $thefieldstypes = array();
        foreach  ($values as $val) { $thefieldstypes[$val->name] = $val->type; }
        foreach ($values as $val)
        {
            if (is_numeric($val->id))
            {
                $rows = $this->gettheValue( $dcron, $id, $val->id);
                if ($rows !== false)
                { foreach ($rows as $rw) { $thefields[$val->name] = $rw->value; } } 
        } }
       if ($useCache) {
           $extras[0] = $thefields;
           $extras[1] = $thefieldstypes;
           $cache->Push_Cache('extra_fields_'.$id, $extras, $cacheLast);
           //$item = $cache->Obtain($query, $cacheLast);
       }
        return array($thefields, $thefieldstypes);
    }
    public function recordNewData($post)
    {
         $item = JRequest::getVar('item','', 'post');
         if (trim($item) == '') { JError::raiseWarning(500, JText::_('DIRECTCRON_NOITEM')); return;}
         $drcFields = DRCFields::getInstance('ddrcfields');
         $dcron = CronDb::getInstance( 'crondb');
         return $drcFields->recordNewData($post, $dcron);
    }
    public function getFields($id, $searchall = false)
    {
        if (!is_numeric($id)) return false;
        $categories = $this->getCategories($this->dcron, $id);
        $published = '';
        if ($searchall === true) $published = 'b.published = 1 AND';
        if ($categories === false || !is_array($categories)) return false;
        else $query = "SELECT a.name, a.id, a.type, a.category, a.published, b.ordering FROM #__directcron_fields a LEFT JOIN #__directcron_fields_settings b ON a.id = b.field_id WHERE $published a.category IN (".implode(',', $categories).") ORDER BY ORDERING ASC"; 
        $fields = $this->dcron->getRows($query);
        if ($this->dcron->getRowsAffected() > 0 ) return $fields;
        else return false;
    }
    private function gettheValue(CronDb &$dcron, $itemid, $fieldid)
    {
        $query = "SELECT value FROM #__directcron_field_values WHERE itemid = $itemid AND field = $fieldid LIMIT 1";
        $values = $dcron->getRows($query);
        if ($dcron->getRowsAffected() > 0 ) return $values;
        else return false;
    }
    public function getItemFields($id)
    { 
        if (!is_numeric($id)) return;
        $query = "SELECT * FROM #__directcron_items WHERE id = $id AND published = 1";
        $dcron = CronDb::getInstance( 'crondb');
        $published = '';
        $categories = $this->getCategories($dcron, $id);
        if ($categories === false) return false;
        
        else $query = "SELECT a.name, a.id, a.type, a.category, b.ordering FROM #__directcron_fields a LEFT JOIN #__directcron_fields_settings b ON a.id = b.field_id WHERE $published a.category IN (".implode(',', $categories).") ORDER BY ORDERING ASC"; 
        $fields = $dcron->getRows($query);
        if ($dcron->getRowsAffected() > 0 ) return $fields;
        else return false; 
        //$cache = new Cache();
        //$cache->setdirUsingID($id);
        //$fields = $cache->Obtain($query);
        /*if (!$fields) 
        {  
            echo "getting data from cache, for getitemfields";
             
            //$cache->Push_Cache($query, $fields);
        }
        if ($dcron->getRowsAffected() > 0 ) return $fields;
        else return false;  
       */
    }
    private function getCategories(CronDb &$dcron, $id)
    {
         $query = "SELECT category FROM #__directcron_items WHERE id = $id AND published = 1";
         $result = array();
         $i = 0;
         
         $result[$i] = $dcron->getOneValue($query);
         if (! is_numeric($result[$i]) ) return false;
         while (isset($result[$i]) && is_numeric($result[$i]))
         {
             $query = "SELECT parentcategory FROM #__directcron_categories WHERE id = ".$result[$i]." AND published = 1 AND parentcategory != 0 LIMIT 1";
             $i++;
             $res = $dcron->getOneValue($query);
             if (is_numeric($res) && !in_array($res, $result)) $result[$i] = $res;
             if ($i == 100) break;
         }
         return $result;
    }
}