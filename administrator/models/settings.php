<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');

class dccrModelPoints extends JModelLegacy
{
    var $dcron;
    var $drcc;
    public function __construct() 
    { 
        parent::__construct(); 
        $this->dcron = new CronDb(); 
    }
    public function getParams($table, $path)
    {
        error_reporting(E_ALL & ~(E_DEPRECATED | E_STRICT)); 
        $instance = new JRegistry($table->params, $path);
        return $instance;
    }
}