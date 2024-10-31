<?php
//include the main class file
require_once("meta-box-class/my-meta-box-class.php");
if (is_admin()){
  /* 
   * prefix of meta keys, optional
   * use underscore (_) at the beginning to make keys hidden, for example $prefix = '_ba_';
   *  you also can make prefix empty to disable it
   * 
   */
  $prefix = '_pvg_';
  /* 
   * configure your meta box
   */
  $config = array(
    'id'             => 'demo_meta_box',          // meta box id, unique per meta box
    'title'          => 'Simple Meta Box fields',          // meta box title
    'pages'          => array(''),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $my_meta =  new PVG_Meta_Box($config);
  
  /*
   * Add fields to your meta box
   */
  

  //textarea field
  $my_meta->addTextarea($prefix.'textarea_field_id',array('name'=> 'My Textarea '));
  //checkbox field
  $my_meta->addCheckbox($prefix.'checkbox_field_id',array('name'=> 'My Checkbox '));
  //select field
  $my_meta->addSelect($prefix.'select_field_id',array('selectkey1'=>'Select Value1','selectkey2'=>'Select Value2'),array('name'=> 'My select ', 'std'=> array('selectkey2')));
  //radio field
  $my_meta->addRadio($prefix.'radio_field_id',array('radiokey1'=>'Radio Value1','radiokey2'=>'Radio Value2'),array('name'=> 'My Radio Filed', 'std'=> array('radionkey2')));
  //Image field
  $my_meta->addImage($prefix.'image_field_id',array('name'=> 'My Image '));
  //file upload field
  $my_meta->addFile($prefix.'file_field_id',array('name'=> 'My File'));
  //file upload field with type limitation
  $my_meta->addFile($prefix.'file_pdf_field_id',array('name'=> 'My File limited to PDF Only','ext' =>'pdf','mime_type' => 'application/pdf'));
  /*
   * Don't Forget to Close up the meta box Declaration 
   */
  //Finish Meta Box Declaration 
  $my_meta->Finish();

  /**
   * Create a second metabox
   */
  /* 
   * configure your meta box
   */
  $config2 = array(
    'id'             => 'demo_meta_box2',          // meta box id, unique per meta box
    'title'          => 'Configure video gallery',          // meta box title
    'pages'          => array('pgallery'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your 2nd meta box
   */
  $my_meta2 =  new PVG_Meta_Box($config2);
  
  /*
   * Add fields to your 2nd meta box
  4 */
  //add checkboxes list 


	
	$repeater_fields2[] = $my_meta2->addFile($prefix.'video',array('name'=> 'Video','desc'=> 'Select a .mp4 / .ogg video file.'),true);
	$repeater_fields2[] = $my_meta2->addTextarea($prefix.'title',array('name'=> 'Video Title','desc'=> 'Please add the video title'),true);
	$repeater_fields2[] = $my_meta2->addImage($prefix.'label',array('name'=> 'Video Poster image','desc'=> 'Select a poster image'),true);
  

  /*
   * Then just add the fields to the repeater block
   */
  //repeater block
  $my_meta2->addRepeaterBlock($prefix.'re_',array(
    'inline'   => true, 
    'name'     => 'Click On + To add videos in the gallery',
    'fields'   => $repeater_fields2, 
    'sortable' => true
  ));
	$my_meta2->addRadio($prefix.'video_repeat',array('once'=>'Repeat Once','loop'=>'Repeated '),array('name'=> 'Repeat', 'std'=> array('once')));
	
    $my_meta2->addCheckbox($prefix.'video_muted',array('name'=> 'Muted Player','desc' =>'Check if you want the video output should be muted'));		
	//$my_meta2->addNumber($prefix.'width',array('name'=> 'Player Width','desc' =>'Sets the player width. eg: 460 for a 460 pixel width. Height will be calculate base on the value. Left 0 for RESPONSIVE Player. ')); 
	
	
	$my_meta2->addCheckbox($prefix.'gallery_title',array('name'=> 'Hide Gallery Title','desc' =>'Check if you want to hide the gallery title in Frontend'));	
	$my_meta2->addRadio($prefix.'column',array('95%'=>'1 Column ','45%'=>'2 Column ','30%'=>'3 Column ','22%'=>'4 Column  '),array('name'=> 'Number of Colum in Gallery', 'std'=> array('30%')));
	$my_meta2->addCheckbox($prefix.'auto_pause',array('name'=> 'Auto Pause','desc' =>'Check if you want Only one player playing at once'));
	$my_meta2->addRadio($prefix.'theme',array('theme1'=>'Theme 1','theme2'=>'Theme 2 '),array('name'=> 'Theme', 'std'=> array('theme1')));
	$my_meta2->addColor($prefix.'bg_color',array('name'=> 'Gallery Background Color ')); 
	//$my_meta2->addNumber($prefix.'margin',array('name'=> 'Margin','desc' =>'set the distance between each video')); 
	
  //Finish Meta Box Declaration 
  $my_meta2->Finish();
}