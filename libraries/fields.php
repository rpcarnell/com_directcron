<?php
defined('_JEXEC') or die('Restricted access');
class DRCFields
{
    var $dcron;
    public static function getInstance( $here )
    {
        static $instances;
        if (!isset( $instances )) {$instances = array();}
        $signature = base64_encode( $here );
        if (empty($instances[$signature]))
        {
            $instance = new DRCFields();
            $instances[$signature] =  $instance;
        }
        return $instances[$signature];
    }
    function __construct() { $this->dcron = CronDb::getInstance( 'crondb'); }
    public function getFieldTypes()
    {
        $query = "SELECT * FROM #__directcron_field_types";
        $rows = $this->dcron->getRows($query);
        return $rows;
    }
    public function recordNewData($post, & $dcron)
    {
         $post = $this->cleanupPost($post);
         $post['published'] = (isset($post['published']) && $post['published'] == 'on') ? 1 : 0;
         list($extrapost, $newpost) = $this->getExtraFields($post);
         
         $query = $dcron->buildQuery( 'INSERT', '#__directcron_items', $newpost);
         $id = $dcron->insert($query);
         if (is_numeric($id)) {  
           $imgpost = $this->uploadImage($id, $post['category']);
           if ($imgpost['image'] != '' && $imgpost['thumbnail'] != '')
           {
               $imgpost_2 = array();
               $imgpost_2['image'] = serialize($imgpost);
               $query = $dcron->buildQuery( 'UPDATE', '#__directcron_items', $imgpost_2, 'WHERE id ='.$id." LIMIT 1");
               $dcron->update($query);
            }
         //now we need the extra fields' values:
         if ($id) $this->editExtraFields($extrapost, $id, $post['category']);  
             return $id;}
         else return false;
    }
    public function getFieldType($id)
    {
        if (!is_numeric($id)) return false;
        $query = "SELECT name FROM #__directcron_field_types WHERE id= $id LIMIT 1";
        $value = $this->dcron->getOneValue($query);
        return $value;
    }
    public function getImageFields($id, $published = true)
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
    public function editExtraImages($id, $category)
    {
        if (!is_numeric($category)) return; 
        $extrapost = $this->getImageFields($category, true);
        if ($extrapost === false) return;
        $temp = array();
        $catg = DRCCategories::getInstance('ddrcateg');
        $catgdata = $catg->getCategoryInfo($category);
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
    public function uploadImage($id, $catid)
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
    public function getFields($id, $searchall = false)//**
    {
        if (!is_numeric($id)) return false;
        $catg = DRCCategories::getInstance('ddrcateg');
        $categories = $catg->getParentCategories($id);
        $published = '';
        if ($searchall === true) $published = 'b.published = 1 AND';
        if ($categories === false || !is_array($categories)) return false;
        else $query = "SELECT a.name, a.id, a.type, a.category, a.published, b.ordering FROM #__directcron_fields a LEFT JOIN #__directcron_fields_settings b ON a.id = b.field_id WHERE $published a.category IN (".implode(',', $categories).") ORDER BY ORDERING ASC"; 
        echo $query;
        $fields = $this->dcron->getRows($query);
        if ($this->dcron->getRowsAffected() > 0 ) return $fields;
        else return false;
    }
    
    public function getAllFields($searchall = false)//**
    {
        $published = '';
        if ($searchall === true) $published = 'WHERE b.published = 1';
        $query = "SELECT a.name, a.id, a.type, a.category, a.published, b.ordering FROM #__directcron_fields a LEFT JOIN #__directcron_fields_settings b ON a.id = b.field_id $published GROUP BY name ORDER BY ORDERING ASC"; 
        $fields = $this->dcron->getRows($query);
        if ($this->dcron->getRowsAffected() > 0 ) return $fields;
        else return false;
    }
    public function getExtraFields($post)//we need to separate the extra fields from the ones that are indigenous of the component
    {
        $cronsec = new CronSecure();
        $extrafields = $this->getAllFields();
        $newpost = $post;
        $extrapost = array();
        foreach ($extrafields as $xtr) 
        {   print_r($xtr); echo "<br /><br />";
            foreach ($newpost as $key => $value) 
            { 
                if ($key == str_replace(' ', '_', $xtr->name)) 
                { 
                    $extrapost[str_replace('_', ' ', $key)] = $value;
                    unset($newpost[$key]); 
                }//we put the user-added values in extrapost, and we put the item's values in newpost
                else $newpost[$key] = $cronsec->protectSQL($value);//we are getting the item values, after 
            } 
        }
        return array($extrapost, $newpost);         
   }
    public function editExtraFields($post, $id, $category)//we have a problem here. Some extra fields may stay even though the user has gotten rid of them
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
    public function verifyField($id)//we find out if the field exists first
    {
        $query = "SELECT * FROM #__directcron_fields WHERE id = $id LIMIT 1";
        $field = $this->dcron->getRow($query);
        if ($this->dcron->getRowsAffected() > 0 ) return true;
        else return false;
    }
    public function verifyFieldByNameCatg($catg_name, $category)
    {
        return true;
    }
    public function cleanupPost($post)
    {
        unset($post['id']);
        unset($post['view']);
        unset($post['task']);
        unset($post['add_data']);
        unset($post['boxchecked']);
        unset($post['option']);
        unset($post['upload_file']);
        return $post;
    }
}
?>
