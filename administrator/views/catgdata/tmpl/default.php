<p><b>The Category is: </b><select>
<?php foreach ($this->categories as $category) { echo "<option value='$category->id'>$category->category</option>"; } ?>
</select></p>
<table cellspacing="5" cellpadding="2">
 <?php if (is_array($this->fields)) { foreach ($this->fields as $fields) { ?>    
       <tr> <td>Item:</td><td><a class="editfield" id="editfield_<?php echo $fields->id; ?>" href='#id=<?php echo $fields->id; ?>'><?php echo $fields->name; ?></a></td> 
         <td>Type:</td><td><?php echo $fields->type; ?></td> 
         <td>Category:</td><td><?php echo $fields->category; ?></td></tr>
<?php }} ?>   
</table>
<div id="editfield"><div id="editfieldcontent"></div></div>
<script language="javascript">  cronframe.jQuery('a.editfield[href^=#]').click(function() { editFields(this); }); </script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
 
    <table><tr>
 <td>Find specific words in the category list:</td>
    <td>
              <input type="text" name="search" id="search" value="<?php echo $this->search;?>" class="text_area" onchange="document.adminForm.submit();" />
              <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					<button onclick="document.getElementById('search').value='';this.form.getElementById('sectionid').value='-1';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>

          </td></tr>
           
</table>
    <table>
          <table class="adminlist">

     <thead>
       <tr>
         <th width="50">
            <input type="checkbox" name="toggle"
                 value="" onclick="checkAll(<?php echo count( $this->categories ); ?>);" />
         </th>
          <th style="text-align: left;">Category</th>
           <th></th>

<th width="50">Published</th>
       </tr>
     </thead>
    <?php $r = 0; 
    if (is_array($this->ciddata)) {
    foreach ($this->ciddata as $item)
    {
        echo "<tr><td>";
         $checked = JHTML::_('grid.id', $r, $item->id ); 
       
        echo $checked;
        echo "</td><td width='30%'>$item->item</td>";
        echo "<td>$item->description</td>";
        echo "<td>";
        $published = JHTML::_('grid.published', $item, $r );
      echo $published;
       
       echo "</td>
  </tr>";  
        $r++;
    }}
    ?>
</table>
    <input type="hidden" name="view" value="catgdata" />
    <input type="hidden" name="option" value="com_directcron" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="boxchecked" value="0" />
   <input type="hidden" name="limitstart" value="" /> 
   
 
</form>
<div style="padding: 5px; margin: 5px;">
<?php
echo $this->pagination;
?>
</div>