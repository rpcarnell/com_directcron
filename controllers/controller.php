<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');


class DRCController extends JControllerLegacy
{
    var $com_params;
    function __construct() { parent::__construct(); }
    public function paramData()//default category
    {
        $this->com_params = JComponentHelper::getParams( 'com_directcron' );
        $frontpage = $this->com_params->get('dircron_frontpage');
        
        switch ($frontpage)
        {   
            case 'categories_latest': 
              $this->categoriesList(2);
              return;
            case 'categories_alpha': 
              $this->categoriesList(1);
              return;  
            case 'items_alpha':
              $this->listItems(1);
              return;
            default:
              $this->listItems(2);
              return;  
        }
     }
    private function categoriesList($order)
    {  
        $modeltouse = &$this->getModel ( 'itemFetch', 'dccrModel');
        $view  = $this->getView  ( 'catglist','html' );
        $categories = $modeltouse->getAllCategories($order);
        $view->assign('categories', $categories);
        $view->display();
    }
    private function listItems()
    {  
        $modeltouse = $this->getModel ( 'itemFetch', 'dccrModel');
        $document = JFactory::getDocument();
        $viewType = $document->getType();
        
        $view = $this->getView  ( 'itemslist',$viewType);
        list($items, $limitstart, $pagination) = $modeltouse->getIntroItems($this->com_params, $viewType);
        $style = new DRCStyles();
        jscssScripts::jsInclude( 'com_directcron', 'templates/'.$style->getTemplateDirectory().DS.'css'.DS.'style.css');    
        $oneItemFields = outputData::getInstance('oneItem');//notice category. This is category data
        $oneItemFields->setData($items);
        $formattask = new strFormat(20);
       
        $view->assign('formatt', $formattask);
        $view->assign('params', $this->com_params);
        $view->assign('style', $style);
        $view->assign('items', $items);
        $view->assign('pagination', $pagination);
        $view->display();
    }
    function jmovies_categories()
    {
        $dcron = CronDb::getInstance( 'crondb');
        $sql = "SELECT * FROM `jos_jmovies_categories` group by catid order by ordering";
        $rows = $dcron->getRows($sql);
        foreach ($rows as $catg)
        {
            echo "<br />jmovies category is ".$catg->jmoviesid." and ".$catg->catid;
            $sql = "SELECT * FROM `jos_categories` where id = ".$catg->catid." LIMIT 1";
            $row = $dcron->getRow($sql);
           // print_r($row);
            echo "and that category is ".$row->title;
        
         $query ="INSERT INTO `mydvdtrader254`.`g2r5k4_directcron_categories` (
`id` ,
`category` ,
`parentcategory` ,
`templatefile` ,
`date_added` ,
`modified` ,
`modified_by` ,
`published`
)
VALUES (".$catg->catid." , '".$row->title."', '0', 'category', '".time()."', NULL , NULL , '1');";
         $dcron->insert($query);
         }
    }
    function jmovies()
    {
        $dcron = CronDb::getInstance( 'crondb');
        $sql = "SELECT a.*, b.catid as realcategory FROM `g2r5k4_jmovies` as a inner join jos_jmovies_categories as b ON b.jmoviesid = a.id";
        $rows = $dcron->getRows($sql);
        //print_r($rows[0]);
        //descrizion -> description
       // print_r($rows); return;
        foreach ($rows as $row)
        {
            //echo "<p>title is ".$row->titolo."</p>";
             
            $imageData['image'] = "directcron/img_pictures/".$row->filename;
             $imageData['thumbnail'] = "directcron/img_thumbnails/".$row->filename;
             //$imageData_2 = array();
             $imageData_2 = serialize($imageData);
            $sql = "INSERT INTO #__directcron_items (`id`, `category`, `item`, `description`, `date_added`, `modified`, `modified_by`, `image`, `item_url`, `templatefile`, `metadesc`, `meta_key`, `searchkeys`, `visits`, `published`, `published_up`) ";
            $sql .= "VALUES (NULL, ".$row->realcategory.", '".addslashes($row->titolo)."', \"".addslashes($row->descrizione)."\", '', '".$row->modified."','".$row->modified_by."', '".$imageData_2."', '', 'oneitem', '".addslashes($row->metadesc)."', '".addslashes($row->metakey)."', '".addslashes($row->keycheck)."', ".$row->counter.", 1, '".$row->publish_up."');";
           
          
             $newid = $dcron->insert($sql);
            $row_2['actor'] = $row->attore1;
            $row_2['actor2'] = $row->attore2;
            $row_2['actor3'] = $row->attore3;
            $row_2['amazonid'] = $row->amazonid;
            $row_2['director'] = $row->regista;
             $row_2['disponible']  = $row->disponibile;
            $row_2['distribution'] = $row->distribution;
             $row_2['duration'] = $row->durata;
             $row_2['MPAA'] = $row->MPAA;
             $row_2['nation'] = $row->nazione;
             $row_2['notes'] = $row->notes;
             $row_2['production'] = $row->produzione;
            $row_2['title_2'] = $row->titolo2;
            $row_2['year'] = $row->anno;
             $row_2['jmovie_id'] = $row->id;
             foreach ($row_2 as $key => $value)
             {
                 $query = "SELECT id FROM #__directcron_fields WHERE name='$key' LIMIT 1";
                 $value2 = $dcron->getOneValue($query);
                 $ourvalues = array();
                 $ourvalues['value'] = $value;
                 $ourvalues['itemid'] = $newid;
                 $ourvalues['field'] = $value2;
                 $ourvalues['categoryid'] = $row->realcategory;
                 $insertquery = $dcron->buildQuery( 'INSERT', '#__directcron_field_values', $ourvalues);
                 $dcron->insert($insertquery);
             }
             
           
       
          
            
            
        }
    }
    function amz($asin)//function exclusively created for mydvdtrader and itemgeeks.com
	{
		global $database, $cinConfig, $mainframe, $ItemId, $my, $option;
		$query = "SELECT id FROM #__jmovies WHERE amazonid='" . $asin . "'";
		$database->setQuery( $query );
		$jmovie = $database->loadAssocList();
		if(count($jmovie) > 0)
		{
			mosRedirect(JMovieURL("index.php?option=".$option."&Itemid=".$Itemid."&task=detail&id=".$jmovie[0]['id']));
			exit;
		}
		
		$objAmazon = new synJMoviesAmazon();
		$amazon_array = $objAmazon->getItemDetail( $asin );
		$row['Error'] = false;
		if(!is_array($amazon_array) || count($amazon_array) <= 0) {
			$row['Error'] = true;
		}
		else
		{
			$amazon_attributes = $objAmazon->getValue( $amazon_array, 'ItemAttributes' );
			$amazon_largeimage = $objAmazon->getValue( $amazon_array, 'LargeImage', '' );
			$amazon_reviews = $objAmazon->getValue( $amazon_array, 'EditorialReviews' );
			$amazon_categories = $objAmazon->getValue( $amazon_array, 'BrowseNodes', '' );
			
			$row['asin'] = $asin;
			$row['title'] = $objAmazon->getValue( $amazon_attributes, 'Title', '' );
			$release_date = $objAmazon->getValue( $amazon_attributes, 'ReleaseDate', '' );
			if($release_date == '') {
				$row['year'] = 0;
			} else {
				$row['year'] = explode("-", $release_date);
				$row['year'] = (int)$row['year'][0];
			}
			$row['duration'] = $objAmazon->getValue( $amazon_attributes, 'RunningTime', 0 );
			$row['production'] = $objAmazon->getValue( $amazon_attributes, 'Publisher', '' );
			$row['distribution'] = $objAmazon->getValue( $amazon_attributes, 'Publisher', '' );
			$row['director'] = $objAmazon->getValue( $amazon_attributes, 'Director', '' );
			$row['cast'] = $objAmazon->getValue( $amazon_attributes, 'Actor', '', true, ', ' );
			$row['certification'] = $objAmazon->getValue( $amazon_attributes, 'AudienceRating', '' );
	
			$row['description'] = '';
			$reviews = array();
                        if ($amazon_reviews != null)//sometimes this information is not available
			{foreach($amazon_reviews as $editorial_review)
			{
				$review = $editorial_review[0];
				$reviews[] = $objAmazon->getValue( $review, 'Content', '' );
				if(strpos("description", strtolower($objAmazon->getValue( $review, 'Source', '' ))) !== FALSE)
				{
					$row['description'] = $objAmazon->getValue( $review, 'Content', '' );
					break;
				}
			}
                        }
			if($row['description'] == '' && count($reviews) > 0)
			{
				foreach($reviews as $review)
				{
					if(strlen(trim($review)) > 0)
					{
						$row['description'] = $review;
						break;
					}
				}
			}
			
			$row['large_image'] = '';
			$row['image_width'] = 0;
			$row['image_height'] = 0;
			if(is_array($amazon_largeimage) && count($amazon_largeimage) > 0)
			{
				$row['large_image'] = $objAmazon->getValue( $amazon_largeimage, 'URL', '' );
				$row['image_width'] = $objAmazon->getValue( $amazon_largeimage, 'Width', 0 );
				$row['image_height'] = $objAmazon->getValue( $amazon_largeimage, 'Height', 0 );
			}
			
			$row['catname'] = array();
			if(is_array($amazon_categories) && count($amazon_categories) > 0)
			{
				$category_list = $objAmazon->getValue( $amazon_categories, 'BrowseNode', array() );
				$categories = $category_list['Name'];
				$category_names = implode( "','", $categories );
				$category_names = "('" . $category_names . "')";

				$query = "SELECT id, name FROM #__categories WHERE section='com_jmovies' AND name IN " . $category_names;
				$database->setQuery( $query );
				$category_matches = $database->loadAssocList();

				if(count($category_matches) > 0)
				{
					foreach($category_matches as $category)
					{
						$row['catname'][] = $category;
					}
				}
			}
			
			//code by seowebmedia
			//print_r($row);$jmovie[0]['id']
			$trade_cost = getReedemPointForMovie($jmovie[0]['id']);

			//$row['tradingpoints'] = (int)$cinConfig['trade_cost'];
			$row['tradingpoints'] = $trade_cost;
			$row['login'] = false;

			if($my->username)
			{
				$row['login'] = true;
	
				/*$query = "SELECT tradepoints FROM #__users WHERE id=" . (int)$my->id;
				$database->setQuery( $query );
				$user_trade_points = $database->loadResult();
				$row['user_trade_points'] = $user_trade_points;*/
                            /*
                                 *
                                 * From now on, we get the points from the AlphaUserPoints component instead:
                                 * Alteration made by Redacron.com on November 22nd, 2009
                                 *
                                 */

                                $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
                                if ( file_exists($api_AUP))
                                {
                                   require_once ($api_AUP);
                                   $points = AlphaUserPointsHelper::getUserInfo ('', (int)$my->id);
                                   $user_trade_points = $points->points;

                                }
                                else $user_trade_points = "ERROR";
                                /* Why is $user_trade_points here? Maybe a copy-paste error */
                                $row['user_trade_points'] = $user_trade_points;
				$objUser = new synJMoviesUser( $database );
				$objUser->load( $my->id );
			}
		}
		
		if(is_file($mainframe->getCfg('absolute_path')."/components/".$option."/templates/".$cinConfig['template']."/show_detail_amazon_tpl.php"))
			require($mainframe->getCfg('absolute_path')."/components/".$option."/templates/".$cinConfig['template']."/show_detail_amazon_tpl.php");
		else
			require($mainframe->getCfg('absolute_path')."/components/".$option."/templates/default/show_detail_amazon_tpl.php");
	}
    
    
    function getFeed()
    {
        //we have a version of SimplePie inside the s2framework, inside crom_cronframe, and JRevirews has code on how to use it ($app::import('vendor', 'simplepie ....
    }
    protected function object_to_array($data) 
    {
        if ((! is_array($data)) and (! is_object($data))) return; //$data;

        $result = array();

        $data = (array) $data;
        foreach ($data as $key => $value) {
            if (is_object($value)) $value = (array) $value;
            if (is_array($value)) 
            $result[$key] = $this->object_to_array($value);
            else
                $result[$key] = $value;
        }

        return $result;
    }    
}
?>
