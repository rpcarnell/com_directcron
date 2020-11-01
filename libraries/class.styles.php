<?php
/******************************************************************************************
DirectCron - Advanced Directory and Partner Links Management Extension for Joomla!
* Copyright 20012-2014 Redacron-Extensions
* 
* This file is part of DirectCron
* 
* Directcron is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* Directcron is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
* 
* @author Redacron.com
* @link http://wwww.redacron.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL  
******************************************************************************************/
defined('_JEXEC') or die('Restricted access');
#************************************************************************************************************
class DRCStyles extends strFormat
{
    var $style_name;
    var $style_live_path;
    var $style_abs_path;
   
#************************************************************************************************************        
    function __construct($displayed_page_id=0, $catid=0)
    {
        $this->cfg = DRCronSettings::getInstance();
        $this->style_name = $this->cfg->get('style_dir'); 
        $this->style_live_path = _DRCRON_STYLES.$this->style_name."/style.css";
        $this->style_abs_path = DRC_TMPL_BASEPATH; //USED      
                
    }
    function getStyleFile() { return $this->style_live_path; }
    public function getTemplateDirectory()
    {
        $settings = DRCronSettings::getInstance();
        $style = $settings->get('style_dir');
        $style = ($style == '') ? 'default' : $style;
        return $style;
    }
    
#************************************************************************************************************         
    public function getTemplatePath() {
        $style = $this->getTemplateDirectory();
        return $this->style_abs_path.DS.$style;    
    }
}

