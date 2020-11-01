<div>
    <form action="index.php" method="post" name="adminForm" autocomplete="off" id="adminForm">
        <table width="100%" class="paramlist admintable" cellspacing="1">
        <tr>
            <?php
            foreach ($this->xmlData as $key => $value)
            {  
                 echo "<tr><td width=\"25%\" valign=\"top\" class=\"paramlist_label\"><span class=\"settingsLabel\">".JText::_($key)."</span></td>\n";
                 if ($key == 'DIRCRON_TEMPLATE') $value = $this->selectTempl;
                 elseif ($key == 'DIRCRON_FRONTPAGE') $value = $this->mainPage;
                 else $value = "<input type='text' name='lform[".strtolower($key)."]' value='".$this->registry->get(strtolower($key))."' />";
                 echo "<td class=\"paramlist_value\">$value</td></tr>\n"; 
            }
            ?>
          
        </tr>
        </table>
        <input type="hidden" name="view" value="layout" />
    <input type="hidden" name="option" value="com_directcron" />
    <input type="hidden" name="task" value="" />
    </form>
   
	</div>
<?php
//$this->layout->getTemplatesList();
?>