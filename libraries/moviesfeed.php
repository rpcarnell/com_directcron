<?php
/**
* @version		$Id: index.php 11407 2009-01-09 17:23:42Z willebil $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__)  );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$mainframe =& JFactory::getApplication('site');

/**
 * INITIALISE THE APPLICATION
 *
 * NOTE :
 */
// set the language
$mainframe->initialise();

JPluginHelper::importPlugin('system');

// trigger the onAfterInitialise events
JDEBUG ? $_PROFILER->mark('afterInitialise') : null;
$mainframe->triggerEvent('onAfterInitialise');

/**
 * ROUTE THE APPLICATION
 *
 * NOTE :
 */
$mainframe->route();

//JPATH_BASE .DS.
include( JPATH_BASE .DS.'includes'.DS.'feedcreator.class.php');

$rss = new UniversalFeedCreator();
$rss->useCached(); // use cached version if age<1 hour
/*$format = "ATOM";
$rss->_setMIME($format);
$rss->_setFormat($format);*/
$rss->title = "MyDVDTrader Movie Feed";
$rss->description = "Check all the movies you can trade with us";

//optional
$rss->descriptionTruncSize = 500;
$rss->descriptionHtmlSyndicated = true;

$rss->link = "http://www.mydvdtrader.com/";
$rss->syndicationURL = "http://www.mydvdtrader.com/".$_SERVER["PHP_SELF"];

$image = new FeedImage();
$image->title = "MyDVDTrader logo";
$image->url = "http://www.mydvdtrader.com/templates/gk_memovie/images/logo_light.png";
$image->link = "http://www.mydvdtrader.com/";
$image->description = "Feed provided by MyDVDTrader. Click to visit.";

//optional
$image->descriptionTruncSize = 500;
$image->descriptionHtmlSyndicated = true;

$rss->image = $image;

// get your news items from somewhere, e.g. your database:
$db	   =& JFactory::getDBO();
$query = 'SELECT * FROM #__jmovies ORDER BY id DESC LIMIT 400';
$db->setQuery($query);
$rows = $db->loadObjectList();
//print_r($rows);
foreach ($rows as $data) {
	$item = new FeedItem();
	$item->title = ( str_replace('\\', '', $data->titolo));
        //$item->category = 'horror';
        $item->comments = substr(str_replace('\\', '', strip_tags($data->metadesc)), 0 ,500);
	$item->link = JRoute::_("http://www.mydvdtrader.com/index.php?option=com_jmovies&Itemid=300028&task=detail&id=$data->id"); //$data->url;
	$item->description = utf8_encode (substr(str_replace('\\', '', strip_tags($data->descrizione)), 0 ,500) );//$data->short;
            $item->image = "<img src='http://www.mydvdtrader.com/components/com_thumbnails/img_pictures/$data->filename' alt='$data->titolo' />";
	//echo "<img src='http://www.mydvdtrader.com/components/com_jmovies/img_thumbnails/$data->thumbname' alt='$data->titolo' />";
	$item->descriptionTruncSize = 500;
    $item->descriptionHtmlSyndicated = true;
    /*
     *
     *         						<img src="http://www.mydvdtrader.com/components/com_jmovies/img_pictures/marmaduke.jpg
     */
/*
    //optional (enclosure)
    $item->enclosure = new EnclosureItem();
    $item->enclosure->url='http://http://www.dailyphp.net/media/voice.mp3';
    $item->enclosure->length="950230";
    $item->enclosure->type='audio/x-mpeg';
*/


	$item->date = $data->newsdate;
	$item->source = "http://www.mydvdtrader.com";
	$item->author = "MyDVDTrader";

	$rss->addItem($item);
}

// valid format strings are: RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
// MBOX, OPML, ATOM, ATOM10, ATOM0.3, HTML, JS
$rss->saveFeed("RSS1.0", "news/feed.xml");//this one stores the feed as xml

//to generate "on-the-fly"
//$rss->outputFeed("RSS1.0");

?>