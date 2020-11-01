<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
$path = str_replace('administrator/', '', JPATH_COMPONENT);
            include_once($path.DS.'libraries/framework.php');
    include_once($path.DS."libraries".DS."settings.php");   
//include_once($path.DS.'libraries/categories.php');
include_once($path.DS.'libraries/imageupload.php');
class DirectcronControllerCategory extends DirectCronController
{
    /**
	 * Constructor
	 **/
	function __construct() {  parent::__construct(); }
         
        function applychanges()
        {
           $cid = JRequest::getVar('catid', 0, 'cmd', 'int');
           if ($cid == 0) 
           {
               if (isset($_POST['add_data']) && $_POST['add_data'] == 1) {  $this->save(0, true, 'apply'); }
               return;
           }
           $this->save($cid); 
        }
        function new_category()
        { 
            $dcron = new CronDb();
            $query = "SELECT * FROM #__directcron_categories";
            $values = $dcron->getRows($query);
            jscssScripts::jsInclude('com_directcron','css/styles.css');
            jscssScripts::jsInclude('com_directcron','css/jquery-ui-1.10.2.custom.css');
            jscssScripts::jsInclude('com_directcron','javascripts/jquery-ui-1.10.2.custom.js');
            jscssScripts::jsInclude('com_directcron','javascripts/admindirect.js');
            $viewType = 'html_2'; 
            $settings = DRCSettings::getInstance( 'drcsettings' );
            $files = $settings->getDirectoryFiles();
            $view = &$this->getView('category', $viewType);
            $view->assign('files', $files);
            $view->assign('categories', $values);
            $view->assign('add_data', 1);//this will lead to save_2 instead of save
            $view->setLayout('editcategory');
            $view->display();
        }
        
        function save($cid = 0, $add = false, $condition = '')
        {
            if (isset($_POST['add_data']) && $_POST['add_data'] == 1 && $condition != 'apply')
            { 
                $condition = 'save';
                $add = true;
            }
            if ($cid == 0) { 
                $displaycid = 0;
                $cid = JRequest::getVar('catid', 0, 'cmd', 'int'); 
            }
            else $displaycid = $cid;
            if ($cid == 0 && $add === false) return;
            $dcron = new CronDb();
            $val = array();
            
            $publishedstatus = false;
            $notwanted = array('catid', 'view', 'option', 'task', 'boxchecked', 'add_data');
            $doc =  & upl_albumpics::getInstance('category', 'categoryimage');//the user may have decided to upload an image
                  list($directory, $picupl) = $doc->upload_pic($cid, str_replace(' ', '_', $_POST['category']));
                   if ($picupl) 
                    {
                        $postimage = array();
                        if (!empty($directory)) $dir = $directory.DS;
                        else $dir = '';
                        $postimage['image'] = $dir.$picupl;
                        $postimage['thumbnail'] = $dir.DS.'thumbnails'.DS.$picupl;
                        if ($postimage['image'] != '' && $postimage['thumbnail'] != '')
                        { $val['categoryimage'] = serialize($postimage);  }
                    }
            foreach ($_POST as $key => $value)
            {
                if (in_array($key, $notwanted)) continue;
                if ($key == 'category')
                {
                    if (trim($value) == '')
                    {
                        echo "<div class='warning'>".JText::_('CATGFIELDEMPTY')."</div>";
                        $this->display(0);//if display = 0, we go back to the basic category list
                        return;
                    }
                }
             
                if ($key == 'published')
                {
                    $val[$key] = ($value == 'on') ? 1 : 0;
                    $publishedstatus = true;
                    continue;
                }
                elseif ($key == 'params')
                {
                    $val[$key] = json_encode($_POST['params']);
                    continue;
                }
               $val[$key] = $value;
            }
            if ($publishedstatus === false) $val['published'] = 0;
            if ($add === false) { $val['modified'] = time();
            $user = &JFactory::getUser();
            $val['modified_by'] = $user->id;
            $dontscape[0] = 'published';
            $dontscape[1] = 'modified';
            $dontscape[2] = 'modified_by';
            $query = $dcron->buildQuery( 'UPDATE', '#__directcron_categories', $val, "WHERE id=$cid", $dontscape);
             
                $dcron->update($query);
            }
            else
            {
                 $val['date_added'] = time();
                 $val['added_by'] = $user->id;
                 $query = $dcron->buildQuery( 'INSERT', '#__directcron_categories', $val);
                 
                 $displaycid = $dcron->insert($query);  
                 if ($condition == 'save') $displaycid = 0;
            }
            $this->display($displaycid);//if display = 0, we go back to the basic category list
        }
        function publish()
        {
            $cid_array	= JRequest::getVar('cid', '', 'cmd', 'array');
            if (isset($_POST['cid'])) {  
            $dcron = new CronDb();
            foreach ($cid_array as $cid)
            { if (is_numeric($cid))
                {
                      $values['published'] = 1;
                      $dontscape[0] = 'published';
                      $query = $dcron->buildQuery( 'UPDATE', '#__directcron_categories', $values, "WHERE id=$cid", $dontscape);
                      $dcron->update($query);
                } } }
                $this->display();
        }
        function unpublish()
        {
            $cid_array	= JRequest::getVar('cid', '', 'cmd', 'array');
            if (isset($_POST['cid'])) {  
            $dcron = new CronDb();
            foreach ($cid_array as $cid)
            { if (is_numeric($cid))
                {
                      $values['published'] = 0;
                      $dontscape[0] = 'published';
                      $query = $dcron->buildQuery( 'UPDATE', '#__directcron_categories', $values, "WHERE id=$cid", $dontscape);
                      $dcron->update($query);
                } } }
                $this->display();
        }
        function remove()
        {
            $cid_array	= JRequest::getVar('cid', '', 'cmd', 'array');
            if (isset($_POST['cid'])) {  
            $dcron = new CronDb();
            foreach ($cid_array as $cid)
            { if (is_numeric($cid))
                {
                      $query = "DELETE FROM #__directcron_categories WHERE id=$cid";
                      $dcron->update($query);
                } } }
                $this->display();
        }
        function editcategory()
        {  
            $cid_array	= JRequest::getVar('cid', '', 'cmd', 'array'); 
            if (isset($_POST['cid'])) {  if (is_numeric($cid_array[0])) { $this->display( $cid_array[0] ); } }
        }
        function cancel()
        {
            $this->display();
        }
        function display($cid = 0)
        {    
        
            $dcron = new CronDb();
            $secure = new CronSecure();
            $viewName	= JRequest::getCmd( 'view' , 'directcron' );
             $search	= JRequest::getCmd( 'search' , '' );
             $views = array('about');
             if (!in_array($viewName, $views)) $viewName = 'category';
             $document = & JFactory::getDocument();
            
            $document->addStyleSheet(JURI::base(true).'/components/com_directcron/css/styles.css');
            $query = "SELECT * FROM #__directcron_categories";
            
            if ($cid == 0 && $search != '') { $query .= " WHERE category LIKE '%".$secure->protectSQL($search)."%'"; }
             
            $values = $dcron->getRows($query);
            if ((int)$cid > 0)
            {
                $query .= " WHERE id = ".(int)$cid." LIMIT 1";
                $category = $dcron->getRow($query);
                 
            }
            
            //if ($dcron->getRowsAffected() > 0 ) {
                $viewType = 'html';//$document->getType();
                if ((int)$cid > 0) {
         
                     jscssScripts::jsInclude('com_directcron','css/styles.css');
                     jscssScripts::jsInclude('com_directcron','css/jquery-ui-1.10.2.custom.css');
                     jscssScripts::jsInclude('com_directcron','javascripts/jquery-ui-1.10.2.custom.js');
                     jscssScripts::jsInclude('com_directcron','javascripts/admindirect.js');
                 $viewType = 'html_2'; }
                $view = &$this->getView($viewName, $viewType);
                if ((int)$cid > 0 ) { 
                    $settings = DRCSettings::getInstance( 'drcsettings' );
                    $files = $settings->getDirectoryFiles();
                     
                    $view->assign('files', $files);
                    $view->setLayout('editcategory');
                    $view->assign('category', $category);
                    $view->assign('add_data', 0);
                }
                $lists['search'] = '';
                $view->assign('lists', $lists);
               
                $view->assign('categories', $values);
               
                $view->display();
           // }
           // else return false; 
              
              return;
              
        }
        
}
?>
