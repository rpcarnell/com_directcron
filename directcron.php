<?php
defined('_JEXEC') or die('Restricted access');
define('DIRC_COM_COMPONENT', 'com_directcron');
define('DRCCATEGORY', 'category');
require_once(JPATH_ADMINISTRATOR.DS."components/com_directcron/common".DS."settings.php");
require_once (JPATH_COMPONENT.DS.'controllers'.DS.'controller.php');
require_once (JPATH_COMPONENT.DS.'libraries/analytics.php');
require_once (JPATH_COMPONENT.DS.'libraries/framework.php');
require_once (JPATH_COMPONENT.DS.'libraries/diroutput.php');
require_once (JPATH_COMPONENT.DS.'libraries/class.styles.php');
require_once (JPATH_COMPONENT.DS.'libraries/categories.php');
require_once (JPATH_COMPONENT.DS.'libraries/settings.php');
$view = JRequest::getVar('view', '', 'cmd');
$controller = '';
if( $view != '') {

	$path = JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php';
        
	if (file_exists($path)) 
        { require_once ($path);
                
                $controller = $view;
	}  
}

if ($view=='enterdata') $defaultTask = 'showform';
else $defaultTask = 'paramData';
//Create the controller
$classname  = 'DRCController'.$controller;

$controller = new $classname( );

$task = JRequest::getVar('task', $defaultTask, 'default', 'cmd');
$controller->execute($task);
// Redirect if set by the controller
$controller->redirect();
 
?>
