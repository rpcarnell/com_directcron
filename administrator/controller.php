<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
$path = str_replace('administrator/', '', JPATH_COMPONENT);
include_once($path.DS.'libraries/framework.php');
            
class DirectCronController extends JControllerLegacy
{
    /**
	 * Constructor
	 **/
	function __construct() {  parent::__construct(); }
        function directMain()
        {
           
        }
        function savesettings()
        {
            print_r($_POST);
            $this->directMain();
        }
        function dircategories()
        {
            $dcron = new CronDb();
            $query = "SELECT * FROM #__directcron_categories";
            $values = $dcron->getRows($query);
            if ($dcron->getRowsAffected() > 0 ) {
                $document 		= JFactory::getDocument();
                $viewType = $document->getType();
                $viewName = "categories";
                $view = &$this->getView($viewName, $viewType);
               // $view->setLayout('category');
                $view->assign('categories', $values);
                $view->display();
            }
            else return false; 
        }
        function publish()
        {
            $divelogID = (isset($_POST['cid']) && is_numeric($_POST['cid'][0])) ? $_POST['cid'][0] : '';
            if (!is_numeric($divelogID)) return;
            $db = JFactory::getDBO();
            $query = "UPDATE #__divelog_entries SET published = 1 WHERE id = $divelogID LIMIT 1";
           // echo $query;
            $result = $db->setQuery($query);
            $result = $db->Query();
            $this->viewLog();
        }
        function unpublish()
        {
            //Array ( [cid] => Array ( [0] => 51 ) [limitstart] => 0 [option] => com_divelog [orderby] => 0 [boxchecked] => 1 [task] => publish ) 
            $divelogID = (isset($_POST['cid']) && is_numeric($_POST['cid'][0])) ? $_POST['cid'][0] : '';
            if (!is_numeric($divelogID)) return;
            $db = JFactory::getDBO();
            $query = "UPDATE #__divelog_entries SET published = 0 WHERE id = $divelogID LIMIT 1";
           // echo $query; 
            $result = $db->setQuery($query);
            $result = $db->Query();
            $this->viewLog();
        }
        function deletePicture()//this is an Ajax function, it deletes pictures through jQuery
        {     
            $document 		=& JFactory::getDocument();
            if ((int)$_POST['logid'] == 0 || (int)$_POST['userid'] == 0) return;
            $user = &JFactory::getUser();
            if ($_POST['userid'] != $user->id) {echo "ERROR - USER IS NOT LOGGED IN"; $document->close(); return;}
            $logphototoken = md5('mind45'.$_POST['userid'].$_POST['logid'].'dmn52');
            if ($logphototoken != $_POST['token']) {echo "ERROR - INCORRECT TOKEN"; $document->close(); return;}
             $db = JFactory::getDBO();
             $picture = $db->getEscaped($_POST['picture']);
             $query = "SELECT pic_dir, pic_thumb_dir, log_photos, log_photos_2 FROM #__divelog_entries WHERE user_id = ".$_POST['userid']." AND id = ".$_POST['logid']." AND (log_photos = '".$picture."' OR log_photos_2 = '".$picture."') LIMIT 1";
             $result = $db->setQuery($query);
             $result = $db->loadObject();
             if (count($result) >= 1)
             {
                 
                 $field = '';
                 if ($result->log_photos == $_POST['picture']) $field = 'log_photos';
                 if ($result->log_photos_2 == $_POST['picture']) $field = 'log_photos_2';
                 if (empty($field))  {echo "ERROR - PICTURE IS NOT IN THE DATABASE"; $document->close(); return;}
                 $query = "UPDATE #__divelog_entries SET $field = '' WHERE user_id = ".$_POST['userid']." AND id = ".$_POST['logid']." LIMIT 1";
                 //echo $query;
                 $result_2 = $db->setQuery($query);
                 $result_2 = $db->Query();
                 //$result_2 = true;
                 if ($result_2)
                 {
                     unlink(str_replace('//', '/', JPATH_BASE.DS.$result->pic_thumb_dir.DS.$_POST['picture']));
                     unlink(str_replace('//', '/', JPATH_BASE.DS.$result->pic_dir.DS.$_POST['picture']));
                     echo 1;
                     $document->close();
                     return;
                 }
             }
             else { echo "ERROR - PICTURE IS NOT IN THE DATABASE"; $document->close(); return;}
        }
        function applyupdate() {$this->edit(true);}
        function saveupdate() {$this->edit(false);}
        function edit($showform = true)
        { 
            $user = &JFactory::getUser();
                global $mainframe;
                $divelogID = JRequest::getVar('id', null, 'default', 'cmd');
                if (!is_numeric($divelogID)) $divelogID = (isset($_POST['cid']) && is_numeric($_POST['cid'][0])) ? $_POST['cid'][0] : '';
                if (!is_numeric($divelogID)) 
                {
                    echo "<h1>Log does not exist or is blank</h1>";
                    return;
                }
                $userid = $user->id;
                
                $document 		=& JFactory::getDocument();
                $document->addScript(JURI::base(true).'components/com_divelog/javascripts/dive.jquery.js');
                $document->addScript(JURI::base(true).'components/com_divelog/javascripts/dive.edit.js');
                $oneproduct = true;
             
		// $showform = true;
                  
		$viewLayout		= JRequest::getCmd( 'layout', 'editform' );//this is the edit form, not the regular form
		$viewType 		= $document->getType();
                $view 			= &$this->getView($viewName, $viewType);
                $modeltouse = &$this->getModel( 'getenterlogs' );
                if (!JError::isError( $modeltouse)) 
                {
			$modelVal   = $view->setModel($modeltouse);
                        $diveLogData = $modelVal->getLog($divelogID);
                       /* if ($diveLogData->user_id != $userid) {echo "<div style='margin-left: 10px; font-size: 15px;'>Unauthorized access to a user's log<br /><br /></div>"; 
                                $this->display();
                                return;
                        
                       //let's make sure users don't abuse the edit function
                       */ if (!is_numeric($diveLogData->user_id)) {
                            echo "<div style='margin-left: 10px; font-size: 16px;'><h1>Log does not exist</h1><br /><br /></div>"; 
                                //$this->display();
                                return;
                            
                        } 
                        $dive_sites = $modelVal->getDiveSites();
                        if ($dive_sites === false)
                        { echo "Error - unable to load Diving Sites"; }
                        if ($_POST && $_POST['user_id'] && $_POST['id'])
                        {
                            $getdata = $modelVal->updateDiveData($_POST);
                            if ($getdata === false) { echo "<p>ERROR - there was an error submitting your data. Contact the administrator</p>"; }
                            else { $diveLogData = $modelVal->getLog($divelogID);}//data got updated }
                             
                        }
                } 
                else echo "Error - unable to load DiveLog model";
                //$showform = true;
                if ($showform === true) {
                $view->setLayout($viewLayout);
                
                   // $params = &$mainframe->getParams();//we need some parameters for the layout
                     
                    
                $view->assign('user_id', $userid);
                $view->assign('dive_sites', $dive_sites);
                $view->assign('diveLogData', $diveLogData);
                $view->display();}
                else 
                { 
                    $component =& JComponentHelper::getComponent('com_divelog');
		    $menus = &JApplication::getMenu('site', array());
	            $items	= $menus->getItems('componentid', $component->id);
                    echo "<div style='margin: 5px;'><p style='font-size: 1.5em;'>Congratulations. Your log has been successfully edited</p>";
		    echo "<p>Click <a target='_blank' href='".JRoute::_(JURI::root().'/index.php?option=com_divelog&task=viewlog&id='.$getdata.'&Itemid='.$items[0]->id)."'>here</a> to see your log.</p></div>"; 
                }
        }
        function viewLog()
        {
                global $mainframe;
                
                $document =& JFactory::getDocument();
                $viewName = 'jDiveCheck';
                $id =  JRequest::getVar('id', '0', 'GET', 'INT');
                if ($id == 0) { $viewLayout = JRequest::getCmd( 'layout', 'divelists' ); }
                else $viewLayout = JRequest::getCmd( 'layout', 'default' );
		$views = array('product', 'entry', 'jsurfproducts', 'author');
		$layouts = array('default', 'form');
                $viewType = $document->getType();
                $view = &$this->getView($viewName, $viewType);
                $modelVal = &$this->getModel( 'getdivedata' );
                if (!JError::isError( $modeltouse))  
                { 
                    if ($id > 0) { $result = $modelVal->getLog($id); } //get data for one dive only
                    else 
                    {   list($result, $pageNav) = $modelVal->getdives();//get data for all dives
                    }
                } 
                else echo "Error - unable to load DiveLog model";
                require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'common'.DS.'class.loader.php');
                    $dataloader = new diveClassLoader();
                    $dataloader->load(1);
                    $divevalues = new divevalues();
                   /* $Itemid = (is_numeric($_GET['Itemid'])) ? $_GET['Itemid'] : '';
                    $view->assign('Itemid', $Itemid);*/
                     $user = &JFactory::getUser();
                    $youruserid = (is_numeric($user->id)) ? $user->id : '';
                     $view->assign('youruserid', $youruserid);
                if ($id > 0 )  
                {
                    $dive_sitenum = $result->dive_site;
                    $view->assign('dive_sitenum', $dive_sitenum);
                    $result->dive_site = $modelVal->getDiveSite($result->dive_site);//get the dive site name from the ID
                    
                    
                    $view->assign('divevalues', $divevalues);
                    
                }
                else {
                    
                     
                    $view->assign('modelVal', $modelVal);
                }
                $view->setLayout($viewLayout);
                $view->assign('diveLog', $result);
                if ($id <= 0 ) 
                {
                    $view->assign('pageNav', $pageNav);
                    $orderBy = JRequest::getVar('orderby', 0, '', 'cmd');
                    $view->assign('orderby', $orderBy);
                    for ($o = 0; $o < count($result); $o++)
                    { 
                        
                        $result[$o]->dive_site = $modelVal->getDiveSite($result[$o]->dive_site); 
                        $dive_data = explode('-', $result[$o]->dive_date);
                        $dive_data[0] = $divevalues->months($dive_data[0]);
                        $result[$o]->dive_date = implode(', ', $dive_data);
                    }
                }
                
                $view->display();
                  
        }
        function about()
        {
            echo "\n<h1>Divelog Component</h1>\n<p><b>Created by: </b>\n<a target='_blank' href='http://www.redacron.com'>Redacron Studios</a>\n</p>";
            echo "<p>If you have any questions or comments, E-Mail Redacron Studios at <a href='mailto:admin@redacron.com'>admin@redacron.com</a></p>";
        }
        function cancel()
        {
            $this->viewLog();
        }
        function display()
        {
            $dcron = new CronDb();
            $viewName	= JRequest::getCmd( 'view' , 'home' );
            $views = array('about');
            if (!in_array($viewName, $views)) $viewName = 'home';
            $document = JFactory::getDocument();
            $document->addStyleSheet(JURI::base(true).'/components/com_directcron/css/styles.css');
            $query = "SELECT * FROM #__directcron_settings";
            $values = $dcron->getRows($query);
           
            $viewType = $document->getType();
            $view = $this->getView($viewName, $viewType);
            if ($dcron->getRowsAffected() > 0 ) { $view->assign('settingval', $values); }
            $view->display();
        }
    
}
?>
