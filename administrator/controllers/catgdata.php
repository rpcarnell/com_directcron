<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
$path = str_replace('administrator/', '', JPATH_COMPONENT);
include_once($path.DS.'libraries/framework.php');
include_once($path.DS.'libraries/fields.php');
include_once($path.DS.'libraries/categories.php');
include_once($path.DS.'libraries/imageupload.php');
include_once($path.DS."libraries".DS."settings.php");    
class DirectcronControllercatgdata extends DirectCronController
{
    function __construct() { parent::__construct(); }
    function display($cid = 0)
    {    
        
        $dcron = new CronDb();
        $viewName	= JRequest::getCmd( 'view' , 'directcron' );
        if ($cid == 0) $cid	= JRequest::getCmd( 'cid' ,0 );
        $views = array('about');
        
        if (!in_array($viewName, $views)) $viewName = 'catgdata';
        $document = & JFactory::getDocument();
        $document->addStyleSheet(JURI::base(true).'/components/com_directcron/css/styles.css');
        $document->addScript(str_replace('administrator', '', JURI::base(true)).'components/com_cronframe/thirdparty/javascripts/cronframe.jquery.js');
        $document->addScript(JURI::base(true).'/components/com_directcron/javascripts/addfields.js' );
        $js = "var tasksURL = '".JURI::root()."/administrator/';";
        $document->addScriptDeclaration($js);
        $modeltouse = &$this->getModel ( 'fieldsFetch', 'dccrModel');
        $modeltouse_2 = &$this->getModel ( 'categories', 'dccrModel');    
        $values = $modeltouse_2->getCategories();
       
        if ((int)$cid == 0) { list($ciddata, $limitstart, $pagination) = $modeltouse->getAllItems(); /*$cid = $values[0]->id;*/ }
        else $ciddata = $modeltouse->getCategoryData($cid);
        $viewType = 'html'; 
        $view = &$this->getView($viewName, $viewType);
        $view->assign('categories', $values);
        $view->assign('ciddata', $ciddata);
        $view->assign('pagination', $pagination);
        $view->assign('limitstart', $limitstart);
        $search  = JRequest::getVar('search', '', 'cmd', 'string');
        $view->assign('search', $search);
        $view->display();
  }
   function apply()
   {
       $this->save(false);
       $id = JRequest::getVar('id', 0, 'cmd');
       $this->editItem($id);     
   }
   function unpublish()
   {
       $cid = JRequest::getVar('cid', 0, 'cmd');
       $cid = $cid[0];
       if (!is_numeric($cid) || $cid == 0) return;
       $dcron = new CronDb(); 
       $query = "UPDATE #__directcron_items SET published = 0 WHERE id = $cid LIMIT 1";
       $dcron->update($query);
       $this->display();
   }
   function publish()
   {
       $cid = JRequest::getVar('cid', 0, 'cmd');
       $cid = $cid[0];
       if (!is_numeric($cid) || $cid == 0) return;
       $dcron = new CronDb();
       $query = "UPDATE #__directcron_items SET published = 1 WHERE id = $cid LIMIT 1";
       $dcron->update($query);
       $this->display();
   }
   function cancel()
   {
      $this->display();
   }
   function save2new()
   {
       $this->save(false);
       $this->newItem();
   }
   function save($direct = true)
   {
       $post = $_POST;
       $modeltouse = &$this->getModel ( 'fieldsFetch', 'dccrModel');
       if ($post['add_data'] == 1) { $id = $modeltouse->recordNewData($post); 
           $app = JFactory::getApplication();
           $app->redirect('index.php?option=com_directcron&view=catgdata&task=editItem&cid='.$id);
       }
       else { $modeltouse->editData($post); }
       if ($direct === true) {$this->display($id);}
   }
   function addItem()
   {
       $viewName	= JRequest::getCmd( 'view' , 'directcron' ); 
       $cid	= JRequest::getCmd( 'id' ,0, 'cmd');
       echo "additem<-------------";
       /*if (!in_array($viewName, $views))*/ $viewName = 'catgdata';
       $viewType = 'html_2'; 
        $modeltouse_2 = &$this->getModel ( 'categories', 'dccrModel');
        $modeltouse = &$this->getModel ( 'fieldsFetch', 'dccrModel');
       // $ciddata = $modeltouse->getItemFields($cid);
        $extrafields = $modeltouse->getFields($cid);//error!!!!!!!, this needs to be fixed <-*****************************************
        $fieldvalues = array();
        if (is_array($extrafields)) { foreach ($extrafields as $extrafield)
        {
            $fielddata[$extrafield->id] = $extrafield;
            $getfieldval = $modeltouse->getFieldValues($extrafield->id, $cid);//only one set of values, since there is a LIMIT 1
            /*if ($getfieldval !== false) {$fieldvalues[$fvl] = $getfieldval;*/
            $extrafield->givenvalue = (($getfieldval) && isset($getfieldval->value)) ? $getfieldval->value : false ;
           
        }}
        $values = $modeltouse_2->getCategories();
        $ciddata = '';
            $dispatcher = JDispatcher::getInstance();
            $view = &$this->getView($viewName, $viewType);
            $view->assign('dispatcher', $dispatcher);
            $view->assign('itemdata', $ciddata);
            $view->assign('categories', $values);
            $view->assign('add_data', 1);
            $view->assign('cid', $cid);
            $view->assign('extrafields', $fielddata);
            $view->assign('fieldvalues', $fieldvalues);
            $view->setLayout('viewitem');
            $view->display();
   }
   function editItem($id = '')
   {
       $cid = JRequest::getVar('cid', 0, 'cmd');
       $cid = (is_array($cid)) ?  $cid[0] :  $cid;
       $id = (is_numeric($id)) ? $id : $cid;
        if (!is_numeric($id) || $id == 0) { echo "ERROR - IMPROPER ID"; return;}
        $dcron = new CronDb();
        
        $viewName	= JRequest::getCmd( 'view' , 'directcron' );
        $viewName = 'catgdata';
        $viewType = 'html_2';
        $document = & JFactory::getDocument();
        $document->addStyleSheet(JURI::base(true).'/components/com_directcron/css/styles.css');
        $document->addScript(str_replace('administrator', '', JURI::base(true)).'components/com_cronframe/thirdparty/javascripts/cronframe.jquery.js');
        $document->addScript(JURI::base(true).'/components/com_directcron/javascripts/addfields.js' );
        $js = "var tasksURL = '".JURI::root()."/administrator/';";
        $document->addScriptDeclaration($js);
        $modeltouse = &$this->getModel ( 'fieldsFetch', 'dccrModel');
        $ciddata = $modeltouse->getItemFields($id);
        $extrafields = $modeltouse->getFields($ciddata->category);
        $fvl = 0;
        $fieldvalues = array();
        foreach ($extrafields as $extrafield)
        {
            $fielddata[$extrafield->id] = $extrafield;
            $getfieldval = $modeltouse->getFieldValues($extrafield->id, $id);//only one set of values, since there is a LIMIT 1
            /*if ($getfieldval !== false) {$fieldvalues[$fvl] = $getfieldval;*/
            $extrafield->givenvalue = (($getfieldval) && isset($getfieldval->value)) ? $getfieldval->value : false ;
           
        }
        $jj = 0;
        
        
        $modeltouse_2 = &$this->getModel ( 'categories', 'dccrModel');    
        $values = $modeltouse_2->getCategories();
        if ($dcron->getRowsAffected() > 0 ) {
            $viewType = 'html_2'; 
             $view = $this->getView($viewName, $viewType);
            
            $view->assign('itemdata', $ciddata);
             
            $settings = DRCSettings::getInstance( 'drcsettings' );
           
            $files = $settings->getDirectoryFiles();
           
            $view->assign('files', $files);
            $view->assign('categories', $values);
            $view->assign('extrafields', $fielddata);
            $view->assign('fieldvalues', $fieldvalues);
            $view->assign('add_data', 0);
            $view->setLayout('viewitem');
            $view->assign('cid', $ciddata->category);
            JPluginHelper::importPlugin('directcron');
            $dispatcher = JDispatcher::getInstance();
            $view->assign('dispatcher', $dispatcher);
            $view->display();
             
        }
        else return false; 
        return;
   }
   function remove()
   {
       $cid = JRequest::getVar('cid', 0, 'cmd');
       if (is_array($cid))
       {
           $db	= JFactory::getDbo();
           foreach($cid as $id)
           {
               if(is_numeric($id) && $id > 0)
               {
                   $query = "DELETE FROM #__directcron_items WHERE id = $id LIMIT 1";
                   $db->setQuery($query);
                   $db->query();
               }
               
           }
           $this->display();
       }
       else return;
   }

}
?>

    