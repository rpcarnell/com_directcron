    <?php
defined('_JEXEC') or die;
//show the comments:
$params = $this->com_params;
//print_r($params);
$r = 0;
//comments will show up according to parameters provided by the user
if ( ($this->commentfor == 'oneitem' && $params->get('showcommentsitem')) || ($this->commentfor == 'items' && $params->get('showcommentscatg')) ) { 
foreach ($this->comments as $cmt)
{
    $kind = ($r % 2) ? '2' : '1';
    
?><div style="clear: both;"></div>
<div id="itemcomments_<?php echo $kind;?>" class="itemComment">
<p><b>Username: </b><?php echo $cmt->username;?><br />
    <b>E-Mail: </b><a href="mailto:'<?php echo $cmt->user_email;?>'"><?php echo $cmt->user_email;?></a><br />
<?php if ($cmt->user_URL) {?> 
    <b>URL: </b><a href="<?php echo $cmt->user_URL; ?>"><?php echo $cmt->user_URL;?></a><br />
<?php } 
echo $cmt->comment;
?>
</p>    
</div><br />
<?php
$r++;
}}

//form will show up according to parameters provided by the user
if ( ($this->commentfor == 'oneitem' && $params->get('showitemcommentform')) || ($this->commentfor == 'items' && $params->get('showcatgcommentform'))) { 
?>
<h3><?php echo JText::_('DIRCRON_LEAVE_A_COMMENT') ?></h3>
<form action="<?php echo JURI::root(true); ?>/index.php" method="post" id="dircomment-form">
	<label class="formComment"><?php echo JText::_('DIRCRON_MSG'); ?> *</label><br />
	<textarea rows="10" cols="60" class="inputbox" onblur="if(this.value=='') this.value='<?php echo JText::_('DIRCRON_ENTER_MSG'); ?>';" onblur="if(this.value=='') this.value='<?php echo JText::_('DIRCRON_ENTER_MSG'); ?>';" onfocus="if(this.value=='<?php echo JText::_('DIRCRON_ENTER_MSG'); ?>') this.value='';" name="commentText" id="commentText"><?php echo JText::_('DIRCRON_ENTER_MSG'); ?></textarea><br />

	<label class="formName"><?php echo JText::_('DIRCRON_NAME'); ?> *</label>
	<input class="inputbox" type="text" name="username" id="userName" value="<?php echo JText::_('DIRCRON_ENTER_NAME'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('DIRCRON_ENTER_NAME'); ?>';" onfocus="if(this.value=='<?php echo JText::_('DIRCRON_ENTER_NAME'); ?>') this.value='';" /><br />

	<label class="formEmail"><?php echo JText::_('DIRCRON_EMAIL'); ?> *</label>
	<input class="inputbox" type="text" name="commentoremail" id="commentEmail" value="<?php echo JText::_('DIRCRON_EMAIL'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('DIRCRON_EMAIL'); ?>';" onfocus="if(this.value=='<?php echo JText::_('DIRCRON_EMAIL'); ?>') this.value='';" /><br />

	<label class="formUrl"><?php echo JText::_('DIRCRON_COMMTURL'); ?></label>
	<input class="inputbox" type="text" name="commentorURL" id="commentURL" value="<?php echo JText::_('DIRCRON_ENTER_SITE'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('DIRCRON_ENTER_SITE'); ?>';" onfocus="if(this.value=='<?php echo JText::_('DIRCRON_ENTER_SITE'); ?>') this.value='';" /><br />

	<?php  if($params->get('commentreCAPTCHA')): 
            include_once('recaptcha.php');
        else:
             echo '<div id="recaptcha_div"></div><br />';
            endif; ?>

	<input type="submit" class="button" id="submitComment" value="<?php echo JText::_('DIRCRON_SUBMIT_COMMENT'); ?>" />

	<span id="formLog"></span>

	<input type="hidden" name="option" value="com_directcron" />
	<input type="hidden" name="view" value="comments" />
	<input type="hidden" name="task" value="usercomment" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
        <input type="hidden" name="id" value="<?php echo JRequest::getInt('id'); ?>" />
        <input type="hidden" name="user_id" value="<?php echo $this->userid; ?>" />
        
        
	<?php echo JHTML::_('form.token'); ?>
</form><br />
<?php } ?>