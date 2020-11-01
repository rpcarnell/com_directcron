<?php
defined('_JEXEC') or die('Restricted access');
class DRCCategories
{
    var $dcron;
    static function getInstance( $here )
    {
        static $instances;
        if (!isset( $instances )) {$instances = array();}
        $signature = base64_encode( $here );
        if (empty($instances[$signature]))
        {
            $instance = new DRCCategories();
            $instances[$signature] = & $instance;
        }
        return $instances[$signature];
    }
    function __construct() {   $this->dcron = CronDb::getInstance( 'crondb'); }
    public function getAllCategories()
    {
        $query = "SELECT * FROM #__directcron_categories ORDER BY category";
        $rows = $this->dcron->getRows($query);
        return $rows;
    }
     public function getAllItems()//works better for search
    {
        $search  = JRequest::getVar('search', '', 'cmd', 'string');
        if ($search)  { $where = " WHERE description LIKE '%$search%' OR item LIKE '%$search%' "; } 
        else $where = '';
        $query = "SELECT count(*) as itemnum FROM #__directcron_items $where"; 
        $total = $this->dcron->getOneValue($query);
        if ( $total == false || $total == 0) { return false; }/*return $data;*/
        jimport('joomla.html.pagination');
        $limitstart = JRequest::getInt('limitstart');
        $limit = JRequest::getVar( "viewlistlimit", '10', 'get', 'int');
        $query = "SELECT * FROM #__directcron_items $where LIMIT $limitstart, $limit"; 
        $pagination = new JPagination($total, $limitstart, $limit);
        $rows = $this->dcron->getRows($query);
        return array($rows, $limitstart, $pagination->getPagesLinks());
    }
     public function getItemsbyID($catid, & $params)
    {
        if (!is_numeric($catid)) { echo "<p style='color: #a00; font-weight: bold;'>ERROR - non-numeric category for items<p>"; return; }
        $where = "WHERE category = $catid";
        $query = "SELECT count(*) as itemnum FROM #__directcron_items $where";  
        $total = $this->dcron->getOneValue($query);
        if ( $total == false || $total == 0) { return false; } 
        jimport('joomla.html.pagination');
        $limitstart = JRequest::getInt('limitstart');
        $limit = JRequest::getVar( "viewlistlimit", '10', 'get', 'int');
        $query = "SELECT * FROM #__directcron_items $where LIMIT $limitstart, $limit"; 
        $pagination = new JPagination($total, $limitstart, $limit);
        $useCache = $params->get('com_cache');
        $cacheLast = $params->get('com_cachelast');
        if ($useCache) 
        {
            $cache = new Cache($params->get('com_cache_debug'));
            $rows = $cache->Obtain($query);
        }
        else $rows = $this->dcron->getRows($query); 
        return array($rows, $limitstart, $pagination->getPagesLinks());
    }
    public function getItemsbyParams(& $params, $type='html')
    {
        $query = "SELECT count(*) as itemnum FROM #__directcron_items";  
        $total = $this->dcron->getOneValue($query);
        if ( $total == false || $total == 0) { return false; } 
        jimport('joomla.html.pagination');
        $app = JFactory::getApplication();
        $limitstart = $app->input->getCmd('limitstart', '0', 'get', 'int');
        //$limitstart = JRequest::getInt('limitstart', '0', 'get', 'int');
        if ($type=='feed') { $limit = $params->get('dirc_rss_count'); } 
        else $limit = $app->input->getCmd('viewlistlimit', '20', 'get', 'int');//JRequest::getVar('viewlistlimit', '20', 'get', 'int');
        $query = "SELECT * FROM #__directcron_items ORDER BY id DESC LIMIT $limitstart, $limit"; 
        $pagination = new JPagination($total, $limitstart, $limit);
        if ($type != 'feed')
        {
            $useCache = $params->get('com_cache');
            $cacheLast = $params->get('com_cachelast');
            if ($useCache) 
            {
                $cache = new Cache($params->get('com_cache_debug'));
                $rows = $cache->Obtain($query);
            }
            else $rows = $this->dcron->getRows($query);
        }
        else //the parameters for a feed are different
        {
            $useCache = $params->get('dirc_rss_cache');
            $cacheLast = $params->get('dirc_rss_cache_time');
            if ($useCache) 
            {
                $cache = new Cache();
                $rows = $cache->Fetch('rss_listItems');
                if (!$rows)
                {
                    $rows = $this->dcron->getRows($query);
                    $cache->Push_Cache('rss_listItems', $rows, $cacheLast);
                }
            }
            if ($params->get('dirc_rss_live_bookmarks'))
            {
                $this->liveBookmark($params);
            }
        }
        return array($rows, $limitstart, $pagination->getPagesLinks());
    }
    private function liveBookmark(& $params)
    {
        $Itemid = JRequest::getInt('Itemid', 0);
	$rssmodid = $params->get('com_rss_modid', 0);
       
	// do not use JRoute since this creates .rss link which normal sef can't deal with
	$rssLink = 'index.php?option=' . DIRC_COM_COMPONENT . '&amp;format=feed&amp;type=rss&amp;Itemid=' . $Itemid . '&amp;modid=' . $rssmodid;
	$rssLink = JUri::root() . $rssLink;

	if (method_exists(JFactory::getDocument(), "addHeadLink"))
	{
		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
		JFactory::getDocument()->addHeadLink($rssLink, 'alternate', 'rel', $attribs);
	}

	$rssLink = 'index.php?option=' . DIRC_COM_COMPONENT . '&amp;format=feed&amp;type=atom&amp;Itemid=' . $Itemid . '&amp;modid=' . $rssmodid;
	$rssLink = JUri::root() . $rssLink;
	//$rssLink = JRoute::_($rssLink);
	if (method_exists(JFactory::getDocument(), "addHeadLink"))
	{
		$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
		JFactory::getDocument()->addHeadLink($rssLink, 'alternate', 'rel', $attribs);
	}
    }
    public function getCategoryData($cid)
    {    
        if (!is_numeric($cid)) return;
        $query = "SELECT * FROM #__directcron_items WHERE category = $cid";
         $data = $this->dcron->getRows($query);
        if ($this->dcron->getRowsAffected() > 0 ) return $data;
        else return false;
    }
    public function getCategory($id)
    {
        if (!is_numeric($id)) return false;
        $query = "SELECT * FROM #__directcron_categories WHERE id = $id LIMIT 1";
        $rows = $this->dcron->getRow($query);
        return $rows;
    }
    public function getCategoryName($id)
    {
        if (!is_numeric($id)) return false;
        $query = "SELECT category FROM #__directcron_categories WHERE id = $id LIMIT 1";
        $rows = $this->dcron->getOneValue($query);
        return $rows;
    }
    public function getCategoryInfo($cid)
    {
        if (!is_numeric($cid)) return;
        $query = "SELECT * FROM #__directcron_categories WHERE id = $cid LIMIT 1"; 
        $data = $this->dcron->getRow($query);
        if ($this->dcron->getRowsAffected() > 0 ) return $data;
        else return false;
    }
   public function updateVisits($cid)
    {
        if ($this->visitedEarlier($cid)) { echo "<p>line 90 of categories, you visited this earlier</p>"; return;}
        $query = "UPDATE #__directcron_categories SET visited = visited + 1 WHERE id = $cid LIMIT 1"; 
        $this->dcron->update($query);
    }
    private function visitedEarlier($cid)
    { 
        $DRCLytics = DRCLytics::getInstance( 'DRCLytics' );
        $ip = $DRCLytics->get_user_ip();
        $cronDB = CronDb::getInstance( 'crondb');
        $updated = $DRCLytics->visitedBefore($cid, $ip,  $cronDB);
        if ($updated)
        {
            $day = 24 * 60 * 60;
            if ((time() - $updated) < $day) { return true; }
            else return false;
        }
        else return false;
    }
    public function getParentCategories($id)
    {
         $i = 0;
         if (!is_numeric($id)) return;
         $result[0] = $id;
         while (isset($result[$i]) && is_numeric($result[$i]))
         {
             $query = "SELECT parentcategory FROM #__directcron_categories WHERE id = ".$result[$i]." AND published = 1 AND parentcategory != 0 LIMIT 1";
             $i++;
             $res = $this->dcron->getOneValue($query);
             if (is_numeric($res)) $result[$i] = $res;
             if ($i == 100) break;
         }
         return $result;
    }
}
?>
