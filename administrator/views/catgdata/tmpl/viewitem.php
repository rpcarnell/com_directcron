<?php
if ($this->add_data == 1) {
?>
<script> 

function catgChange(){
  var myselect = document.getElementById("selectCategory");
  var catig = myselect.options[myselect.selectedIndex].value;
  
  location.href="index.php?option=com_directcron&view=catgdata&task=addItem&id="+catig;
}
</script>

<form><select onChange="catgChange()" id="selectCategory">
<?php foreach ($this->categories as $category) { 
    $selected = ($this->cid == $category->id) ? "selected='selected'": ''; 
    echo "<option $selected value='$category->id'>$category->category</option>"; } ?>
</select></form>
<br />
<?php
}


if ($this->cid> 0 || $this->add_data == 0)
{
    

?>
<br/> 
 
<form action="index.php" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
 <?php
     $this->dispatcher->trigger('beforeEditItemDisplay', array(   $this->itemdata,  $this->extrafields) );
 ?>
 
 
          <table class="adminlist">

     <thead>
       <tr>
         <th width="50" style="text-align: left;">Values:</th>
          <th width="550" style="text-align: left;">Field:</th>
           

 
       </tr>
     </thead>
     <tr><td>Item: </td><td><input type='text' name='item' value='<?php echo (isset($this->itemdata->item)) ? $this->itemdata->item : '';?>' maxlength="255" size="100" /></td></tr>
       <tr><td>Category:</td><td><select name="category">
<?php foreach ($this->categories as $category) { 
    $selected = ($this->cid == $category->id) ? "selected='selected'": ''; 
    echo "<option $selected value='$category->id'>$category->category</option>"; } ?>
</select>
 <?php $default_thumb = unserialize($this->itemdata->image); ?>
           </td>
        </tr>
        <tr><td>Description: </td><td><textarea cols="200" rows="5" name="description"><?php echo (isset($this->itemdata->description)) ? $this->itemdata->description : '';?></textarea></td></tr>
        <tr><td valign="top">Image: </td>
            <td><img style="margin-left: 50px;" src="<?php echo JURI::root(true)."/images/".$default_thumb['image']?>" alt="<?php echo $it->item;?>" />
          <div style="float: left"><p>Upload a new image:<br /><input type="file" name="upload_file" /></p></div>
          </td></tr>
        <tr><td>Meta Keywords: </td><td><textarea cols="100" rows="3" name="meta_key"><?php echo (isset($this->itemdata->meta_key)) ? $this->itemdata->meta_key : '';?></textarea></td></tr> 
        <tr><td>Meta Description: </td><td><textarea cols="100" rows="3" name="metadesc"><?php echo (isset($this->itemdata->metadesc)) ? $this->itemdata->metadesc : '';?></textarea></td></tr> 
        <?php
            if (is_array($this->extrafields))//time to add the extra fields:
            { 
                foreach($this->extrafields as $extrafield) {  
                $fieldtype = $extrafield->type;
                /* let's deal with image inputs first: */
            if ($fieldtype == 6) { $formfield = "<div style='float: left'><input type='file' name='".$extrafield->name."' value='Insert Image' maxlength='255' size='100'></div>"; }    
            else if ($fieldtype != 5) $formfield = "<input type='text' name='".$extrafield->name."' value='".(($extrafield->givenvalue) ? $extrafield->givenvalue : '')."' maxlength='255' size='100'>";
            else $formfield = "<textarea cols='200' rows='5' name='".$extrafield->name."'>".(($extrafield->givenvalue) ? $extrafield->givenvalue : '')."</textarea>";
            //some fields may be images:
            if ($extrafield->type == 6 && trim($extrafield->givenvalue))//if there's an image, let's show it:
            {
                $fieimages = unserialize($extrafield->givenvalue);
                if (is_array($fieimages) && trim($fieimages['thumbnail']))
                { $formfield .= '<div style="float: left; margin-left: 20px; padding: 5px; border: 1px solid #ddd;">';
                $formfield .= "<img src='".JURI::root().'images'.DS.$fieimages['thumbnail']."' alt='image thumbnail' />";
                $formfield .= "</div>";
            }}
        ?>
            <tr><td><?php echo ucwords($extrafield->name); ?>: </td><td><?php echo  $formfield;?></td>  </tr> 
        <?php   } } ?>
           <tr><td>Template File:</td><td><select name="templatefile">
                <?php
                
                foreach ($this->files as $fil)
                {
                    if ($this->itemdata->templatefile == $fil)  $selected = 'selected';
                    else $selected = '';
                    echo "<option value='$fil' $selected>$fil</option>";
                }
                ?>
                
                </select></td></tr> 
        <tr><td>Published: </td><td>
        <?php 
        if (isset($this->itemdata->published) && $this->itemdata->published ==1) $checked = 'checked=checked';
        else $checked = 0;
        echo "<input type='checkbox' $checked name='published' />"; ?>
       
      </td> </tr><?php 
     
    ?>
</table>
    <?php
     $this->dispatcher->trigger('afterEditItemDisplay', array( $this->itemdata,  $this->extrafields) );
    ?>
    <input type="hidden" name="view" value="catgdata" />
    <input type="hidden" name="id" value="<?php echo (isset($this->itemdata->id)) ? $this->itemdata->id : ''; ?>" />
    <input type="hidden" name="option" value="com_directcron" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
   
    <input type="hidden" name="add_data" value="<?php echo $this->add_data;?>" />
    
</form>
<?php
} 
?>


