<form action="index.php" method="post" name="adminForm" id="adminForm">
 <?php $lists = $this->lists;?>
    <table><tr>
 <td>Find specific words in the category list:</td>
    <td>
              <input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
              <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					<button onclick="document.getElementById('search').value='';this.form.getElementById('sectionid').value='-1';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>

          </td></tr>
           
</table>
 
          <table class="adminlist">

     <thead>
       <tr>
         <th width="50">
           <?php if (is_array($this->categories)) { ?> <input type="checkbox" name="toggle"
           value="" onclick="checkAll(<?php echo count( $this->categories ); ?>);" /><?php } ?>
         </th>
          <th style="text-align: left;">Category</th>
           <th></th>

<th width="50">Published</th>
       </tr>
     </thead>
    <?php $r = 0; 
    foreach ($this->categories as $category)
    {
        echo "<tr><td>";
         $checked = JHTML::_('grid.id', $r, $category->id ); 
       
        echo $checked;
        echo "</td><td width='30%'>$category->category (Sub-Categories)</td>";
        echo "<td>Edit Fields</td>";
        echo "<td>";
        $published = JHTML::_('grid.published', $category, $r );
      echo $published;
       
       echo "</td>
  </tr>";  
        $r++;
    }
    ?>
</table>
    <input type="hidden" name="view" value="category" />
    <input type="hidden" name="option" value="com_directcron" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    
 
</form>
