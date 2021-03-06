<?php

$install 				= JRequest::getVar('install', '', 'REQUEST');
$view 	 				= JRequest::getVar('view', '', 'cmd');

$task = JRequest::getVar('task', null, 'default', 'cmd');
require_once (JPATH_COMPONENT.DS.'controller.php');
$document	=& JFactory::getDocument();
$mainframe	=& JFactory::getApplication();
$document->addStyleSheet(JURI::base(true).'/components/com_divelog/css/styles.css');
$controller = '';
if( $view != '') {

	$path = JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php';
	if (file_exists($path)) {
		require_once $path;
                $controller = $view;
	}  
}
//Create the controller
$classname  = 'DirectcronController'.$controller;
$controller = new $classname( );
$controller->execute($task);
?>