<?php
interface dataOutput
{
    function outputVar($field, $tag = '', $style = '', $debug = true, $removefromarray = false);
    function outputVariables($field, $tag = '', $style = '', $debug = true, $removefromarray = false);
    function shiftData();
}
abstract class outputData_basics
{
   private $userdata;
   private $userdataTypes;
   private $dataCleaned;
   protected $cron_db;
   private $itemid;
   private $dataType;
   public $for_loop;
   protected $params;            
   public function __construct() {  
       $this->userdata = ''; 
       $this->cron_db = CronDb::getInstance( 'crondb');
      
   }
    static function &getInstance( $here, $warn = false)
    {
        static $instances;
         $here = strtolower($here);
          
        if (!isset( $instances )) {$instances = array();}
        $signature = base64_encode( $here );
         
        if (empty($instances[$signature]))
        {  
          
            
            switch ($here)
            {
                case 'oneitem':
                $instance = new outputData();
                $output = 'outputData';
                
                break;
                case 'subcategory':
                $instance = new outputDatSubCategory;
                $output = 'outputDataSubCategory';    
                break;
                case 'category':
                $instance = new outputDataCategory;
                $output = 'outputDataSubCategory';    
                break;
                default: 
                echo "<h1 class='error'>ERROR - initialization variable $here does not correspond to any output function!</h1>";
            }
            if ($warn === true) echo "<h1>ERROR - initializing $output Class again. Output from '$here' may be corrupted or non-existent!</h1>";
            $instance->dataType = $here;//set the type of data outData will handle
            
            $instances[$signature] = & $instance;
            $instances[$signature]->dataCleaned = false;
        }  
        return $instances[$signature];
    }
    public function getDataCategory()
    {
        return ($this->dataType != '') ? $this->dataType : 'oneItem';
    }
    public function setItemId($id)
    {
        if (!is_numeric($id)) return;
        $this->itemid = $id;
    }
    public function setDataTypes($ourdata)
    {  
         if (!is_array($ourdata) && trim($ourdata) == '') return false;
         $this->userdataTypes = serialize($ourdata); 
    }
    public function setData($ourdata)
    {  
         if (!is_array($ourdata) && trim($ourdata) == '') return false;
         $this->userdata = serialize($ourdata); 
    }
    public function setParams($params)
    {  
         if (trim($params) == '') return false;
         $this->params = $params;
    }
     public function getParams()
    {  
         if (trim($this->params) == '') return false;
         else return $this->params;
    }
    public function addData($newKey, $value, $type)
    {
         $data = $this->getData();//the data must be still there, since the class was held in a static value.
         $dataTypes = $this->getDataType();
         if ($data === false) return;
         //print_r($data);
         $data[$newKey] = $value;
         $this->setData($data);
         $dataTypes[$newKey] = $type;
         $this->setDataTypes($dataTypes);
    }
    public function getDataType() {
        if ($this->dataCleaned === true) { echo "<p style='color: #f00; font-weight: bold;'>ERROR - dircron_echo being used after echo_fields in template</p>"; return false;} 
        if ($this->userdataTypes == '') return false;
        return unserialize($this->userdataTypes);
        
    }
    public function getData() {
        if ($this->dataCleaned === true) { echo "<p style='color: #f00; font-weight: bold;'>ERROR - dircron_echo being used after echo_fields in template</p>"; return false;} 
        if ($this->userdata == '') return false;
        return unserialize($this->userdata);
        
    }
    public function removefromData($field) {
        if ($this->userdata == '' || $field == '') return;
        $data = unserialize($this->userdata);//first we need to unserialize the data, so whatever the field is, it can be removed from the array
        if (!isset($data[$field])) return;
        unset($data[$field]);
        $this->userdata = serialize($data);
    }
    public function cleanData() { 
        $this->dataCleaned = true;
        $this->userdata = '';  }
    public function getMainItemFields()
    {
        $fields = $this->cron_db->getTableFields('#__directcron_items');
        if (is_array($fields))
        {
            foreach($fields as $key => $value) { $itemfields[] = $key;}
        }
        if (count($itemfields) > 0) return $itemfields;
        else return false;
    }
    protected function object_to_array($data) 
    {
        if ((! is_array($data)) and (! is_object($data))) return; //$data;

        $result = array();

        $data = (array) $data;
        foreach ($data as $key => $value) {
            if (is_object($value)) $value = (array) $value;
            if (is_array($value)) 
            $result[$key] = $this->object_to_array($value);
            else
                $result[$key] = $value;
        }

        return $result;
    }
}
class outputDatSubCategory extends outputData_basics implements dataOutput
{
       function shiftData()
       {
            $data = $this->getData();
            array_shift($data);
            $this->setData($data);
       } 
       private function fieldExists($var)//we dont need to add variables to the category field. At least not yet
        {
             if ($var == 'count') return true;
             $tablefields = $this->object_to_array ( $this->cron_db->list_fields('#__directcron_categories') );
             $fields = array();
             $r = 0;
             foreach($tablefields as $field)
             {
                $fields[$r] = $field['Field'];
                $r++;
             }
             if (! in_array($var, $fields)) {
                 echo "<h1 class='error'>Category field '$var' does not exist on the category table</h1>";
                 return false;
             }
             else return true;
        }
        function outputVar($field, $tag = '', $style = '', $debug = true, $removefromarray = false)
        {  
            if (! $this->fieldExists($field) ) return;//in the future, we may be dealing with user-added fields for categories as well
            return $this->outputVariables($field, $tag, $style, $debug, $removefromarray); 
        }
        function outputVariables($field, $tag = '', $style = '', $debug = true, $removefromarray = false)
        {
            $params = $this->getParams();
            $oneItemFields = $this;//outputData::getInstance('oneItem', true);
            $data_1 = $oneItemFields->getData();//the data must be still there, since the class was held in a static value.
            $data_num = count($data_1);
            $data = $this->object_to_array($data_1[0]);
           // if ($field != 'count') { array_shift($data_1); }
            $oneItemFields->setData($data_1);
           if (trim($tag))
            {
                $tag = strtolower($tag);
                if (strpos($tag, '<') !== false) { $tag = str_replace('<', '', $tag); }
                if (strpos($tag, '>') !== false) { $tag = str_replace('>', '', $tag); }
            }
            switch ($field): 
            case 'count':
                return $data_num;
                break;
             case 'category':
               if ($params->get('showSubCatgTitleLinked',0) && $params->get('showCatgTitle', 0)): 
               $string = "<a href='".JRoute::_('index.php?option=com_directcron&view=items&task=viewItems&catid='.$data['id'])."'>".$data[$field]."</a>";
               elseif ($params->get('showCatgTitle', 0)):  //show category title without the link: 
               $string = $data[$field];
               endif;
            break;
            case 'description':
                if (! $params->get('subcatIntroText',0) ) return;
                $formattask = new strFormat();
                $string = $formattask->shorten_pr($data[$field], $params->get('subcatIntroTextWordLimit', 50));
            break;
            case 'added_by':
                if (! $params->get('subcatAuthor',0)) return;
                $added_by = & JFactory::getUser($data['added_by']);
                if ($params->get('sublinkAuthor', 0)):
                    $string = JText::_('DIRC_CATGADDEDBY')." <a href='".JRoute::_('index.php?option=com_directcron&view=user&userid='.$added_by->id)."'>".$added_by->username."</a>";
                    else:
                    $string = JText::_('DIRC_CATGADDEDBY')." ".$added_by->username;
                endif;
            break;
           case 'categoryimage':
                if (! $params->get('subcatImage',0)) return;
                $default_thumb = unserialize($data['categoryimage']); 
                if ($default_thumb['image']) {  
                    if ($params->get('subcatimagesize', 'thumbnail') == 'thumbnail')
                    { 
                        $default_thumb = $default_thumb['thumbnail'];
                        $height = "height: 100px;";
                    }
                    else { 
                        $default_thumb = $default_thumb['image']; 
                        $height = '';
                    }
                    $imageuRL = JURI::root(true)."/images/".$default_thumb;
                }
                $style= $style." style='height: $height'";
                if ($tag == '' || $tag == 'img') $string = "<img alt='".$data['category']."' src='$imageuRL' ".trim($style)." />"; 
                else $string = $imageuRL;
             endswitch;
            
            if (trim($tag))
            {
                $tagclose = "</$tag>";
                $tag = $this->type." <$tag ".trim($style).">";
            }
            else { $tag = $tagclose = ''; }
            return  $tag.$string.$tagclose;
        }
}
class outputDataCategory extends outputData_basics
{
        private function fieldExists($var)//we dont need to add variables to the category field. At least not yet
        {
             $tablefields = $this->object_to_array ( $this->cron_db->list_fields('#__directcron_categories') );
             $fields = array();
             $r = 0;
             foreach($tablefields as $field)
             {
                $fields[$r] = $field['Field'];
                $r++;
             }
             if (! in_array($var, $fields)) {
                 echo "<h1 class='error'>Category field '$var' does not exist on the category table</h1>";
                 return false;
             }
             else return true;
        }
        function outputVar($field, $tag = '', $style = '', $debug = true, $removefromarray = false)
        {  
            if (! $this->fieldExists($field) ) return;//in the future, we may be dealing with user-added fields for categories as well
            return $this->outputVariables($field, $tag, $style, $debug, $removefromarray); 
        }
        function outputVariables($field, $tag = '', $style = '', $debug = true, $removefromarray = false)
        {
            $params = $this->getParams();
            $oneItemFields = $this;//outputData::getInstance('oneItem', true);
            $data = $oneItemFields->getData();//the data must be still there, since the class was held in a static value.
            if (trim($tag))
            {
                $tag = strtolower($tag);
                if (strpos($tag, '<') !== false) { $tag = str_replace('<', '', $tag); }
                if (strpos($tag, '>') !== false) { $tag = str_replace('>', '', $tag); }
            }
            switch ($field): 
            case 'category':
               if ($params->get('showCatgTitle', 0)):  //show category title without the link: 
               $string = $data[$field];
               endif;
            break;
            case 'description':
                if (! $params->get('catIntroText',0) ) return;
                $formattask = new strFormat();
                $string = $formattask->shorten_pr($data[$field], $params->get('catIntroTextWordLimit', 50));
            break;
            case 'added_by':
                if (! $params->get('catAuthor',0)) return;
                $added_by = & JFactory::getUser($data['added_by']);
                if ($params->get('linkAuthor', 0)):
                    $string = JText::_('DIRC_CATGADDEDBY')." <a href='".JRoute::_('index.php?option=com_directcron&view=user&userid='.$added_by->id)."'>".$added_by->username."</a>";
                    else:
                    $string = JText::_('DIRC_CATGADDEDBY')." ".$added_by->username;
                endif;
            break;
            case 'categoryimage':
                if (! $params->get('catImage',0)) return;
                $default_thumb = unserialize($data['categoryimage']); 
                if ($default_thumb['image']) { 
                    if ($params->get('catimagesize', 'thumbnail') == 'thumbnail')
                    {
                        $default_thumb = $default_thumb['thumbnail'];
                        $height = "height: 100px;";
                    }
                    else { 
                        $default_thumb = $default_thumb['image']; 
                        $height = '';
                    }
                    $imageuRL = JURI::root(true)."/images/".$default_thumb;
                }
                $style= $style." style='height: $height'";
                if ($tag == '' || $tag == 'img') $string = "<img alt='".$data['category']."' src='$imageuRL' ".trim($style)." />"; 
                else $string = $imageuRL;
                break;
            case 'visited':
                if (! $params->get('categoryHits', 0) ) return;
                $string = $data[$field];
                break;
            default:
               return  $string = $data[$field];
            
             endswitch;
            
            if (trim($tag))
            {
                $tagclose = "</$tag>";
                $tag = "<$tag ".trim($style).">";
            }
            else { $tag = $tagclose = ''; }
            return  $tag.$string.$tagclose;
        }
}
class outputData extends outputData_basics implements dataOutput //class handles item data. Including those variables created by users
{
    function shiftData()
    {
        $data = $this->getData();
        array_shift($data);
        $this->setData($data);
    }
    function outputVariables($field, $tag = '', $style = '', $debug = true, $removefromarray = false)
        {
            $oneItemFields = $this;//outputData::getInstance('oneItem', true);
            $data = $oneItemFields->getData();//the data must be still there, since the class was held in a static value.
            if ($field == 'count'){ return count($data); }
            //if (!isset($data->id)) $data = 
            if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) { } 
            else {
              $data = $this->object_to_array( $data[0] ) ;
            
               $dataTypes = $oneItemFields->getDataType();
               if ($data === false) return;
            }
            
            
            if (isset($dataTypes) && $dataTypes[$field] == 7)
            {   
                dircron_imageURL($field, '', $style);
            }
            elseif (isset($dataTypes) &&  $dataTypes[$field] == 6 || $field == 'image' || $field == 'thumbnail') //notice that the image field is mentioned here
            { 
                if ($field == 'thumbnail') {$field = 'image'; $useThumb = true;}
                else $useThumb = false;
                dircron_imageHandler($field, $useThumb, '', $style);
                return;
            } else {
            if (!isset($data[$field])) 
            { 
                if ($debug === true)  {echo "<p style='color: #f00; font-weight: bold;'>ERROR - field $field does not exist</p>";} 
                return; 
            }
            elseif (($data[$field]) == '') { return; }
            $tag = strtolower($tag);
            if (strpos($tag, '<') !== false) { $tag = str_replace('<', '', $tag); }
            if (strpos($tag, '>') !== false) { $tag = str_replace('>', '', $tag); }
            if (trim($tag))
            {
                $tagclose = "</$tag>";
                $tag = "<$tag ".trim($style).">";
            }
            else { $tag = $tagclose = ''; }
            return $tag.$data[$field].$tagclose;
            if ($removefromarray === true) $oneItemFields->removefromData($field);//now remove the key from the array so echo_fields won't print it out
            }//end of if ($dataTypes[$field] == 6)'s else
        }
       function outputVar($field, $tag = '', $style = '', $debug = true, $removefromarray = false)
        { 
            if (strpos($field, ':') !== false)
           {
               $field_split = split(':', $field);
               $field = $field_split[1];
               $typeofData = ($field_split[0] != 'category') ? 'oneItem' : 'category'; 
           } else $typeofData = 'oneItem';
           return $this->outputVariables($field, $tag, $style, $debug, $removefromarray); 
            //echo "$field, $tag = '', $style = '', $debug = true, $removefromarray = false";
        }
        
        function imageHandler($field, $thumbnail = false, $alt = '', $style = '', $debug = false, $removefromarray = true)
        {
            $oneItemFields = $this;//outputData::getInstance('oneItem', true);
            $data = $oneItemFields->getData();//the data must be still there, since the class was held in a static value.
            if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) { } 
            else {
               $data = $this->object_to_array( $data[0] ) ;
               if ($data === false) return;
            }
             if (!isset($data[$field]) && $debug === true) { echo "<p style='color: #f00; font-weight: bold;'>ERROR - field $field does not exist</p>"; return; }
            elseif (($data[$field]) == '') { return; }
            $imageData = unserialize($data[$field]);
            $alt = ($alt) ? $alt :  $data['item']; 
            if ($thumbnail === false) $imagedata = $imageData['image'];
            else $imagedata = $imageData['thumbnail'];
           // echo "<h2>line 403 $imagedata</h2>";
            echo "<img alt='$alt' src='".JURI::base(true).DS."images/".$imagedata."' ".trim($style)." />";
            if ($removefromarray === true) $oneItemFields->removefromData($field);//now remove the key from the array so echo_fields won't print it out

        }
        function dircron_imageURL($field, $alt, $style, $debug)
        {
           $data = $this->getData();
           if (!isset($data[$field]) && $debug === true) { echo "<p style='color: #f00; font-weight: bold;'>ERROR - field $field does not exist</p>"; return; }
           elseif (($data[$field]) == '') { return; }
           echo "<img alt='$alt' src='".$data[$field]."' ".trim($style)." />";
        }
       function ifFieldNotEmpty($field, $debug = false)
       {
            $data = $this->getData();//the data must be still there, since the class was held in a static value.
            if (!isset($data[$field]) || trim($data[$field]) == '') 
            { 

                if ($debug === true)  
               {
                    if (!isset($data[$field])) echo "<p style='color: #f00; font-weight: bold;'>ERROR - field $field does not exist</p>";
                    else echo "<p style='color: #f00; font-weight: bold;'>ERROR - field $field is empty</p>";
               }
                return false; 
            }
            else return true;
        }
        function echo_fields()
        {
            $oneItemFields = $this;//outputData::getInstance('oneItem', true);
            $data = $oneItemFields->getData();//items added by the user
            $dataTypes = $oneItemFields->getDataType();
            $itemfields = $oneItemFields->getMainItemFields();
            if ($data === false) return;
            //let's deal with the user-added fields now:
            if ($itemfields !== false) 
            {
                foreach ($itemfields as $item)//let's show the main fields. We'll make this simple:
                {
                    if ($item== 'id' || $item == 'published' || $item == 'metadesc ' || $item == 'meta_key') continue;
                    elseif ($item == 'image' || $item == 'thumbnail')
                    {
                        $thumb = ($item == 'thumbnail') ? true : false;
                        dircron_imageHandler($item, $thumb);
                    }
                    else echo ($data[$item]) ? "<div class='item'><b>".ucwords($item).":</b> ".$data[$item]."</div>" : '';
                }
                foreach ($data as $key => $value)//let's show the fields the user added. Once again, it'll be simple:
                { if (!in_array($key, $itemfields)) {
                    if ($dataTypes[$key] != 6 && $value) echo "<div class='item'><b>".ucwords($key)."</b>: $value</div>";
                    else { dircron_imageHandler($key);}}
                }}
            $oneItemFields->cleanData();
        }
        function dircron_getField($field, $debug = false)
        {
            $oneItemFields = $this;//outputData::getInstance('oneItem', true);
            $data = $oneItemFields->getData();//the data must be still there, since the class was held in a static value.
             if (!isset($data[$field])) 
            { 
                if ($debug === true)  {echo "<p style='color: #f00; font-weight: bold;'>ERROR - field $field does not exist</p>";} 
                return; 
            }
            elseif (trim($data[$field]) == '') { return; }
            else return $data[$field];
        }
}

//********************************************************************************************
// The functions below handle how the data is shown:
//********************************************************************************************
function explode_var($field)
{
     $typeofData = '';
     if (strpos($field, ':') !== false)
     {
         $field_split = explode(':', $field);
         $field = $field_split[1];
         $typeofData = $field_split[0];
         if (strtolower($typeofData) == 'oneitem') $typeofData = 'oneItem';
     } 
     $types = array('oneItem', 'category', 'subcategory');
     if (empty($typeofData) || !in_array($typeofData, $types))
     {
         echo "<h1 class='error'>ERROR - unrecognized function initializer, use ".implode(', or ', $types)." followed by a : and then your variable name</h1>";
         return false;
     }
     return array($typeofData, $field);
}
function dont_echo($field)//remove data so echo_fields won't show it
{
    $oneItemFields = outputData::getInstance('oneItem', true);
    $oneItemFields->removefromData($field);
}
            
//this function only handles directcron images:
function dircron_imageHandler($field, $thumbnail = false, $alt = '', $style = '', $removefromarray = true, $debug = false)
{
    $oneItemFields = outputData::getInstance('oneItem', true);
    $oneItemFields->imageHandler($field, $thumbnail, $alt, $style, $removefromarray, $debug);
}
function dircron_image($field, $alt = '', $style = '', $debug = true)
{
    $oneItemFields = outputData::getInstance('oneItem', true);
    $oneItemFields->dircron_imageURL($field, $alt, $style, $debug);
}
function ifFieldNotEmpty($field, $debug = false)
{  
    $oneItemFields = outputData::getInstance('oneItem', true);
    return $oneItemFields->ifFieldNotEmpty($field, $debug);
}
function dircron_getField($field, $debug = false)
{
    $oneItemFields = outputData::getInstance('oneItem', true);
    $oneItemFields->dircron_getField($field, $debug); 
}
function dircron_get($field, $tag = '', $style = '', $debug = true, $removefromarray = false)//print out a field before echo_fields does it
{
    list($type, $field) = explode_var($field);
    if (!$type) return;
    $oneItemFields = outputData::getInstance($type, true);
    return $oneItemFields->outputVar($field, $tag, $style, $removefromarray, $debug);
}
function dircron_shift($type)
{
    $oneItemFields = outputData::getInstance($type, true);
    $oneItemFields->shiftData();
}
function echo_fields()
{
    $oneItemFields = outputData::getInstance('oneItem', true);
    $oneItemFields->echo_fields();
}
?>
