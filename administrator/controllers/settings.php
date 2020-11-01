<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');


$path = str_replace('administrator/', '', JPATH_COMPONENT);
include_once($path.DS.'libraries/framework.php');
       
class DirectCronControllerSettings extends DirectCronController
{
    /**
	 * Constructor
	 **/
	function __construct() {  parent::__construct(); }
        function &getParams()
		{
			static $instance;

			if ($instance == null)
			{
			       $table = & JTable::getInstance('extension');
				if (!$table->load(array("element" => "com_directcron", "type" => "component"))) // 1.6 mod
				{
					JError::raiseWarning(500, 'Not a valid component');
					return false;
				}
                                if ($path = JRequest::getString('path'))
				{
					$path = JPath::clean(JPATH_SITE . '/' . $path);
					JPath::check($path);
				}
				else { $path = JPATH_ADMINISTRATOR . DS . 'components/com_directcron/config.xml'; }
                                /*if (file_exists($path))  $instance = new JRegistry($table->params, $path);
				else $instance = new JRegistry($table->params); */
                                $modeltouse = $this->getModel ( 'Settings', 'dccrModel');
                                $instance = $modeltouse->getParams($table, $path);
                                 
			}
                         
			return $instance;

		} 
        function getEditors()
	{
		$dcron = new CronDb();
		$query = 'SELECT element AS name, name as value'
				. ' FROM #__extensions'
				. ' WHERE folder = "editors"'
				. ' AND type= "plugin" AND enabled = 1'
				. ' ORDER BY ordering, name';
	        $editors = $dcron->getRows($query);
                return $editors;
	}        
        function savesettings()
        {
            $table = & JTable::getInstance('extension');
            if (!$table->load(array("element" => "com_directcron", "type" => "component"))) // 1.6 mod
            {
                    JError::raiseWarning(500, 'Not a valid component');
                    return false;
            }
            $post = JRequest::get('post');
            $post['params'] = $post['jform'];//JRequest::getVar('jform', array(), 'post', 'array');
            $registry = new JRegistry($table->params);
            foreach( $post['params'] as $key => $value )
            {
                     $registry->set($key , $value );
            }
            $table->params = $registry->toString();
            if(!$table->store() ) { return false; }
            return true;
        }
        function applySettings()
        {
            $this->savesettings();
            $mainframe = JFactory::getApplication();
            $url = "index.php?option=com_directcron&view=settings";
            $mainframe->redirect($url, JText::_('DIRCRON_SETTINGS_SAVED'));
        }
        function save()
        {
             $this->savesettings();
             $mainframe = JFactory::getApplication();
             $url = "index.php?option=com_directcron";  
             $mainframe->redirect(JRoute::_($url), JText::_(DIRCRON_SETTINGS_SAVED));
        }
        function display()
        {  
            jscssScripts::jsInclude('com_directcron','javascripts/jquery-ui-1.10.2.custom.js');
            jscssScripts::declareScript("cronframe.jQuery(function() { cronframe.jQuery( document ).tooltip(); });");
            $dcron = new CronDb();
            $params = $this->getParams();//this parameters belong to config.xml
            $viewName	= JRequest::getCmd( 'view' , 'directcron' );
            $views = array('about');
            if (!in_array($viewName, $views)) $viewName = 'settings';
            $document = & JFactory::getDocument();
            $document->addStyleSheet(JURI::base(true).'/components/com_directcron/css/styles.css');
            $query = "SELECT * FROM #__directcron_settings";
            $values = $dcron->getRows($query);
           
            $document 		=& JFactory::getDocument();
            $viewType = $document->getType();
            $view = &$this->getView($viewName, $viewType);
            if ($dcron->getRowsAffected() > 0 ) {
                  
                $view->assign('settingval', $values);
                $view->assign('editors',$this->getEditors());
                $view->assign('params', $params);
               
            }
            $view->display();
        }
}
?>
