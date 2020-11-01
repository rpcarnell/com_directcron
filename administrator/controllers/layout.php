<?php
class DirectCronControllerLayout extends DirectCronController
{
    /**
	 * Constructor
	 **/
	function __construct() { parent::__construct(); }
        function display()
        {   
            $dcron = new CronDb();
            $viewName	= JRequest::getCmd( 'view' , 'directcron' );
            $views = array('about');
            if (!in_array($viewName, $views)) $viewName = 'layout';
            $document = & JFactory::getDocument();
            $document->addStyleSheet(JURI::base(true).'/components/com_directcron/css/styles.css');
            $path = JPATH_ADMINISTRATOR . DS . 'components/com_directcron/config_2.xml';
            if (!is_file($path)) { echo "<div class='warning'>Unable to find file config_2.xml. Exiting!</div>"; return; }
            else $xmlData = simplexml_load_file($path);
           // print_r($xmlData);
            $params = $this->getParams();
            $registry = new JRegistry($params);
            
 
            $selectTempl = $this->getTemplatesList($registry->get('dircron_template'));
            $mainPage = $this->selectMainPage($registry, $xmlData);//$this->getTemplateFiles($registry);
            $document = & JFactory::getDocument();
            $viewType = $document->getType();
            $view = &$this->getView($viewName, $viewType);
            if ($dcron->getRowsAffected() > 0 ) {
                $view->assign('registry', $registry);
                $view->assign('xmlData', $xmlData);
                $view->assign('selectTempl', $selectTempl);
                $view->assign('mainPage', $mainPage);
                $view->assign('layout', $this);
                
            }
            $view->display();
        }
        private function getParams()
        {
            $table = & JTable::getInstance('extension');
		if (!$table->load(array("element" => "com_directcron", "type" => "component"))) // 1.6 mod
		{
			JError::raiseWarning(500, 'Not a valid component');
			return false;
		}
            return $table->params;
        }
        function save()
	{
		$table = & JTable::getInstance('extension');
                if (!$table->load(array("element" => "com_directcron", "type" => "component"))) // 1.6 mod
		{
			JError::raiseWarning(500, 'Not a valid component');
			return false;
		}
                $registry = new JRegistry($table->params);
                if (isset($_POST['lform'])) 
                    { foreach( $_POST['lform'] as $key => $value ) { $registry->setValue($key , $value ); } }
                $table->params = $registry->toString();
                if(!$table->store() ) { echo "<div id='dircron_fail'>".JText::_('DIRCRON_SAVE_FAIL')."</div>"; }
                else echo "<div id='dircron_success'>".JText::_('DIRCRON_SAVE_SUCCESS')."</div>";
                $this->display();
	}
        public function applySettings()
        {
            $this->save();
        }
        private function getTemplatesList( $default = '' )
	{
		$path	= dirname(JPATH_BASE) . DS . 'components' . DS . 'com_directcron' . DS . 'templates';
                $selected = '';
	        if( $handle = @opendir($path) )
		{
			while( false !== ( $file = readdir( $handle ) ) )
			{  if( $file != '.' && $file != '..') $templates[]	= $file; }
		}
		$html	= '<select name="lform[dircron_template]">';
                foreach( $templates as $template )
		{
			if( $template )
			if( !empty( $default ) )
			{ $selected	= ( $default == $template ) ? ' selected="true"' : ''; }
			$html	.= '<option value="' . $template . '"' . $selected . '>' . $template . '</option>';
		}
		$html	.= '</select>';
                return $html;
	}
        private function getTemplateFiles(& $registry)
        {
            $path	= dirname(JPATH_BASE) . DS . 'components' . DS . 'com_directcron' . DS . 'templates'. DS . $registry->getValue('dircron_template');
            $frontpage = $registry->getValue('dircron_frontpage');
            $notAvailable = false;
            $html	= '<select name="lform[dircron_frontpage]">';
            if( $handle = @opendir($path) )
            {
                    while( false !== ( $file = readdir( $handle ) ) )
                    {  
                       if (strpos($file, ".php") !== false) 
                       {
                           $file2 =  str_replace('.php', '', $file);
                           $selected = ($frontpage == $file) ? "selected='selected'" : '';
                           if ($selected) $notAvailable = true;
                           $html	.= '<option value="' . $file . '"' . $selected . '>' .$file2 .'</option>';
                       }
                   }
             }
             $html	.= '</select>';
            if (!$notAvailable) $html .= " <span class='warning'>".sprintf(JText::_('FILENOTINTEMPLATE'), $frontpage)."</span>"; 
            return $html;
        }
        private function selectMainPage($registry, $xmlData)
        {
            $html	= '<select name="lform[dircron_frontpage]">';
            $frontpage = $registry->get('dircron_frontpage');
            foreach($xmlData->DIRCRON_FRONTPAGE->frontpage as $value)
            {  
               $selected = ($frontpage == $value->value) ? "selected='selected'" : '';
               $html .= "<option $selected value='$value->value'>$value->name</option>";
            }
            $html	.= '</select>';
            return $html;
        }
        
            
}
?>
