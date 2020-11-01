<?php
if (!defined('CRONFRAME_ROOT')) { echo "ERROR - unable to load thumbnail.inc.php"; exit; }
include_once(CRONFRAME_ROOT. DS . 'thirdparty'. DS . 'imagescripts' . DS . 'thumbnail.inc.php');
class imageSettings
{
    function __construct() {  }
    function getInstance( $options = array())
    {
        static $instances;
        if (!isset( $instances )) {$instances = array();}
        $signature = serialize( $options );
        if (empty($instances[$signature]))
        {
            $instance = new imageSettings();
            $instances[$signature] = & $instance;
        }
        return $instances[$signature];
    }
    public function imageDir() { $imageDir = JPATH_ROOT.DS.'images'.DS; //in the future, we will need this to change the directory's location
        return $imageDir;
    }
}
class upl_albumpics
{
    private $X_LIMIT = 350;
    private $Y_LIMIT = 350;
    private $file_field = 'upload_file';
function __construct($file_field)
{
     if ($file_field != '') $this->file_field = $file_field;
}
function getInstance( $options	= array(), $filefield = '' )
{
    static $instances;
    if (!isset( $instances )) {$instances = array();}
    $signature = serialize( $options );
    if (empty($instances[$signature]))
    {
	$instance = new upl_albumpics( $filefield);
        $instances[$signature] = & $instance;
    }
    return $instances[$signature];
}
function upload_pic($id, $directory = '')
{
    jimport('joomla.client.helper');
    JClientHelper::setCredentialsFromRequest('ftp');

    jimport('joomla.filesystem.file');

    $file =& JRequest::getVar($this->file_field, '', 'files', 'array' );//Get File name, tmp_name

    $filename = JPath::clean(strtolower($file['name']));
    if (trim($file['type']) == '') {  return array(false, false);}
    if (trim($filename) == '') return array(false, false);
     $directory = 'directcron'.DS.$directory;
        $imgsettings = imageSettings::getInstance('upload_pic');
        $crudepath = $imgsettings->imageDir();//JPATH_ROOT.DS.'images'.DS;
     JFolder::create($crudepath.DS.$directory, 0755);
     if (!is_writeable($crudepath.DS.$directory.DS))
       {

          echo "<script> alert('Error - Path ".$crudepath." is not writeable!');</script>\n";
            return array(false, false);   
       }
       else
       {
           $crudepath = $crudepath.DS.$directory.DS;
           JFolder::create($crudepath.'thumbnails', 0755);
       }
    //$filepath = JPATH_ROOT.DS.'images/albums'.DS.$filename;//specific path of the file
    $filepath = $crudepath.$filename;//specific path of the file
    if (is_file($filepath))
    {
         echo "<script> alert('The file you are trying to upload has already been uploaded.');</script>\n";
        return array(false, false);
    }
    $allowed = array('image/jpeg', 'image/png', 'image/gif', 'image/JPG', 'image/jpg', 'image/pjpeg');

    if (!in_array($file['type'], $allowed)) //To check if the file are image file
    {
            echo "<script> alert('The file you are trying to upload ".$file['type']." is not supported.');</script>\n";
          return array(false, false); 
    }
    else
    {
             JFile::upload($file['tmp_name'], $filepath);//first param is src file, second param is destination
             /* Perhaps the file is too large, let's make it smaller: */
              $img = $this->Right_Creator($crudepath, $filename);
                  if ($img === false) {
                      echo "<script> alert('File Error! File Type Error');</script>\n";
                           exit;
                      }
                  $width = imagesx( $img );
                  $height = imagesy( $img );
                  imagedestroy($img);
                  /*  we should always free up memory, always, and $img variable is also used in
                  * Minimize_Image method */
                  $smalled = 0;

                  if ($height > $this->Y_LIMIT)
                  {
                      $this->Minimize_Image($crudepath, $filename, $this->Y_LIMIT, 'height');
                      $smalled = 1;
                  }
                  if ($width > $this->X_LIMIT) {$this->Minimize_Image($crudepath, $filename, $this->X_LIMIT, 'width'); $smalled =1;}
                  //we now create a thumbnail:
                  $this->Minimize_Image($crudepath , $filename, 150, 'height', true);

             return array($directory, $filename);
    }
}
private function Right_Creator($pathToImages, $fname)
{
    $pos = strrpos($fname, ".");
      if ($pos === false) return false;
      $extension = strtolower(substr($fname, $pos +1));
      if ($extension == 'gif') $img = @imagecreatefromgif( "{$pathToImages}{$fname}" );
      elseif ($extension == 'png') $img = @imagecreatefrompng( "{$pathToImages}{$fname}" );
      elseif ($extension == 'bmp') $img = $this->ImageCreateFromBMP( "{$pathToImages}{$fname}" );
      else $img = @imagecreatefromjpeg( "{$pathToImages}{$fname}" );
      return $img;
}
private function Minimize_Image($pathToImages, $fname, $thumb = "150", $choice ='width', $thumbnail = false)
{
     
    
   if ($fname == '') return;
   if (!is_writeable($pathToImages))
   {
          echo "<script> alert('Error - Path #1 is not writeable! Exiting now');</script>\n";
          exit;
   }
       $thumbClass = new Thumbnail($pathToImages.DS.$fname);
       if ($thumbnail === true)
      {
          $pathToImages = str_replace('\/\/', '\/', $pathToImages.DS.'thumbnails');
          if (!is_writeable($pathToImages))
   {
          echo "<script> alert('Error - Thumbnail Path is not writeable! Exiting now');</script>\n";
          
          exit;
   }
      }
       $height = $thumbClass->getCurrentHeight();
       $width = $thumbClass->getCurrentWidth();
       // calculate thumbnail size
       if ($choice == 'width') {$new_width = $thumb;
       $new_height = floor( $height * ( $thumb / $width ) );}
       else {$new_height = $thumb;
        $new_width = floor( $width * ( $thumb / $height ) );}
         $thumbClass->resize($new_width, $new_height);
         $thumbClass->save($pathToImages.DS.$fname, 100);
          
          
  /* $img = $this->Right_Creator($pathToImages, $fname);
   $width = @imagesx( $img );
   $height = @imagesy( $img );
   // calculate thumbnail size
   if ($choice == 'width') {$new_width = $thumb;
   $new_height = floor( $height * ( $thumb / $width ) );}
   else {$new_height = $thumb;
   $new_width = floor( $width * ( $thumb / $height ) );}
   // create a new tempopary image
   
   $tmp_img = @imagecreatetruecolor( $new_width, $new_height );
   // copy and resize old image into new image
   @imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
   // save thumbnail into a file
   $pos = strrpos($fname, ".");
   $extension = strtolower(substr($fname, $pos +1));
   if ($extension == 'gif') $img = @imagegif( $tmp_img, "{$pathToThumbs}{$fname}", $quality);
   elseif ($extension == 'png') $img = @imagepng($tmp_img, "{$pathToThumbs}{$fname}", 10);
   elseif ($extension == 'bmp') $img = imagejpeg($tmp_img, "{$pathToThumbs}{$fname}", $quality);
   else $img = @imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}", $quality );
   @imagedestroy($tmp_img);//we should always free up memory, always
   @imagedestroy($img);//we should always free up memory, always*/

}
}
?>