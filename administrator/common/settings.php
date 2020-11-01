<?php
/******************************************************************************************
* JV-LinkDirectory - Advanced Directory and Partner Links Management Extension for Joomla!
* Copyright 2007-2011 JV-Extensions
* 
* This file is part of JV-LinkDirectory
* 
* JV-LinkDirectory is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* JV-LinkDirectory is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
* 
* @file version 4.6 RELEASE
* @author JV-Extensions
* @link http://www.jv-extensions.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL  
******************************************************************************************/
defined('_JEXEC') or die('Direct Access to this location is not allowed');  
#******************************************************************************************
/**
* Constants
*/
//echo "her ehere ".JPATH_COMPONENT.DS.'templates/default <----------------------------------------------------';
define("DRC_TMPL_BASEPATH", JPATH_COMPONENT.DS.'templates');

define("_MOS_ABS_TEMP", JPATH_ROOT.DS.'components'.DS.'com_jvse'.DS.'temp');

define("_MOS_IMGDIR", JURI::root()."components/com_jvse/assets/images/");
define("_MOS_STYLES", JURI::root().'media/com_jvse/styles/');
define("_MOS_BANNERS", JURI::root().'media/com_jvse/images/banners/');
define("_MOS_TBCACHE", JURI::root().'media/com_jvse/images/tbcache/');
define("_MOS_TEMP", JURI::root()."components/com_jvse/temp/");

define("_JVSE_DEBUG", 0);

define("_JVSE_CONFIG_CORE", 1);
define("_JVSE_CONFIG_DIRHOME", 2);
define("_JVSE_CONFIG_CATPAGES", 3);
define("_JVSE_CONFIG_PROFILES", 4);
define("_JVSE_CONFIG_ONEWAY", 5);
define("_JVSE_CONFIG_TWOWAY", 6);
define("_JVSE_CONFIG_DETAIL", 7);
define("_JVSE_CONFIG_SEARCH", 8); 
define("_JVSE_CONFIG_LATLINKS", 9); 
define("_JVSE_CONFIG_FELINKS", 10); 
define("_JVSE_CONFIG_ADVERTISE", 11);
define("_JVSE_CONFIG_RECAWEBSITE", 12);
define("_JVSE_CONFIG_HITSTRACKING", 13);
define("_JVSE_CONFIG_SITERATING", 14);
define("_JVSE_CONFIG_LINKREVIEW", 15);
define("_JVSE_CONFIG_CHECKLINKSTATUS", 16);  
define("_JVSE_CONFIG_MISC", 17); 
define("_JVSE_CONFIG_GPRATR", 18); 
define("_JVSE_CONFIG_EMAILS", 20); 
define("_JVSE_CONFIG_ALPHABAR", 21);   
define("_JVSE_CONFIG_MYLINKS", 22);
define("_JVSE_CONFIG_RECYCLEBIN", 23);
define("_JVSE_CONFIG_LINKACTIONS", 24);
define("_JVSE_CONFIG_SOCIALBK", 25);
define("_JVSE_CONFIG_ANTISPAM", 26);
define("_JVSE_CONFIG_SUGGESTCAT", 27);
define("_JVSE_CONFIG_SEF", 28);
define("_JVSE_CONFIG_DIRSTATS", 29);
define("_JVSE_CONFIG_GOOGLEMAPS", 30);
define("_JVSE_CONFIG_RSS", 31);
define("_JVSE_CONFIG_LOWNOTIFY", 32);
define("_JVSE_CONFIG_CSPONSOR", 33);
define("_JVSE_CONFIG_STW", 34); 
define("_JVSE_CONFIG_TWITTER", 35); 
define("_JVSE_CONFIG_JOMSOCIAL", 36); 
define("_JVSE_CONFIG_CBUILDER", 37); 
define("_JVSE_CONFIG_PHOTOGALLERY", 38); 
define("_JVSE_CONFIG_VIDEOGALLERY", 39);
define("_JVSE_CONFIG_LIKEUNLIKE", 40);
define("_JVSE_CONFIG_LINKACTIVITIES", 41);
define("_JVSE_CONFIG_PAYMENTS", 42); 
define("_JVSE_CONFIG_ALIASURLS", 43);  

define("_ESTABLISHED", 0);
define("_PARTNER_PENDING", 1);
define("_WEBMASTER_PENDING", 2);
define("_RECYCLED", 3);
define("_UNKNOWN", 4); 

define("_PAGE_DIRHOME", 1);      // default
define("_PAGE_CATEGORY", 2);
define("_PAGE_TELLFRIEND", 3);   
define("_PAGE_CLSTATUS", 4);
define("_PAGE_RECWEB", 5);
define("_PAGE_NOTIFY", 6);
define("_PAGE_THANKYOU", 7);  
define("_PAGE_LINKDET", 8);    
define("_PAGE_FELINKS", 9);    
define("_PAGE_SUGGESTCAT", 10);
define("_PAGE_ADDFEATURED", 11);
define("_PAGE_ADDLINK", 12);
define("_PAGE_ALPHABAR", 13);
define("_PAGE_CLAIMLINK", 14);
define("_PAGE_MANAGELINKS", 15);
define("_PAGE_NEWREVIEW", 16);
define("_PAGE_BROKENLINK", 17);
define("_PAGE_SEARCHRESULTS", 18);
define("_PAGE_SETFEATURED", 19);
define("_PAGE_SHOWCODE", 20);
define("_PAGE_REVIEWS", 21);
define("_PAGE_CSPONSOR", 22);

define("_NORTH", 1);
define("_SOUTH", 2);

define("_GPR_UPDATE_INTERVAL", 90);
define("_ATR_UPDATE_INTERVAL", 7);

define("_THUMBNAIL_CATEGORY_SIZE", 48);
define("_JVLD_TP_ICONSIZE_W", 148);
define("_JVLD_TP_ICONSIZE_H", 148);

define("_JVLD_STW_SFX_CATPAGE", 0);
define("_JVLD_STW_SFX_FLBLOCK", 1);
define("_JVLD_STW_SFX_FLPAGE", 2);
define("_JVLD_STW_SFX_MYLINKS", 3);
define("_JVLD_STW_SFX_LINKDET", 4);
define("_JVLD_STW_SFX_SRCRES", 5);    // default
define("_JVLD_STW_SFX_MODULE", 99);

define("_JVLD_JS_AVATAR_X_LISTING", 48);
define("_JVLD_JS_AVATAR_X_DETAIL", 64);
define("_JVLD_CB_AVATAR_X_LISTING", 48);
define("_JVLD_CB_AVATAR_X_DETAIL", 64);

define("_JVLD_EFMAINTAIN_CNT", 100);
define("_JVLD_GALLERYTHUMBGENERATION_CNT", 25);
define("_JVLD_GALLERY_THUMBNAIL_QUALITY", 50);
define("_JVLD_FOOTERLINKS_NUMCATS_PER_ROW", 26);

define("_JVLD_SHOW_DEFAULT_CATEGORY_IMAGE", 1);

#******************************************************************************************
/**
* Configuration Class
*/
class DRCronSettings {
    private static $_instance = null; 
    private $_config = array();
       
    private function __construct() {
        $dcron = new CronDb();
        $rows = $dcron->getRows("select * from `#__directcron_settings`");
        // return $rows;
       
        //$rows = JvseDb::getRows("select * from `#__drcron_settings`");
        foreach ($rows as $cfg) {
            $this->_config[$cfg->fieldname] = $cfg->fieldvalue;
        }    
    }
    
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new DRCronSettings;  
        }
        
        return self::$_instance;
    }
    
    public function get($var) { 
        return $this->_config[$var];        
    }
}
#******************************************************************************************
/**
* Security Class
*/

#******************************************************************************************
/** 
* Session class
*/
abstract class JvseSession {
    public function get($varname, $defval, $namespace) {
        $session =& JFactory::getSession();
        return $session->get($varname, $defval, $namespace);
    }

    public function set($varname, $val, $namespace) {
        $session =& JFactory::getSession();
        $session->set($varname, $val, $namespace);    
    }

    public function clear($varname_array, $namespace) {
        $session =& JFactory::getSession();
        for ($i=0;$i<count($varname_array);$i++)
            $session->clear($varname_array[$i], $namespace);
    }
}
#******************************************************************************************
abstract class JvseGlobalUtil {
    public function getItemID() {
        $row = JvseDb::getRow("select id from #__menu where link = 'index.php?option=com_jvse&view=jvse' and published = '1'");
        return ($row) ? $row->id : 0;
    }    
}
#******************************************************************************************
