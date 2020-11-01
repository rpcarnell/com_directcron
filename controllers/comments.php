<?php
class DRCControllerComments extends DRCController
{
    var $CronDb;
    function __construct() { 
        $this->CronDb = CronDb::getInstance( 'crondb');
        parent::__construct(); 
       
    }
    public function usercomment()
    {
        $app	= JFactory::getApplication();
        $params = JComponentHelper::getParams( 'com_directcron' );
        $recaptcha = $params->get('commentreCAPTCHA');
        $itemid = isset($_POST['Itemid']) ? "&Itemid=".$_POST['Itemid']: '';
        $id = (isset($_POST['id']) && is_numeric($_POST['id'])) ? $_POST['id']: '';
        $redirect = JRoute::_('index.php?option=com_directcron&view=items&task=oneitem&id='.$id.$itemid);
        if ($recaptcha)
        {
            if ( !function_exists('_recaptcha_qsencode') ) { include_once(JPATH_COMPONENT."/libraries/recaptchalib.php"); }
           $response = reCaptcha();
           if (!$response)
           {
               $app->redirect( $redirect );
               return;
           }
        }
        $values = array();
        $values['item_id'] = $_POST['id'];
        $values['published'] = 1;
        $values['userid'] = $_POST['user_id'];
        $values['comment'] = $_POST['commentText'];
        $values['username'] = $_POST['username'];
        $values['user_email'] = $_POST['commentoremail'];
       
        $redirect = JRoute::_('index.php?option=com_directcron&view=items&task=oneitem&id='.$id.$itemid);
        // JError::raiseError(403, JText::_('K2_ALERTNOTAUTH'));
        if (! JMailHelper::isEmailAddress($values['user_email']) )
        {
            $app->enqueueMessage(JText::_('DIRCRON_INVALID_EMAIL'), 'warning');
            $app->redirect( $redirect );
        }
        $values['user_URL'] = $_POST['commentorURL'];
        if (trim($values['user_URL']) && preg_match('/^((http|https|ftp):\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}((:[0-9]{1,5})?\/.*)?$/i', $values['user_URL']))
        {
            $fixurl = $values['user_URL'];
            $fixurl = str_replace(';//', '://', $fixurl);
            $fixurl = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $fixurl);
            if ($fixurl != '') { $fixurl = (!strstr($fixurl, '://')) ? 'http://'.$fixurl : $fixurl; }
            $values['user_URL'] = $fixurl;
        }
        else { $values['user_URL'] = ''; }
        $query = $this->CronDb->buildQuery('INSERT', '#__directcron_comments', $values, '', $doNotEscape);
        $this->CronDb->insert($query);
        $msg = JText::_('YOUR_COMMENT_ENTERED');
        $app->enqueueMessage($msg, 'notice');
        
        $app->redirect( $redirect );
    }
}
?>
