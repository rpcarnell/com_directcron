<?php
defined('_JEXEC') or die('Restricted access');
class DRCSettings
{
    var $dcron;
    var $catgfiles = array('category', 'items');//files needed to show category data inside a template
    var $oneitemfiles = array('oneitem');
    function getInstance( $here )
    {
        static $instances;
        if (!isset( $instances )) {$instances = array();}
        $signature = base64_encode( $here );
        if (empty($instances[$signature]))
        {
            $instance = new DRCSettings();
            $instances[$signature] = & $instance;
        }
        return $instances[$signature];
    }
    function __construct() { $this->dcron = CronDb::getInstance( 'crondb'); }
    public function getDirectoryFiles()
    {
        $dir = JPATH_ROOT.DS.$this->getTemplatenDir();       
        if (!is_dir($dir)) { echo "<p style='color: #f00;'><strong>ERROR - DIRECTORY DOES NOT EXIST</strong></p>"; return false; }  
        if ($handle = opendir($dir)) 
                {
                    $files = array();
                    $a = 0;
                    while (false !== ($entry = readdir($handle))) 
                        { 
                            
                            if (str_replace('.', '', $entry) ) { $exclude = false; 
                                foreach ($this->oneitemfiles as $oneitem)
                                {
                                    if ($oneitem == $entry) { $exclude = true; }
                                     
                                }
                                if ($exclude === false) {  
                                    $files[$a] = str_replace('.php', '', $entry); 
                                    $a++;
                                } }
                       }
                    closedir($handle);
                    /*if (! */$this->verifyCatgTemplate($files) ; /*return false;*/
                    
                    /*else*/ return $files;
                }
    }
    private function verifyCatgTemplate($files)//make sure the template has the necessary files needed for categories to show up properly
    {
        if (!is_array($files))
        {
            echo "<p style='color: #f00;'><strong>ERROR - IMPOSSIBLE TO VERIFY TEMPLATE FILES</strong></p>"; return false;
        }
        $template_files = $this->catgfiles;//array('assignedtasks.php', 'items.php');
        foreach ($template_files as $fil)
        {
            if (!in_array($fil, $files)) {
                 echo "<p style='color: #f00;'><strong>ERROR - FILE $fil IS MISSING. TEMPLATE CANNOT RUN WITHOUT IT!</strong></p>"; return false; 
            }
        }
        return true;
    }
    public function getTemplatenDir()
    {
        return $this->getTemplateDir().DS.$this->getTemplate();
    }
    public function getTemplateDir()
    {
        $query = "SELECT fieldvalue FROM #__directcron_settings WHERE fieldname = 'template_dir' LIMIT 1";
        $result = $this->dcron->getOneValue($query);
        return (trim($result)) ? $result : 'components/com_directcron/templates';
    }
    public function getTemplate()
    {
        $query = "SELECT fieldvalue FROM #__directcron_settings WHERE fieldname = 'style_dir' LIMIT 1";
        $result = $this->dcron->getOneValue($query);
        return (trim($result)) ? $result : 'default';
    }
    public function getParams()
    {
        $params = & JComponentHelper::getParams("com_directcron");
        return $params;
    }
    
}
?>
