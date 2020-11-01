<?php if ( 1 == 2) { ?><div class="itemSocialSharing">

		<ul><li class="li_social_bookmarks"><g:plusone size="small" annotation="none"></g:plusone></li>
                        <li class="li_social_bookmarks">
                            <a rel="nofollow" href="http://www.facebook.com/" 
								onclick="window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent('<?php echo $oneItemData['item']." ".JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>')+'&amp;t='+encodeURIComponent('<?php echo $oneItemData['item']; ?>'));return false;" 
								title="Add to : Facebook">
									<div id="facebook-icon"></div>
							</a></li><li class="li_social_bookmarks"><a rel="nofollow" 
				                href="http://twitter.com/" 
								onclick="window.open('http://twitter.com/?status='+encodeURIComponent('<?php echo $oneItemData['item']." ".JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>');return false;"
								title="Add to : Twitter">
									<div id="twitter-icon"></div>
							</a></li><li class="li_social_bookmarks"></li><li class="li_social_bookmarks"><a rel="nofollow" 
				                href="http://reddit.com/" 
								onclick="window.open('http://reddit.com/submit?url='+encodeURIComponent('<?php echo JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>')+'&amp;title='+encodeURIComponent('<?php echo $oneItemData['item']; ?>'));return false;"
								title="Add to : Reddit">
									<div id="reddit-icon"></div>
							</a></li> 
                                                        <li class="li_social_bookmarks"><a rel="nofollow" 
				                href="http://www.yahoo.com/"
								onclick="window.open('http://myweb2.search.yahoo.com/myresults/bookmarklet?t='+encodeURIComponent('<?php echo $oneItemData['item']; ?>')+'&amp;d=&amp;tag=&amp;u='+encodeURIComponent('<?php echo JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>'));return false;"
								title="Add to : Yahoo">
									<div id="yahoo-icon"></div>
							</a></li><li class="li_social_bookmarks"><a rel="nofollow"
				                href="http://www.google.com/"
								onclick="window.open('http://www.google.com/bookmarks/mark?op=add&amp;hl=en&amp;bkmk='+encodeURIComponent(''<?php echo JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>'')+'&amp;annotation=&amp;labels=&amp;title='+encodeURIComponent('<?php echo $oneItemData['item']; ?>'));return false;"
								title="Add to : Google">
									<div id="google-icon"></div>
							</a></li>
                                                        
                                                       
                                                        <li class="li_social_bookmarks"><a rel="nofollow" 
				                href="http://www.technorati.com/"
								onclick="window.open('http://technorati.com/faves?add='+encodeURIComponent('<?php echo JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>')+'&amp;tag=');return false;"
								title="Add to : Technorati"><div id="technorati-icon"></div>
									 
							</a></li>
                                                        
                                                        <li class="li_social_bookmarks"><a rel="nofollow" 
				                href="http://www.webnews.de/" 
								onclick="window.open('http://www.webnews.de/einstellen?url='+encodeURIComponent('<?php echo JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>')+'&amp;title='+encodeURIComponent('<?php echo $oneItemData['item']; ?>'));return false;"
								title="Add to : Webnews"><div id="webnews-icon"></div>
									 
							</a></li> 
                                                       
                                                        <li class="li_social_bookmarks"><a rel="nofollow" 
				                href="http://digg.com/" 
								onclick="window.open('http://digg.com/submit?phase=2&amp;url='+encodeURIComponent('<?php echo JRoute::_("index.php?".$_SERVER['QUERY_STRING']).":".str_replace(' ', '-', $oneItemData['item']); ?>')+'&amp;bodytext=&amp;tags=&amp;title='+encodeURIComponent('<?php echo $oneItemData['item']; ?>'));return false;"
								title="Add to : Digg">
									<div id="digg-icon"></div>
							</a></li></ul></div>

		<div class="clr"></div>
	 
<?php } ?>