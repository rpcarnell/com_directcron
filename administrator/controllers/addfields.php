<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
$path = str_replace('administrator/', '', JPATH_COMPONENT);
include_once($path.DS.'libraries/framework.php');
include_once($path.DS.'libraries/fields.php');
include_once($path.DS.'libraries/categories.php');
 
class DirectcronControllerAddFields extends DirectCronController
{
    function __construct() { parent::__construct(); }
    function display($cid = 0)
        {    
            $dcron = new CronDb();
            $secure = new CronSecure();
            $viewName	= JRequest::getCmd( 'view' , 'directcron' );
            $id	= JRequest::getCmd( 'id' ,0, 'cmd');
            $views = array('about');
            if (!in_array($viewName, $views)) $viewName = 'addfields';
            jscssScripts::jsInclude('com_directcron','css/styles.css');
            jscssScripts::jsInclude('com_directcron','javascripts/addfields.js');
            jscssScripts::declareVariable('tasksURL', JURI::root()."administrator/");
            $query = "SELECT * FROM #__directcron_categories ORDER BY category";
            $values = $dcron->getRows($query);
            if ($id == 0) $id = $values[0]->id;//get the id of the first category
            $modeltouse = $this->getModel ( 'fieldsFetch', 'dccrModel');
            $fields = $modeltouse->getFields($id);
            $drcc = DRCCategories::getInstance('ddrcateg');
            $drcFields = DRCFields::getInstance('ddrcfields');
            
                $viewType = 'html'; 
                $view = $this->getView($viewName, $viewType);
                $view->assign('categories', $values);
                $view->assign('fields', $fields);
                $view->assign('drcc', $drcc);
                $view->assign('drcFields', $drcFields);
                $view->assign('id', $id);
                $view->display();
        }
        function unpublish()
       {
           $cid = JRequest::getVar('cid', 0, 'cmd');
           if (!is_array($cid)) return;
           $dcron = new CronDb();
           foreach($cid as $cd)
           {
               $query = "UPDATE #__directcron_fields SET published = 0 WHERE id = $cd LIMIT 1";
               $dcron->update($query);
           }
           $this->display();
       }
       function publish()
       {
           $cid = JRequest::getVar('cid', 0, 'cmd');
           if (!is_array($cid)) return;
           $dcron = new CronDb();
           foreach($cid as $cd)
           {
               $query = "UPDATE #__directcron_fields SET published = 1 WHERE id = $cd LIMIT 1";
               $dcron->update($query);
           }
           $this->display();
       }
        public function remove()
        {
            $cid = JRequest::getVar('cid', 0, 'cmd');
            if (!is_array($cid)) return;
            $dcron = new CronDb();
            foreach($cid as $cd)
            {
               $query = "DELETE FROM #__directcron_fields WHERE id = $cd LIMIT 1";
               $dcron->update($query);
            }
            $this->display();
        }
        public function ajaxeditfield()
        {
            $app = &JFactory::getApplication();
            $catinfo = array();
            $catinfo['category'] = JRequest::getVar('category', 0, 'post', 'int');
            $catinfo['id'] = JRequest::getVar('id', 0, 'post', 'int');
            $catinfo['type'] = JRequest::getVar('type', 0, 'post', 'int');
            $catinfo['nullornot'] = JRequest::getVar('nullornot', false, 'post', 'boolean');
            $catinfo['name'] = JRequest::getVar('name', 0, 'post', 'string');
            $catinfo['style_class'] = JRequest::getVar('style_class', 0, 'post', 'string');
            $catinfo['style_id'] = JRequest::getVar('style_id', 0, 'post', 'string');
            $catinfo['extra'] = JRequest::getVar('extra', 0, 'post', 'string');
            $catinfo['ordering'] = JRequest::getVar('ordering', 0, 'post', 'int');
            $catinfo['default_value'] = JRequest::getVar('odefault_value', 0, 'post', 'string');
            $catinfo['published'] = JRequest::getVar('published', 1, 'post', 'int');
            $modeltouse = &$this->getModel ( 'fieldsFetch', 'dccrModel');
            if ($catinfo['id'] > 0) { $field = $modeltouse->updateFields($catinfo);
            
            if ($field == 1) { echo "<p style='color: #0a0'>".JText::_('DATAUPDATED_SUCCESSFULLY')."</p>"; }
            else { echo "<p style='color: #a00'>".JText::_('DATA_NOT_UPDATED')."</p>"; } }
            else { $field = $modeltouse->insertFields($catinfo); echo "<p style='color: #0a0'>".JText::_('DATAINSERTED_SUCCESSFULLY')."</p>"; }
            $app->close();
        }
        public function ajaxFieldInfo()//category deals with an Ajax form
        {
            $app = &JFactory::getApplication();
            $id = (isset($_POST['id']) && is_numeric($_POST['id'])) ? $_POST['id'] : 0;
            if (!is_numeric($id) || $id == 0) { 
                $catid = (isset($_POST['catid']) && is_numeric($_POST['catid'])) ? $_POST['catid'] : false;
                if ($catid)//we don't have an id, but we have a category id. That means a new field is being added.
                {
                    $values = new stdClass();  
                    $values->category = $catid;
                } else $values = '';
            } else {
                 $dcron = new CronDb();
                 $query = "SELECT a.*, b.* FROM #__directcron_fields as a LEFT JOIN #__directcron_fields_settings as b ON a.id = b.field_id WHERE a.id = $id LIMIT 1";
                 $values = $dcron->getRow($query); }
                 //this is the form used to either add or edit a field:
 ?>
<script language='javascript'>
function DVShow()
{
    var $showNotNull = cronframe.jQuery('select[name=nullornot]').val() ;
    if ($showNotNull == 1) { cronframe.jQuery('#DVShow').fadeIn(); } else cronframe.jQuery('#DVShow').fadeOut();
}
</script>
<div id="formresult"></div>
<form><input type="hidden" name="cid" value="<? echo $id;?>" /> 
    <p>Field Name: <input type="text" name="name" value="<?php echo (isset($values->name) ) ? $values->name : ''; ?>" /></p>
     
    <p>Field Type: <select name="type"><?php
        $drcfields = new DRCFields();
        $fieldtypes = $drcfields->getFieldTypes();
        foreach ($fieldtypes as $fts)
        {
            if ( isset( $values->type ) &&  ($values->type != $fts->id) ) $select = '';
            else $select = "selected='selected'";
            echo "<option $select value='".$fts->id."'>".$fts->name."</option>\n";
        }
    ?></select></p>
    <p>Field Category: <select name="category"><?php
        $drcatg = new DRCCategories();
        $catgs = $drcatg->getAllCategories();
        foreach ($catgs as $fts)
        {
            if (isset( $values->category ) &&  $values->category != $fts->id) $select = '';
            else $select = "selected='selected'";
            echo "<option $select value='".$fts->id."'>".$fts->category."</option>\n";
        }
    ?></select></p> 
    <p>Null or Not: <select name="nullornot" onChange="DVShow()">
            <option value='0' <?php echo (isset( $values->nullornot ) && ! $values->nullornot) ? "selected='selected'": '';?>>NULL</option>
            <option value='1' <?php echo (isset( $values->nullornot ) && $values->nullornot) ? "selected='selected'": '';?>>NOT NULL</option>
        </select></p>
        
        <div id='DVShow' style='display: none;'><p>Default Value: <input type="text" name="default_value" maxlength="250" size="50" value="" />
        </p></div>
        <p>Field Class: <input type="text" name="style_class" value="<?php echo (isset( $values->style_class ) && trim($values->style_class)) ? $values->style_class : ''; ?>" /></p>
        <p>Field ID: <input type="text" name="style_id" value="<?php echo (isset( $values->style_id ) && trim($values->style_id)) ? $values->style_id : ''; ?>" /></p>
        <p>Field Extras: <input type="text" size="50" maxlength="250" name="extra" value="<?php echo (isset( $values->extra ) && trim($values->extra)) ? $values->extra : ''; ?>" /></p>
        <p>Ordering: <input type="text" size="5" maxlength="5" name="ordering" value="<?php echo (isset( $values->ordering ) &&  is_numeric($values->ordering)) ? $values->ordering : ''; ?>" /></p>
        <p>Published: <input type="text" size="2" maxlength="2" name="published" value="<?php echo (isset( $values->published ) &&  is_numeric($values->published)) ? $values->published : ''; ?>" /></p>
        
        <p>
            <input type="button" onClick="submitChanges()" value="Edit values" /> 
        </p>
        
</form>
<?php
if (isset( $values->nullornot ) &&  $values->nullornot)  echo "<script>DVShow();</script>";
           $app->close();
             
        }
}
 
       
        ?>
 