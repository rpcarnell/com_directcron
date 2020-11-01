<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');
$path = str_replace('administrator/', '', JPATH_COMPONENT);
include_once($path.DS.'libraries/categories.php');
include_once($path.DS.'libraries/imageupload.php');
include_once($path.DS.'models/fieldsinterface.php');
class dccrModelFieldsFetch extends JModelLegacy implements fieldsFetch
{
    var $dcron;
    var $drcc;
    public function __construct() 
    { 
        parent::__construct(); 
        $this->dcron = new CronDb(); 
        $this->drcc =  new DRCCategories();
    }
    public function getAllItems()//**
    {
        $drcc = DRCCategories::getInstance('ddrcateg');
        list($data, $limitstart, $pagination) = $drcc->getAllItems();
        return array($data, $limitstart, $pagination);
    }
    public function getCategoryData($cid)//**
    {
        $drcc = DRCCategories::getInstance('ddrcateg');
        $data = $drcc->getCategoryData($cid);
        return $data;
    }
    private function getCategoryInfo($cid)
    {
        $drcc = DRCCategories::getInstance('ddrcateg');
        $data = $drcc->getCategoryInfo($cid);
        return $data;
    }
    public function getItemFields($id)//** gets all the fields for a single item
    {
        if (!is_numeric($id)) return;
        $query = "SELECT * FROM #__directcron_items WHERE id = $id LIMIT 1"; 
        $data = $this->dcron->getRow($query);
        if ($this->dcron->getRowsAffected() > 0 ) return $data;
        else return false;
    }
    public function getFields($id, $searchall = false)//**
    {
        $drcFields = DRCFields::getInstance('ddrcfields');
        return $drcFields->getFields($id, $searchall);
    }
    public function getFieldValues($field, $itemid)//***
    {
        if (!is_numeric($field) || !is_numeric($itemid)) { echo "ERROR - invalid data"; return; }
        $query = "SELECT itemid, field, value FROM #__directcron_field_values WHERE itemid = $itemid AND field = $field LIMIT 1";
        $fields = $this->dcron->getRow($query);
        if ($this->dcron->getRowsAffected() > 0 ) return $fields;
        else return false;
    }
    /*private function verifyField($id)//we find out if the field exists first
    {
        $query = "SELECT * FROM #__directcron_fields WHERE id = $id LIMIT 1";
        $field = $this->dcron->getRow($query);
        if ($this->dcron->getRowsAffected() > 0 ) return true;
        else return false;
    }
    private function verifyFieldByNameCatg($catg_name, $category)
    {
        return true;
    }*/
    public function insertFields($catinfo)//***
    {
        if (DRCFields::verifyFieldByNameCatg($catinfo['name'], $catinfo['category']) !== false)
        {
            $catinfo_2 = array();
            unset($catinfo['id']);//we don't need an id of 0
            $catinfo_2['name'] = $catinfo['name'];
            $catinfo_2['type'] = $catinfo['type'];
            $catinfo_2['category'] = $catinfo['category'];
            $query = $this->dcron->buildQuery( 'INSERT', '#__directcron_fields', $catinfo_2);
            $id = $this->dcron->insert($query);
            if ((int)$id == 0) return false;
            else //let's insert the field's settings now:
            { 
                $catinfo_3 = array();
                $catinfo_3['style_id'] =  $catinfo['style_id'];
                $catinfo_3['field_id'] = $id;
                $catinfo_3['style_class'] =  $catinfo['style_class'];
                $catinfo_3['extra'] =  $catinfo['extra'];
                $catinfo_3['nullornot'] =  $catinfo['nullornot'];
                $catinfo_3['published'] =  $catinfo['published'];
                $catinfo_3['ordering'] =  $catinfo['ordering'];
                $catinfo_3['default_value'] =  $catinfo['default_value'];
                $query = $this->dcron->buildQuery( 'INSERT', '#__directcron_fields_settings', $catinfo_3);
                $this->dcron->insert($query);
            }
                return true;
        } else return false;
    }
    public function updateFields($catinfo)//**
    {      
        if (!is_numeric($catinfo['id'])) return;
        if ($this->verifyField($catinfo['id']) !== false)
        {
            $id = $catinfo['id'];
            $catinfo_2 = array();
            //category, name, type <---fields table
            $catinfo_2['name'] = $catinfo['name'];
            $catinfo_2['type'] = $catinfo['type'];
            $catinfo_2['category'] = $catinfo['category'];
            $query = $this->dcron->buildQuery( 'UPDATE', '#__directcron_fields', $catinfo_2, 'WHERE id = '.$id);
            $upt = $this->dcron->update($query);
            if ($upt == 0) return false;
            else 
            { 
                $catinfo_3 = array();
                //style_id, style_class, extra, nullornot, published, ordering, field_id <---field_settings table
                $catinfo_3['style_id'] =  $catinfo['style_id'];
                $catinfo_3['style_class'] =  $catinfo['style_class'];
                $catinfo_3['extra'] =  $catinfo['extra'];
                $catinfo_3['nullornot'] =  $catinfo['nullornot'];
                $catinfo_3['published'] =  $catinfo['published'];
                $catinfo_3['ordering'] =  $catinfo['ordering'];
                $query = $this->dcron->buildQuery( 'UPDATE', '#__directcron_fields_settings', $catinfo_3, 'WHERE field_id = '.$id);
                $this->dcron->update($query);
            }
                return true;
        } else return false;
    }
    private function getCategories(& $dcron, $id)
    { 
        $parents = $this->drcc->getParentCategories($id);
        return $parents;
    }
    public function recordNewData($post)
    {
         $item = JRequest::getVar('item','', 'post');
         if (trim($item) == '') { JError::raiseWarning(500, JText::_('DIRECTCRON_NOITEM')); return;}
         $drcFields = DRCFields::getInstance('ddrcfields');
         return $drcFields->recordNewData($post, $this->dcron);
    }
   public function editData($post)//***
   {
        $id = JRequest::getVar('id',0, 'post', 'int');
        if (!is_numeric($id) && $id > 0) {JError::raiseWarning(500, JText::_('DIRECTCRON_NOID')); return;}
        $drcFields = DRCFields::getInstance('ddrcfields');
        $post = $drcFields->cleanupPost($post);
        $post['published'] = ($post['published'] == 'on') ? 1 : 0;
        $imgpost = $drcFields->uploadImage($id, $post['category']);//upload image
         $imgpost2 = array();
         $imgpost2['image'] = serialize($imgpost);
        if (trim($imgpost['image']) && $imgpost['image'] != false) $post = array_merge($post, $imgpost2);
        //now we need the extra fields' values:
        list($extrapost, $newpost) = $drcFields->getExtraFields($post);
          
                 $query = $this->dcron->buildQuery( 'UPDATE', '#__directcron_items', $newpost, 'WHERE id = '.$id);
        $upd = $this->dcron->update($query);
        if ($upd) { 
            $drcFields->editExtraFields($extrapost, $id, $post['category']); 
            $drcFields->editExtraImages($id, $post['category'], $extrapost);
            }
        
    }/*
    private function getImageFields($id, $published = true)
    {
        $extras = $this->getFields($id, $published);
        $newarray = array();
        if (isset($extras) && is_numeric($extras[0]->id)) 
        {
            foreach ($extras as $ex) { if ($ex->type == 6) $newarray[] = $ex; }
            return $newarray;
        }
        else return false;
        
    }
    private function editExtraImages($id, $category)
    {
        if (!is_numeric($category)) return; 
        $extrapost = $this->getImageFields($category, true);
        if ($extrapost === false) return;
        $temp = array();
        $catgdata = $this->getCategoryInfo($category);
        $category = $catgdata->category;
        foreach($extrapost as $pst) {   
              {
                  if (!trim($pst->name)) continue;
                  //notice we are initiating another instance of the function, or many instances:
                   $doc =  & upl_albumpics::getInstance($pst->name, $pst->name);//the user may have decided to upload an image
                  list($directory, $picupl) = $doc->upload_pic($id, str_replace(' ', '_', $category) );
                    if ($picupl) 
                    {
                        $postimage = array();
                        if (!empty($directory)) $dir = $directory.DS;
                        else $dir = '';
                        $postimage['image'] = $dir.$picupl;
                        $postimage['thumbnail'] = $dir.DS.'thumbnails'.DS.$picupl;
                        
                    } else continue;
                    $value = serialize($postimage);
                    $key2 = $pst->id;
                    $temp['value'] = $value;
                    $temp['categoryid'] = $catgdata->id;
                    if (trim($key2) && trim($value) ) //if the key does not exist, let's not worry about it
                    {
                       $query = 'SELECT value FROM #__directcron_field_values WHERE field = '.$key2.' AND itemid = '.$id.' LIMIT 1';
                       $extravalue = $this->dcron->getOneValue($query);
                       if (trim($extravalue)) {
                         $imgsettings = imageSettings::getInstance('upload_pic');
                         $crudepath = $imgsettings->imageDir();
                         $fieimages = unserialize($extravalue);
                         if (is_array($fieimages) && trim($fieimages['thumbnail']))//if there are images previously uploaded, let's get rid of them
                         {
                             unlink($crudepath.$fieimages['image']);
                             unlink($crudepath.$fieimages['thumbnail']);
                         }
                         $query = $this->dcron->buildQuery( 'UPDATE', '#__directcron_field_values', $temp, 'WHERE field = '.$key2.' AND itemid = '.$id);
                  }
                  else //user has updated the item, but has no entered data for these fields:
                  {
                      $temp['field'] = $key2;
                      $temp['itemid'] = $id;
                      $query = $this->dcron->buildQuery( 'INSERT', '#__directcron_field_values', $temp);
                  } }
                  $this->dcron->update($query);
                   
              }
        }
    }
    private function uploadImage($id, $catid)
    {
        if (!is_numeric($id) || !is_numeric($catid)) return;
        $doc =  & upl_albumpics::getInstance('upl_albumpics');//the user may have decided to upload an image
        $catg = DRCCategories::getInstance('ddrcateg');
        $category = $catg->getCategory($catid);
        list($directory, $picupl) = $doc->upload_pic($id, str_replace(' ', '_', $category->category));
        $post = array();
        if (!empty($picupl)) 
        {
            if (!empty($directory)) $dir = $directory.DS;
            else $dir = '';
            $post['image'] = $dir.$picupl;
            $post['thumbnail'] = $dir.DS.'thumbnails'.DS.$picupl;
        }
        return $post;
    }
    private function getExtraFields($post)//we need to separate the extra fields from the ones that are indigenous of the component
    {
        $cronsec = new CronSecure();
        $extrafields = $this->getFields($post['category'], true);
        $newpost = $post;
        $extrapost = array();
        foreach ($extrafields as $xtr) { foreach ($newpost as $key => $value) 
            { if ($key == str_replace(' ', '_', $xtr->name)) 
                { 
                $extrapost[str_replace('_', ' ', $key)] = $value;
                unset($newpost[$key]); }//we put the user-added values in extrapost, and we put the item's values in newpost
                else $newpost[$key] = $cronsec->protectSQL($value);//we are getting the item values, after 
            } }
        return array($extrapost, $newpost);         
   }
    private function editExtraFields($post, $id, $category)//we have a problem here. Some extra fields may stay even though the user has gotten rid of them
    {
         $cronsec = new CronSecure();
         foreach ($post as $key => $value)
         {
              $temp = array(); 
              $query = 'SELECT id, type FROM #__directcron_fields WHERE name=\''.$key.'\' LIMIT 1';
              $fieldinfo = $this->dcron->getRow($query);
              $key2 = $fieldinfo->id;
              $temp['value'] = $cronsec->protectSQL($value);//make sure there's no damaging data
              $temp['categoryid'] = $category;
             
              if (trim($key2) && trim($value) ) //if the key does not exist, let's not worry about it
              {
                  $query = 'SELECT categoryid FROM #__directcron_field_values WHERE field = '.$key2.' AND itemid = '.$id.' LIMIT 1';
                  $itemid = $this->dcron->getValue($query);
                  if (is_numeric($itemid)) $query = $this->dcron->buildQuery( 'UPDATE', '#__directcron_field_values', $temp, 'WHERE field = '.$key2.' AND itemid = '.$id);
                  else //user has updated the item, but has no entered data for these fields:
                  {
                      $temp['field'] = $key2;
                      $temp['itemid'] = $id;
                      $query = $this->dcron->buildQuery( 'INSERT', '#__directcron_field_values', $temp);
              } }
               $this->dcron->update($query);
         }
    }
    private function cleanupPost($post)
    {
        unset($post['id']);
        unset($post['view']);
        unset($post['task']);
        unset($post['add_data']);
        unset($post['boxchecked']);
        unset($post['option']);
        return $post;
    }*/
}
?>
