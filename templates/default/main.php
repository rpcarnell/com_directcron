<?php
if (is_array($this->categories)) {
foreach ($this->categories as $catg)
{ 
    echo "<p><b>Category:</b> <a href='".JRoute::_('index.php?option=com_directcron&view=items&task=viewItems&catid='.$catg->id)."'>".$catg->category."</a></p>";
}
}
?>