<?php
class DRCControllerEnterData extends DRCController
{
    private $com_params;
    function __construct()
    { 
        parent::__construct();
        $this->com_params = JComponentHelper::getParams( 'com_directcron' );
    }
    function showForm()
    {  
        $view  = $this->getView  ( 'showform','html' );
        if ($this->com_params->get('itemformlogin'))
        {
               $user = &JFactory::getUser();
               if($user->guest) {
                // Show login page
                $juri = JURI::getInstance();
                $myURI = base64_encode($juri->toString());
                $com = version_compare(JVERSION, '1.6.0', 'ge') ? 'users' : 'user';
                JFactory::getApplication()->redirect(JURI::base().'index.php?option=com_'.$com.'&view=login&return='.$myURI);
                return false;
        }
        }
        $view->assign('com_params', $this->com_params);
        $view->display();
    
    }
    function getData()
    {
        $modeltouse = &$this->getModel ( 'fieldsFetch', 'dccrModel');
        $modeltouse->recordNewData($_POST);
    }
    
}
?>
