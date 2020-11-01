<?php 
$oneItemData = $this->oneitem;
 
?>
<div id="wrapper2"  style="width: 100%;">

                                                <div id="main">
 <div id="extmovies-container" >
	<div id="movie-container">
						<h1><?php echo dircron_get('oneItem:title_2'); ?></h1>
				<div class="movie-details">
			<h2>Details</h2>
			<div class="extmov_block">
				<div class="emov_info">
					<div class="movie-description">
								
								<div class="emov-poster-main">
					 <?php echo dircron_get('oneItem:image'); ?>
				 
						</div>
					 <ul class="movie-traits">
							<li class="movie-duration">
								<b>Duration: </b>
								<?php echo dircron_get('oneItem:duration'); ?> min</li>
                                                        <li class="movie-year">
                                                            <b>Year: </b>
                                                            <?php echo dircron_get('oneItem:year'); ?> 
                                                        </li>
                                                        <li class="movie-production">
                                                            <b>Production: </b>
                                                            <?php echo dircron_get('oneItem:production'); ?>
                                                        </li>
														<li class="movie-genres">
								<b>Genre: </b> 
           <a href="<?php echo "index.php?option=com_directcron&amp;Itemid=".$this->itemid."&amp;task=viewitems&amp;view=items&amp;catid=".dircron_get('oneItem:category_id'); ?>"><?php echo dircron_get('oneItem:category'); ?></a>	 
                                                                                                                </li>
	 <li class="movie-countries"> <b>Country: </b><?php echo dircron_get('oneItem:nation'); ?></li>
          <li class="movie-notes"><p class="movie-website"><?php echo dircron_get('oneItem:notes'); ?></p></li></ul>						
	 <p class="movie-description"><?php echo $this->formattask->shorten_pr(dircron_get('oneItem:description'), 50);?></p>
		<?php /*if ($this->userid > 0) { ?>													 
			<div id="wishlist_container">
   <a href="javascript:void(0)" class="wishlist_btn" onclick="wishlist(1, 'projectmovies', 'add'); return false;">Add to wishlist</a>
							</div>
         <?php }*/ ?>
                                                                                                        <!-- <table width="100%" border="0" cellspacing="1" cellpadding="3">
					 
           
                                        <tr>
                                            <td style="text-align:left"><a href="javascript:void(0)" id="iownthis">I own this movie.<br />I want to trade it.</a></td>
                                            <td><a href="javascript:void(0)" id="iwantthis">I want this movie.</a></td>
                                            <td><a href="javascript:void(0)" id="wishlist">Wish List</a></td>
                                                 
                                        <script>
                                           cronframe.jQuery('#iownthis').click(function() {  
                                         cronframe.jQuery.post("index.php?option=com_itemexchange&task=ownItem", {itemid:<?php echo $oneItemData['id']?>},//date2send: values, prevar: prevarray, option: option, taskordering: ascdesc},
    function(data)  { alert("here is the data: " + data);   });
                                                return false;    
                                       }); 
                                            cronframe.jQuery('#iwantthis').click(function() {
                                                cronframe.jQuery.post("index.php?option=com_itemexchange&task=requestItem", {itemid:<?php echo $oneItemData['id']?>},//date2send: values, prevar: prevarray, option: option, taskordering: ascdesc},
    function(data)  { alert("here is the data: " + data);   });
                                                return false; }); 
                                            cronframe.jQuery('#wishlist').click(function() {
                                                cronframe.jQuery.post("index.php?option=com_itemexchange&view=wishlist&task=addtoWish", {itemid:<?php echo $oneItemData['id']?>},//date2send: values, prevar: prevarray, option: option, taskordering: ascdesc},
    function(data)  { alert("here is the data: " + data);   });
                                                return false; }); 
                                            </script>
                                           <div class="threeothers" id="itemThree_<?php print_r($oneItemData['id']); ?>" data-userid="<?php print_r($oneItemData['modified_by']); ?>">dfdf</div>
                                           <div id="wishtradeMSG_<?php print_r($oneItemData['id']); ?>"></div>
			
					</tr>-->
         <?php if ($this->userid > 0) { ?>
<script>var itemexurl = '<?php echo JUri::base();?>';</script>
<script type="text/babel" src="<?php echo JUri::root()."components/com_itemexchange/assets/js/directcron.js";?>"></script>
<div id='wishTrade' data-itemid='<?php echo $oneItemData['id'];?>' data-moviename='<?php echo $oneItemData['item'];?>'></div>
<div id='wishtradeResult'></div>
         <?php } ?>
				 				<?php if (1 == 2) { 
                                                                    //for now, we shouldn't bother with like or dislike
                                                                    ?><div id="emov_likes_1" class="emov_likes">
				<a href="#" class="a_emov_likes" onclick="sendLikes(1,'projectmovies',1);return false;">
					<img class="img_emov_likes" src="http://demo.jvitals.com/demo25/components/com_extendeddb/assets/likes/images/like.png" />
					&nbsp;Like&nbsp;
					<span class="emov_likes_count" style="display:none;" id="emov_likes_count_1">[&nbsp;0&nbsp;]</span>
				</a>
								<a href="#" class="a_emov_dislikes" onclick="sendLikes(1,'projectmovies',-1);return false;">
					<img class="img_emov_dislikes" src="http://demo.jvitals.com/demo25/components/com_extendeddb/assets/likes/images/dislike.png" />
					&nbsp;Dislike&nbsp;
					<span class="emov_dislikes_count" style="display:none;" id="emov_dislikes_count_1">[&nbsp;0&nbsp;]</span>
				</a>
                                                                </div><?php } ?>
									</div>
				</div>
			</div>
		</div>
                                                <div class="movie-details">
				<div class="extmov_block">
				<div class="emov_info">
				 <p class="movie-description"><?php echo dircron_get('oneItem:description');?></p>
                                </div></div></div>
					 <div class="movie-cast">
			<h2>Cast And Crew</h2>
									<div class="extmov_block_wrap">
			<div class="extmov_block extmov_joblist">
				<h4>Actor</h4>
				<ul>
                                     
                                                        
                                                            
                                    <li><?php echo dircron_get('oneItem:actor'); ?></li>
                                  <?php 
                                       if (ifFieldNotEmpty('actor2')) {  echo "<li>".dircron_get('oneItem:actor2')."</li>"; }
                                       if (ifFieldNotEmpty('actor3')) {  echo "<li>".dircron_get('oneItem:actor3')."</li>"; }
                                       ?>
                                </ul>
                                <?php
                                if (ifFieldNotEmpty('director')) { ?>
			 <h4>Director</h4>
				<ul><li><?php echo dircron_get('oneItem:director'); ?></li></ul>
                       <?php } ?> 
                                    
                                </div>
				</div>
                      </div>
			</div>
      
			 </div>
                                                    <?php include_once('oneitem_social.php'); ?>                    

                                                </div></div>                                  
 
<div class="jmoviesSeparator"></div>
                                         
 <?php 
     echo $this->loadTemplate('commentform'); 
    
 ?>
