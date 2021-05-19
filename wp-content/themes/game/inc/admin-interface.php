<?php
/** Activate some theme features **/
add_action('after_setup_theme', 'braygames_setup' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function braygames_setup() {
  global $options;

  // Make theme available for translation
  // Translations can be filed in the /lang/ directory
  load_theme_textdomain('braygames', TEMPLATEPATH . '/lang');

  // This theme uses wp_nav_menu() in two locations.
  register_nav_menus( array(
    'primary' => __( 'Primary Navigation', 'braygames' ),
    'footer' => __( 'Footer Navigation', 'braygames' ),
    )
  );

  // This theme allows users to set a custom background
  add_theme_support( 'custom-background' );

  $custom_header_args = array(
    'default-image' => '',
    'random-default' => false,
    'width' => apply_filters('fungames_header_image_width', 1280),
    'height' => apply_filters('fungames_header_image_height', 214),
    'flex-height' => false,
    'flex-width' => false,
    'default-text-color' => '000000',
    'header-text' => false,
    'uploads' => true,
    'wp-head-callback' => '',
    'admin-head-callback' => 'braygames_admin_header_style',
    'admin-preview-callback' => '',
  );

  add_theme_support('custom-header', $custom_header_args);

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
}
function braygames_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
  border-bottom: 1px solid #000;
  border-top: 4px solid #000;
}
#headimg #name {
  position:relative;
  top:65px;
  left:38px;
  width:852px;
  font:bold 28px "Trebuchet MS";
  text-decoration:none;
}
#headimg #desc {
    color:#000;
    border-bottom:none;
    position:relative;
    top:50px;
    width:852px;
    left:38px;
    font:18px arial;
}
</style>
<?php
}
/**
 * Sets the various customised styling according to the options set for the theme
 */
function braygames_custom_style() {
  $background  = get_theme_mod('background_image', false);
  $bgcolor     = get_theme_mod('background_color', false);

  if (!$background && $bgcolor) {
    echo '<style type="text/css">body { background-image:none; }</style>';
  }
}


/*-----------------------------------------------------------------------------------*/
/* Admin Interface
/*-----------------------------------------------------------------------------------*/
//$functions_path = THEME_ADMIN . '/';
function siteoptions_add_admin() {

    global $query_string;

    if ( isset($_REQUEST['page']) && $_REQUEST['page'] == 'siteoptions' ) {
		if (isset($_REQUEST['of_save']) && 'reset' == $_REQUEST['of_save']) {
			$options =  get_option('of_template');
			of_reset_options($options,'siteoptions');
			header("Location: admin.php?page=siteoptions&reset=true");
			die;
		}
    }

    $tt_page = add_theme_page('Site Options', 'Theme Settings', 'edit_theme_options', 'siteoptions','siteoptions_options_page');
	add_action("admin_print_scripts-$tt_page", 'of_load_only');
	add_action("admin_print_styles-$tt_page",'of_style_only');
}

add_action('admin_menu', 'siteoptions_add_admin');


/*-----------------------------------------------------------------------------------*/
/* Reset Function
/*-----------------------------------------------------------------------------------*/

function of_reset_options($options,$page = ''){

	global $wpdb;
	$query_inner = '';
	$count = 0;

	$excludes = array( 'blogname' , 'blogdescription' );

	foreach($options as $option){
		if(isset($option['id'])){
			$count++;
			$option_id = $option['id'];
			$option_type = $option['type'];

			//Skip assigned id's
			if(in_array($option_id,$excludes)) { continue; }

			if($count > 1){ $query_inner .= ' OR '; }
			if($option_type == 'multicheck'){
				$multicount = 0;
				foreach($option['options'] as $option_key => $option_option){
					$multicount++;
					if($multicount > 1){ $query_inner .= ' OR '; }
					$query_inner .= "option_name = '" . $option_id . "_" . $option_key . "'";

				}

			} else if(is_array($option_type)) {
				$type_array_count = 0;
				foreach($option_type as $inner_option){
					$type_array_count++;
					$option_id = $inner_option['id'];
					if($type_array_count > 1){ $query_inner .= ' OR '; }
					$query_inner .= "option_name = '$option_id'";
				}

			} else {
				$query_inner .= "option_name = '$option_id'";
			}
		}

	}

	//When Theme Options page is reset - Add the of_options option
	if($page == 'siteoptions'){
		$query_inner .= " OR option_name = 'of_options'";
	}

	//echo $query_inner;

	$query = "DELETE FROM $wpdb->options WHERE $query_inner";
	$wpdb->query($query);

}

/*-----------------------------------------------------------------------------------*/
/* Build the Options Page
/*-----------------------------------------------------------------------------------*/

function siteoptions_options_page(){
    $options =  get_option('of_template');
?>

<div class="wrap" id="theme_settings">
  <div id="of-popup-save" class="of-save-popup">
    <div class="of-save-save"><?php echo MYAPB_THEMENAME; ?> settings saved</div>
  </div>
  <div id="of-popup-reset" class="of-save-popup">
    <div class="of-save-reset">Options Reset</div>
  </div>
  <form action="" enctype="multipart/form-data" id="ofform">
    <div id="main">
    <?php
		// Rev up the Options Machine
        $return = siteoptions_machine($options);
        ?>

<div class="save_bar_top">
    <img style="display:none;" src="<?php echo get_template_directory_uri() ?>/inc/images/wpspin_light.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
    <input type="submit" value="Save All Changes" class="button-primary" />
  </form>
  <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" method="post" style="display:inline" id="ofform-reset">
    <span class="submit-footer-reset">
    <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button" onclick="return confirm('CAUTION: Any and all settings will be lost! Click OK to reset.');" />
    <input type="hidden" name="of_save" value="reset" />
    </span>
  </form>
</div>
<div class="clear"></div>
      <div id="of-nav">
        <ul>
          <?php echo $return[1] ?>
        </ul>
      </div>

      <div id="content"> <?php echo $return[0]; /* Settings */ ?> </div>
      <div class="clear"></div>

<div class="save_bar_top">
    <img style="display:none;" src="<?php echo get_template_directory_uri() ?>/inc/images/wpspin_light.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
    <input type="submit" value="Save All Changes" class="button-primary" />
  </form>
  <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" method="post" style="display:inline" id="ofform-reset">
    <span class="submit-footer-reset">
    <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button" onclick="return confirm('CAUTION: Any and all settings will be lost! Click OK to reset.');" />
    <input type="hidden" name="of_save" value="reset" />
    </span>
  </form>
</div>
</div>
<?php  if (!empty($update_message)) echo $update_message; ?>
<div style="clear:both;"></div>
</div>
<!--wrap-->
<?php
}








/*-----------------------------------------------------------------------------------*/
/* Load required styles for Options Page
/*-----------------------------------------------------------------------------------*/

function of_style_only() {
	wp_enqueue_style('admin-style', get_template_directory_uri().'/inc/admin-style.css');
}





/*-----------------------------------------------------------------------------------*/
/* Load required javascripts for Options Page
/*-----------------------------------------------------------------------------------*/

function of_load_only() {

	add_action('admin_head', 'of_admin_head');

	wp_enqueue_script('jquery-ui-core');
	wp_register_script('jquery-input-mask', get_template_directory_uri().'/inc/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	//wp_enqueue_script('color-picker', get_template_directory_uri().'/inc/js/colorpicker.js', array('jquery'));
	wp_enqueue_script('ajaxupload', get_template_directory_uri().'/inc/js/ajaxupload.js', array('jquery'));

	function of_admin_head() {
	?>


<script type="text/javascript" language="javascript">

		jQuery(document).ready(function(){

		// Race condition to make sure js files are loaded
		if (typeof AjaxUpload != 'function') {
			return ++counter < 6 && window.setTimeout(init, counter * 500);
		}

			//Color Picker
			<?php $options = get_option('of_template');

			foreach($options as $option){
			if($option['type'] == 'color' OR $option['type'] == 'typography' OR $option['type'] == 'border'){
				if($option['type'] == 'typography' OR $option['type'] == 'border'){
					$option_id = $option['id'];
					$temp_color = get_option($option_id);
					$option_id = $option['id'] . '_color';
					$color = $temp_color['color'];
				}
				else {
					$option_id = $option['id'];
					$color = get_option($option_id);
				}
				?>
				 jQuery('#<?php echo $option_id; ?>_picker').children('div').css('backgroundColor', '<?php echo $color; ?>');
				 /*jQuery('#<?php echo $option_id; ?>_picker').ColorPicker({
					color: '<?php echo $color; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						//jQuery(this).css('border','1px solid red');
						jQuery('#<?php echo $option_id; ?>_picker').children('div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $option_id; ?>_picker').next('input').attr('value','#' + hex);

					}
				  });*/
			  <?php } } ?>

		});

		</script>

		<?php
		//AJAX Upload
		?>
<script type="text/javascript">
			jQuery(document).ready(function(){

				var i = 0;
				jQuery('#of-nav li a').attr('id', function() {
				   i++;
				   return 'item'+i;
				});


			var flip = 0;

			jQuery('#expand_options').click(function(){
				if(flip == 0){
					flip = 1;
					jQuery('#theme_settings #of-nav').hide();
					jQuery('#theme_settings #content').width(755);
					jQuery('#theme_settings .group').add('#theme_settings .group h2').show();

					jQuery(this).text('[-]');

				} else {
					flip = 0;
					jQuery('#theme_settings #of-nav').show();
					jQuery('#theme_settings #content').width(579);
					jQuery('#theme_settings .group').add('#theme_settings .group h2').hide();
					jQuery('#theme_settings .group:first').show();
					jQuery('#theme_settings #of-nav li').removeClass('current');
					jQuery('#theme_settings #of-nav li:first').addClass('current');

					jQuery(this).text('[+]');

				}

			});

				jQuery('.group').hide();
				jQuery('.group:first').fadeIn();

				jQuery('.group .collapsed').each(function(){
					jQuery(this).find('input:checked').parent().parent().parent().nextAll().each(
						function(){
           					if (jQuery(this).hasClass('last')) {
           						jQuery(this).removeClass('hidden');
           						return false;
           					}
           					jQuery(this).filter('.hidden').removeClass('hidden');
           				});
           		});

				jQuery('.group .collapsed input:checkbox').click(unhideHidden);

				function unhideHidden(){
					if (jQuery(this).attr('checked')) {
						jQuery(this).parent().parent().parent().nextAll().removeClass('hidden');
					}
					else {
						jQuery(this).parent().parent().parent().nextAll().each(
							function(){
           						if (jQuery(this).filter('.last').length) {
           							jQuery(this).addClass('hidden');
									return false;
           						}
           						jQuery(this).addClass('hidden');
           					});

					}
				}

				jQuery('.of-radio-img-img').click(function(){
					jQuery(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
					jQuery(this).addClass('of-radio-img-selected');

				});
				jQuery('.of-radio-img-label').hide();
				jQuery('.of-radio-img-img').show();
				jQuery('.of-radio-img-radio').hide();
				jQuery('#of-nav li:first').addClass('current');
				jQuery('#of-nav li a').click(function(evt){

						jQuery('#of-nav li').removeClass('current');
						jQuery(this).parent().addClass('current');

						var clicked_group = jQuery(this).attr('href');

						jQuery('.group').hide();

							jQuery(clicked_group).fadeIn();

						evt.preventDefault();

					});

				if('<?php if(isset($_REQUEST['reset'])) { echo $_REQUEST['reset'];} else { echo 'false';} ?>' == 'true'){

					var reset_popup = jQuery('#of-popup-reset');
					reset_popup.fadeIn();
					window.setTimeout(function(){
						   reset_popup.fadeOut();
						}, 2000);
						//alert(response);

				}

			//Update Message popup
			jQuery.fn.center = function () {
				this.animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"},100);
				this.css("left", 250 );
				return this;
			}


			jQuery('#of-popup-save').center();
			jQuery('#of-popup-reset').center();
			jQuery(window).scroll(function() {

				jQuery('#of-popup-save').center();
				jQuery('#of-popup-reset').center();

			});



			//AJAX Upload
			jQuery('.image_upload_button').each(function(){

			var clickedObject = jQuery(this);
			var clickedID = jQuery(this).attr('id');
			new AjaxUpload(clickedID, {
				  action: '<?php echo admin_url("admin-ajax.php"); ?>',
				  name: clickedID, // File upload name
				  data: { // Additional data to send
						action: 'of_ajax_post_action',
						type: 'upload',
						data: clickedID },
				  autoSubmit: true, // Submit file after selection
				  responseType: false,
				  onChange: function(file, extension){},
				  onSubmit: function(file, extension){
						clickedObject.text('Uploading'); // change button text, when user selects file
						this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
						interval = window.setInterval(function(){
							var text = clickedObject.text();
							if (text.length < 13){	clickedObject.text(text + '.'); }
							else { clickedObject.text('Uploading'); }
						}, 200);
				  },
				  onComplete: function(file, response) {

					window.clearInterval(interval);
					clickedObject.text('Upload Image');
					this.enable(); // enable upload button

					// If there was an error
					if(response.search('Upload Error') > -1){
						var buildReturn = '<span class="upload-error">' + response + '</span>';
						jQuery(".upload-error").remove();
						clickedObject.parent().after(buildReturn);

					}
					else{
						var buildReturn = '<img class="hide of-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';

						jQuery(".upload-error").remove();
						jQuery("#image_" + clickedID).remove();
						clickedObject.parent().after(buildReturn);
						jQuery('img#image_'+clickedID).fadeIn();
						clickedObject.next('span').fadeIn();
						clickedObject.parent().prev('input').val(response);
					}
				  }
				});

			});

			//AJAX Remove (clear option value)
			jQuery('.image_reset_button').click(function(){

					var clickedObject = jQuery(this);
					var clickedID = jQuery(this).attr('id');
					var theID = jQuery(this).attr('title');

					var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';

					var data = {
						action: 'of_ajax_post_action',
						type: 'image_reset',
						data: theID
					};

					jQuery.post(ajax_url, data, function(response) {
						var image_to_remove = jQuery('#image_' + theID);
						var button_to_hide = jQuery('#reset_' + theID);
						image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
						button_to_hide.fadeOut();
						clickedObject.parent().prev('input').val('');



					});

					return false;

				});




/* Top save button
jQuery(document).ready( function(){
  // bind "click" event for links with title="submit"
  jQuery("a[title=submit]").click( function(){
    // it submits the form it is contained within
    jQuery(this).parents("form").submit();
  });
}); */


			//Save everything else
			jQuery('#ofform').submit(function(){

					function newValues() {
					  var serializedValues = jQuery("#ofform").serialize();
					  return serializedValues;
					}
					jQuery(":checkbox, :radio").click(newValues);
					jQuery("select").change(newValues);
					jQuery('.ajax-loading-img').fadeIn();
					var serializedReturn = newValues();

					var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';

					 //var data = {data : serializedReturn};
					var data = {
						<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'siteoptions'){ ?>
						type: 'options',
						<?php } ?>

						action: 'of_ajax_post_action',
						data: serializedReturn
					};

					jQuery.post(ajax_url, data, function(response) {
						var success = jQuery('#of-popup-save');
						var loading = jQuery('.ajax-loading-img');
						loading.fadeOut();
						success.fadeIn();
						window.setTimeout(function(){
						   success.fadeOut();


						}, 2000);
					});

					return false;

				});

			});
		</script>
<?php }
}



/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_of_ajax_post_action', 'of_ajax_callback');

function of_ajax_callback() {
	global $wpdb; // this is how you get access to the database


	$save_type = $_POST['type'];
	//Uploads
	if($save_type == 'upload'){

		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';
		$uploaded_file = wp_handle_upload($filename,$override);

				$upload_tracking[] = $clickedID;
				update_option( $clickedID , $uploaded_file['url'] );

		 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }
		 else { echo $uploaded_file['url']; } // Is the Response
	}
	elseif($save_type == 'image_reset'){

			$id = $_POST['data']; // Acts as the name
			global $wpdb;
			$query = "DELETE FROM $wpdb->options WHERE option_name LIKE '$id'";
			$wpdb->query($query);

	}
	elseif ($save_type == 'options' OR $save_type == 'framework') {
		$data = $_POST['data'];

		parse_str($data,$output);
		//print_r($output);

		//Pull options
        	$options = get_option('of_template');

		foreach($options as $option_array){

			$id = ( !empty( $option_array['id'] ) ) ? $option_array['id'] : false;

      if ( ! $id ) {
        continue;
      }

			$old_value = get_option($id);
			$new_value = '';

			if(isset($output[$id])){
				$new_value = $output[$option_array['id']];
			}

			if(isset($option_array['id'])) { // Non - Headings...


					$type = $option_array['type'];

					if ( is_array($type)){
						foreach($type as $array){
							if($array['type'] == 'text'){
								$id = $array['id'];
								$std = $array['std'];
								$new_value = $output[$id];
								if($new_value == ''){ $new_value = $std; }
								update_option( $id, stripslashes($new_value));
							}
						}
					}
					elseif($new_value == '' && $type == 'checkbox'){ // Checkbox Save

						update_option($id,'false');
					}
					elseif ($new_value == 'true' && $type == 'checkbox'){ // Checkbox Save

						update_option($id,'true');
					}
					elseif($type == 'multicheck'){ // Multi Check Save

						$option_options = $option_array['options'];

						foreach ($option_options as $options_id => $options_value){

							$multicheck_id = $id . "_" . $options_id;

							if(!isset($output[$multicheck_id])){
							  update_option($multicheck_id,'false');
							}
							else{
							   update_option($multicheck_id,'true');
							}
						}
					}

					elseif($type != 'upload_min'){

						update_option($id,stripslashes($new_value));
					}
				}
			}

	}

  die();

}



/*-----------------------------------------------------------------------------------*/
/* Cases fpr various option types
/*-----------------------------------------------------------------------------------*/

function siteoptions_machine($options) {

    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {

		$counter++;
		$val = '';
		//Start Heading
		 if ( $value['type'] != "heading" )
		 {
		 	$class = '';
      if(isset( $value['class'] )) { $class = $value['class']; }

			$output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			if ( ! empty( $value['name']) ) {
        $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
      }
			$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";

		 }
		 //End Heading
		$select_value = '';
		switch ( $value['type'] ) {

		case 'text':
			$val = $value['std'];
			$std = get_option($value['id']);
			if ( $std != "") { $val = $std; }
			$output .= '<input class="of-input" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
		break;







		case 'select':

			$output .= '<select class="of-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';

			$select_value = get_option($value['id']);

			foreach ($value['options'] as $option) {

				$selected = '';

				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';}
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }

				 $output .= '<option'. $selected .'>';
				 $output .= $option;
				 $output .= '</option>';

			 }
			 $output .= '</select>';


		break;






		case 'fontsize':

		/* Font Size */
			$val = $default['size'];
			if ( $typography_stored['size'] != "") { $val = $typography_stored['size']; }
			$output .= '<select class="of-typography of-typography-size" name="'. $value['id'].'_size" id="'. $value['id'].'_size">';
				for ($i = 9; $i < 71; $i++){
					if($val == $i){ $active = 'selected="selected"'; } else { $active = ''; }
					$output .= '<option value="'. $i .'" ' . $active . '>'. $i .'px</option>'; }
			$output .= '</select>';


		break;
        case "display_id":

            $output .= '<ul id="display_id">';
            foreach ($value['options'] as $cat_id => $option) {
			$output .= '<li><b>'. $option .'</b><strong> ID:'.$cat_id.'</strong></li>';
			}
			$output .= '</ul>';

         break;
		case 'display_id2':
			$val = $value['std'];
			$std = get_option($value['id']);
			if ( $std != "") { $val = $std; }
			$output .= '<input class="of-input" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
		break;

		case "multicheck":

			$std =  $value['std'];

			foreach ($value['options'] as $key => $option) {

			$tt_key = $value['id'] . '_' . $key;
			$saved_std = get_option($tt_key);

			if(!empty($saved_std))
			{
				  if($saved_std == 'true'){
					 $checked = 'checked="checked"';
				  }
				  else{
					  $checked = '';
				  }
			}
			elseif( $std == $key) {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';                                                                                    }
			$output .= '<input type="checkbox" class="checkbox of-input" name="'. $tt_key .'" id="'. $tt_key .'" value="true" '. $checked .' /><label for="'. $tt_key .'">'. $option .'</label><br />';

			}
		break;


		case 'textarea':

			$cols = '8';
			$ta_value = '';

			if(isset($value['std'])) {

				$ta_value = $value['std'];

				if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
					} else { $cols = '8'; }
				}

			}
				$std = get_option($value['id']);
				if( $std != "") { $ta_value = stripslashes( $std ); }
				$output .= '<textarea class="of-input" name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';


		break;








		case "radio":

			 $select_value = get_option( $value['id']);

			 foreach ($value['options'] as $key => $option)
			 {

				 $checked = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; }
				   } else {
					if ($value['std'] == $key) { $checked = ' checked'; }
				   }
				$output .= '<input class="of-input of-radio" type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'';

			}

		break;









		case "checkbox":

		   $std = $value['std'];

		   $saved_std = get_option($value['id']);

		   $checked = '';

			if(!empty($saved_std)) {
				if($saved_std == 'true') {
				$checked = 'checked="checked"';
				}
				else{
				   $checked = '';
				}
			}
			elseif( $std == 'true') {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}
			$output .= '<input type="checkbox" class="checkbox of-input" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';

		break;








		case "upload":

			$output .= siteoptions_uploader_function($value['id'],$value['std'],null);

		break;









		case "upload_min":

			$output .= siteoptions_uploader_function($value['id'],$value['std'],'min');

		break;
		case "color":
			$val = $value['std'];
			$stored  = get_option( $value['id'] );
			if ( $stored != "") { $val = $stored; }
			$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div></div></div>';
			$output .= '<input class="of-color" name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
		break;









		case "images":
			$i = 0;
			$select_value = get_option( $value['id']);

			foreach ($value['options'] as $key => $option)
			 {
			 $i++;

				 $checked = '';
				 $selected = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
				    } else {
						if ($value['std'] == $key) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
						elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
						elseif ($i == 1  && $value['std'] == '') { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
						else { $checked = ''; }
					}

				$output .= '<span>';
				$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio" value="'.$key.'" name="'. $value['id'].'" '.$checked.' />';
				$output .= '<div class="of-radio-img-label">'. $key .'</div>';
				$output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" onClick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				$output .= '</span>';

			}

		break;


		case "titletab":
			$default = $value['std'];
			$output .= $default;
		break;

		case "info":
			$default = $value['std'];
			$output .= $default;
		break;

		case "warning":
			$default = $value['std'];
			$output .= $default;
		break;

        case "plugincheck":
			if (function_exists( $value['function'] )) {
				$output .= '<div class="info-plugincheck"><h3><img alt="no" src="'.get_template_directory_uri().'/images/yes.png" />This plugin is installed !</h3></div>';
				}
				else {
				$output .= '<div class="info-plugincheck2"><h3><img alt="no" src="'.get_template_directory_uri().'/images/no.png" /> This plugin is not installed yet or deactivated !</h3></div>';
				}
        break;

		case "heading":

			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = preg_replace("/[^A-Za-z0-9]/", "", strtolower($value['name']) );
      echo "<!-- jquery_click_hook: " . $jquery_click_hook . " -->";
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<li><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
			$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
		break;
		}

		// if TYPE is an array, formatted into smaller inputs... ie smaller values
		if ( is_array($value['type'])) {
			foreach($value['type'] as $array){

					$id = $array['id'];
					$std = $array['std'];
					$saved_std = get_option($id);
					if($saved_std != $std){$std = $saved_std;}
					$meta = $array['meta'];

					if($array['type'] == 'text') { // Only text at this point

						 $output .= '<input class="input-text-small of-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';
						 $output .= '<span class="meta-two">'.$meta.'</span>';
					}
				}
		}
		if ( $value['type'] != "heading" ) {
			if ( $value['type'] != "checkbox" )
				{
				$output .= '<br/>';
				}
			if(!isset($value['desc'])){ $explain_value = ''; } else{ $explain_value = $value['desc']; }
			$output .= '</div><div class="explain">'. $explain_value .'</div>'."\n";
			$output .= '<div class="clear"> </div></div></div>'."\n";
			}

	}
    $output .= '</div>';
    return array($output,$menu);

}










/*-----------------------------------------------------------------------------------*/
/* File Uploading
/*-----------------------------------------------------------------------------------*/

function siteoptions_uploader_function($id,$std,$mod){

	$uploader = '';
    $upload = get_option($id);

	if($mod != 'min') {
			$val = $std;
            if ( get_option( $id ) != "") { $val = get_option($id); }
            $uploader .= '<input class="of-input" name="'. $id .'" id="'. $id .'_upload" type="text" value="'. $val .'" />';
	}

	$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">Upload Image</span>';

	if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}

	$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
	$uploader .='</div>' . "\n";
    $uploader .= '<div class="clear"></div>' . "\n";
	if(!empty($upload)){
    	$uploader .= '<a class="of-uploaded-image" href="'. $upload . '">';
    	$uploader .= '<img class="of-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
    	$uploader .= '</a>';
		}
	$uploader .= '<div class="clear"></div>' . "\n";


return $uploader;
}

?>