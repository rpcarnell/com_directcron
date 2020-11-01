<?php 
class DRCControllerItems extends DRCController
{
    var $com_params;
    function __construct()
    { 
        parent::__construct(); 
        $this->com_params = JComponentHelper::getParams( 'com_directcron' );
    }
    function viewItems()
    {
        $catid	= JRequest::getVar('catid', 0, '', 'int');
        if ($catid == 0) return;
        $settings = new DRCSettings();
        $document = JFactory::getDocument();
        $viewType = $document->getType();
        $modeltouse = &$this->getModel ( 'itemFetch', 'dccrModel');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        
        list($items, $limitstart, $pagination) = $modeltouse->getItems($catid, $this->com_params);
        $subcategories = $modeltouse->getSubCategories($catid);
        
        $catgdata = $modeltouse->getCategoryData($catid);
        $drcc = DRCCategories::getInstance('ddrcateg');
        $drcc->updateVisits($catid);
        //analyze data using the user's ip:
        $DRCLytics = DRCLytics::getInstance( 'DRCLytics' );
        $user = & JFactory::getUser();
        $DRCLytics->updateAnalys($user, $catid, 'category');
        $formattask = new strFormat();
         //set the data for the output functions to handle (for the category):
        $oneItemFields = outputData::getInstance('category');//notice category. This is category data
        $oneItemFields->setData((array)$catgdata);
        $oneItemFields->setParams($catgdata->params);
        $view = $this->getView  ( 'items',$viewType);
        $view->assign('formattask', $formattask);
        $view->assign('catgdata', $catgdata);//catgdata also contains the params we will use
        $style = new DRCStyles();
        
        if (!isset($catgdata->templatefile) || trim($catgdata->templatefile) == '')
        {
            $catgdata->templatefile = 'category';
            if (!is_file(JPATH_COMPONENT."/templates/".$style->getTemplateDirectory()."/category.php"))
            { echo "ERROR - no file category.php in ".JPATH_COMPONENT."/templates/".$style->getTemplateDirectory()."!"; }
        }
        //let's add the Cascading style sheet:
        jscssScripts::jsInclude( 'com_directcron', 'templates/'.$style->getTemplateDirectory().DS.'css'.DS.'style.css');
        $view->assign('style', $style);
        $view->assign('subcategories', $subcategories);
        $oneItemFields = outputData::getInstance('subcategory');//notice category. This is category data
        $oneItemFields->setData((array)$subcategories);
        $oneItemFields->setParams($catgdata->params);
        $oneItemFields = outputData::getInstance('oneItem');//notice category. This is category data
        $oneItemFields->setData($items);
        $oneItemFields->setParams($catgdata->params);
        $view->assign('items', $items);
        $view->assign('com_params', $this->com_params);
        $view->assign('commentfor', 'items');
        $view->assign('pagination', $pagination);
        $view->display();
    }
    function oneItem()
    {
        $iid = JRequest::getVar('id', 0, '', 'int');
        $itemid = JRequest::getVar('Itemid', 0, '', 'int');
        $formatDat = new urlFormat();
        $itemid = ($itemid == 0) ? $formatDat->getItemid('com_directcron', 'oneitem', 0) : $itemid;
        if ($iid == 0) return;
        jscssScripts::addjQuery();
        $document = JFactory::getDocument();
        $lang     = $document->getLanguage();
        
       // jscssScripts::addjQuery();
       
        $color = $this->com_params->get('themerecaptcha', 'red');
        $paramsReCaptcha = "
				var RecaptchaOptions = {
				   theme : '$color',
				   lang  : '". substr($lang, 0, 2)."'
				};
				";
        
        jscssScripts::declareScript($paramsReCaptcha);
        $style = new DRCStyles();
        jscssScripts::jsInclude( 'com_directcron', 'templates/'.$style->getTemplateDirectory().DS.'css'.DS.'style.css');
        $view  = $this->getView  ( 'oneitem','html' );
        $modeltouse = $this->getModel ( 'itemFetch', 'dccrModel');
        $oneitem = $modeltouse->getOneItem($iid, $this->com_params);
        $catg = ($oneitem) ? $modeltouse->getCategoryData($oneitem->category) : '';
        $oneitem->category_id = (isset($oneitem->category)) ? $oneitem->category : '';
        $oneitem->category = (isset($catg->category)) ? $catg->category : '';
        if ($oneitem) $oneitem = $this->object_to_array($oneitem);
        if ($oneitem) {
            $fieldstouse = $this->getModel ( 'fieldsFetch', 'dccrModel');
            list($fields, $fieldtypes) = $fieldstouse->getFieldValues($iid, $this->com_params);
 
            $pluginfields = array();
            if (is_array($fields)) {foreach ($fields as $key => $value)
            {
                $pluginfields[$key]['value'] = $value;
                $pluginfields[$key]['type'] = $fieldtypes[$key];
            }}
            if ($fields) $oneitem = array_merge($oneitem, $fields);
            
            foreach ($oneitem as $key => $value)
            {
                $pluginfields[$key]['value'] = $value;
                $pluginfields[$key]['type'] = 0;
            }
        } else $fieldtypes = '';
        JPluginHelper::importPlugin('directcron');
        $dispatcher = JDispatcher::getInstance();
        $formattask = new strFormat(20);
        $view->assign('formattask', $formattask);
        $view->assign('oneitem', $oneitem);
        $view->assign('category', $catg);
        $user =  JFactory::getUser();
        $view->assign('userid', $user->id);
        $oneItemFields = outputData::getInstance('oneItem');//notice the oneItem. We will have a static declaration of this class, so the values will be preserved
        $oneItemFields->setItemId($iid);
        
        $oneItemFields->setData($oneitem);
        $oneItemFields->setDataTypes($fieldtypes);
        $commentsModel = $this->getModel ( 'comments', 'dccrModel');
        $comments = $commentsModel->getItemComments($oneitem['id']);
        $view->assign('comments', $comments);
        $view->assign('itemid', $itemid);
        $view->assign('com_params', $this->com_params);
        $view->assign('commentfor', 'oneitem');
        $dispatcher->trigger('beforeOneItemDisplay', array( $oneitem,  $pluginfields,  $oneItemFields) );
        $view->display();
        $dispatcher->trigger('afterOneItemDisplay', array( $oneitem,  $pluginfields,  $oneItemFields));
    }
}
?>
