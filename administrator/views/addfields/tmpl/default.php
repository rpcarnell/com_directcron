<script>
function catgChange(){
  var myselect = document.getElementById("selectCategory");
  var catig = myselect.options[myselect.selectedIndex].value;
  
  location.href="index.php?option=com_directcron&view=addfields&id="+catig;
}
</script>

<p><form>
  
<select onChange="catgChange()" id="selectCategory">
<?php foreach ($this->categories as $category) {
    if ($this->id == $category->id) $selected = "selected='selected'";
    else $selected = '';
    echo "<option $selected value='$category->id'>$category->category</option>\n"; 
    
} 

$catid = isset($_GET['id']) ? $_GET['id'] : 'none';
?>
</select></form></p>
<p><?php echo JText::_('CATEGORYIS').": <b>".$this->drcc->getCategoryName($this->id); ?></b> </p>
<p><a class="editfield" id="editfield_add" href='#catid=<?php echo $catid;?>'><?php echo JText::_('ADD_DIRECTCRON_FIELD'); ?></a></p>
<form action="index.php" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
<table cellspacing="5" cellpadding="2" class="adminlist" style="width: 80%">
 <?php if (is_array($this->fields)) { $r = 0; ?>
     

     <thead>
       <tr>
           <th style="text-align: left;">Checkbox:</th>
         <th style="text-align: left;">Item:</th>
          <th style="text-align: left; width: 10%;">Type:</th>
           <th style="text-align: left; width: 20%;">Category:</th>
           <th width="50">Published</th>
 </tr>
     </thead>
         <?php
         $r = 0;
   foreach ($this->fields as $fields) {
       $class = ($fields->category == $catid) ? 'style="background: #faa; width: 10%;"' : 'style="background: #fff; width: 10%;"';
       ?>    
        
           <tr><td <?php echo $class; ?>>
       <?php   $checked = JHTML::_('grid.id', $r, $fields->id ); 
        echo $checked;?>
        </td>
           <td><a class="editfield" id="editfield_<?php echo $fields->id; ?>" href='#id=<?php echo $fields->id; ?>'><?php echo $fields->name; ?></a></td> 
        <td><?php echo $this->drcFields->getFieldType($fields->type); ?></td> 
         <td><a href='index.php?option=com_directcron&view=addfields&id=<?php echo $fields->category; ?>'><?php echo $this->drcc->getCategoryName($fields->category); ?></a></td>
    <td><?php
        $published = JHTML::_('grid.published', $fields, $r );
      echo $published;
       ?>
        </td>
       </tr>
<?php $r++; }
     
} 
    if ($r == 0 && !is_array($this->fields)) echo "<tr><td>".NOFIELDSFORCATG."</td></tr>";
?>   
</table>
    <input type="hidden" name="view" value="addfields" />
    <input type="hidden" name="id" value="<?php echo $this->id;?>" />
    <input type="hidden" name="option" value="com_directcron" />
    <input type="hidden" name="boxchecked" value='0' />
    <input type="hidden" name="task" value="" />
   
</form>
<div id="editfield"><div id="editfieldcontent"></div></div>
<script language="javascript"> 
   cronframe.jQuery('a.editfield[href^=#]').click(function() { editFields(this); }); //edit Field popup activates when you click 
</script>
