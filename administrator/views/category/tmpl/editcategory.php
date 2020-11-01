 
<form action="index.php" method="post" name="adminForm"  id="adminForm" enctype="multipart/form-data">
 <table width="100%"><tr><td width="70%" valign="top">
 
          <table class="adminlist">

     <thead>
       <tr>
         <th width="50" style="text-align: left;">Values:</th>
          <th width="550" style="text-align: left;">Field:</th>
           

 
       </tr>
     </thead>
    <?php $r = 0; 
   // foreach ($this->category as $category)
    {
         
        ?><tr><td>Category:</td><td><input type='text' name='category' size="50" value='<?php echo $this->category->category; ?>' /></td>
        </tr>
        <tr><td>Parent Category: </td><td><select name="parentcategory"><option value=""><?php echo JText::_('NONE');?></option>
                <?php
                foreach ($this->categories as $catg)
                {
                    if ($this->category->id == $catg->id) continue;
                    
                    if ($catg->id == $this->category->parentcategory) $selected = 'selected="selected"';
                    else $selected = '';
                    echo "<option $selected value='$catg->id'>$catg->category</option>";
                }
                ?>
                
                </select></td></tr>    
        <tr><td>Template File:</td><td><select name="templatefile">
                    <option value=""><?php echo JText::_('NONE');?></option>
                <?php
                
                foreach ($this->files as $fil)
                {
                    if ($this->category->templatefile == $fil)  $selected = 'selected';
                    else $selected = '';
                    echo "<option value='$fil' $selected>$fil</option>";
                }
                ?>
                
                </select></td></tr>
        <tr><td>Category Description:</td><td><textarea cols="150" rows="5" name="description"><?php echo $this->category->description;?></textarea></td></tr> 
        <tr><td>Image: </td><td valign="top"> 
            <?php $default_thumb = unserialize($this->category->categoryimage); 
                   if ($default_thumb['thumbnail']) { ?>
                <img style="margin-left: 50px;" src="<?php echo JURI::root(true)."/images/".$default_thumb['thumbnail']?>" alt="category_thumbnail" />
                <?php } ?>
            <input type="file" name="categoryimage" value="" />
            </td></tr>
        <tr><td>Published: </td><td>
        <?php 
        if ($this->category->published ==1) $checked = 'checked=checked';
        else $checked = 0;
        echo "<input type='checkbox' $checked name='published' />"; ?>
       
      </td> </tr><?php 
      $r++;
    }
    ?>
</table>
             <?php echo $this->add_data;?>
    <input type="hidden" name="view" value="category" />
    <input type="hidden" name="catid" value="<?php echo $this->category->id; ?>" />
    <input type="hidden" name="option" value="com_directcron" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="add_data" value="<?php echo $this->add_data;?>" />
         </td>
     <td valign="top">
					 
						 <div id="accordion">
                                                     <h3>Section 5</h3>
<div>
<p>
Cras dictum. Pellentesque habitant morbi tristique senectus et netus
et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
mauris vel est.
</p>
<p>
Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
inceptos himenaeos.
</p>
</div>
<h3><?php echo JText::_("CATEGORY_DATA"); ?></h3>
<div id="dsd">
 
	<fieldset class="panelform">
								<ul class="adminformlist">
									<?php foreach ($this->form->getFieldset('item-view-options-listings') as $field): ?>
									<li>
										<?php if($field->type=='header'): ?>
										<div class="paramValueHeader"><?php echo $field->input; ?></div>
										<?php elseif($field->type=='Spacer'): ?>
										<div class="paramValueSpacer">&nbsp;</div>
										<div class="clr"></div>
										<?php else: ?>
										<div class="paramLabel"><?php echo $field->label; ?></div>
										<div class="paramValue"><?php echo $field->input; ?></div>
										<div class="clr"></div>
										<?php endif; ?>
									</li>
									<?php endforeach; ?>
								</ul>
							</fieldset>						
							
</div>
<h3><?php echo JText::_("SUBCATEGORY_DATA");    ?></h3>
<div id="dsd">
 
	<fieldset class="panelform">
								<ul class="adminformlist">
									<?php   foreach ($this->form->getFieldset('subcatg-item-layout') as $field): ?>
									<li>
										<?php if($field->type=='header'): ?>
										<div class="paramValueHeader"><?php echo $field->input; ?></div>
										<?php elseif($field->type=='Spacer'): ?>
										<div class="paramValueSpacer">&nbsp;</div>
										<div class="clr"></div>
										<?php else: ?>
										<div class="paramLabel"><?php echo $field->label; ?></div>
										<div class="paramValue"><?php echo $field->input; ?></div>
										<div class="clr"></div>
										<?php endif; ?>
									</li>
									<?php endforeach; ?>
								</ul>
							</fieldset>						
							
</div>
<h3><?php echo JText::_("CATEGORYITEM_DATA"); ?></h3>
<div>
<fieldset class="panelform">
								<ul class="adminformlist">
									<?php foreach ($this->form->getFieldset('category-item-layout') as $field): ?>
									<li>
										<?php if($field->type=='header'): ?>
										<div class="paramValueHeader"><?php echo $field->input; ?></div>
										<?php elseif($field->type=='Spacer'): ?>
										<div class="paramValueSpacer">&nbsp;</div>
										<div class="clr"></div>
										<?php else: ?>
										<div class="paramLabel"><?php echo $field->label; ?></div>
										<div class="paramValue"><?php echo $field->input; ?></div>
										<div class="clr"></div>
										<?php endif; ?>
									</li>
									<?php endforeach; ?>
								</ul>
							</fieldset>


<h3>Section 2</h3>

<fieldset class="panelform">
								<ul class="adminformlist">
									<?php foreach ($this->form->getFieldset('item-image-options') as $field): ?>
									<li>
										<?php if($field->type=='header'): ?>
										<div class="paramValueHeader"><?php echo $field->input; ?></div>
										<?php elseif($field->type=='Spacer'): ?>
										<div class="paramValueSpacer">&nbsp;</div>
										<div class="clr"></div>
										<?php else: ?>
										<div class="paramLabel"><?php echo $field->label; ?></div>
										<div class="paramValue"><?php echo $field->input; ?></div>
										<div class="clr"></div>
										<?php endif; ?>
									</li>
									<?php endforeach; ?>
								</ul>
							</fieldset>

<h3>Section 3</h3>

<fieldset class="panelform">
								<ul class="adminformlist">
									<?php foreach ($this->form->getFieldset('category-view-options') as $field): ?>
									<li>
										<?php if($field->type=='header'): ?>
										<div class="paramValueHeader"><?php echo $field->input; ?></div>
										<?php elseif($field->type=='Spacer'): ?>
										<div class="paramValueSpacer">&nbsp;</div>
										<div class="clr"></div>
										<?php else: ?>
										<div class="paramLabel"><?php echo $field->label; ?></div>
										<div class="paramValue"><?php echo $field->input; ?></div>
										<div class="clr"></div>
										<?php endif; ?>
									</li>
									<?php endforeach; ?>
								</ul>
							</fieldset>
</div>

</div>
                                                 
       </td>
         
      
     </tr></table>

</form>