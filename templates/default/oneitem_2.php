<?php $oneItemData = $this->oneitem; ?>
<table>
    <tr><td colspan="2" style="background: #ddd;"><h2><?php echo $oneItemData['item']; ?></h2></td></tr>
  <tr>
             <?php if ($oneItemData['thumbnail']) { ?> <td><img src="<?php echo JURI::base()."components/com_directcron/images/photos/".$oneItemData['thumbnail'];?>" alt="<?php echo $oneItemData['item'];?> image" /></td> <?php } ?> 
  
            <td valign="top"><?php echo $oneItemData['description'];?></td>
               </tr><tr>
           <td colspan="2"><p>Actors: <?php echo $oneItemData['actor']; ?>
            
               </p><p>Director:
                <?php echo $oneItemData['director']; 
                 dircron_echo('item', '<h1>', 'style=\'color: #900; font-weight: bold;\'');
                 dircron_imageHandler('image',true);
                 dircron_echo('director', '<div>', 'style=\'color: #090; font-weight: bold;\''); 
                 dircron_echo('extra_image', '<div>', 'style=\'color: #090; font-weight: bold;\''); 
                 echo_fields();
                ?>
               </p> 
           </td>    
           
    </tr>
  
    
</table>
 