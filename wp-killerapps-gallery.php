<?php
/**
 * Plugin Name: Killerapps Gallery
 * Description:  Gallery with thumbnails and Ajax Loader
 * Version: 1.0
 * Author: Zosia Sobocinska
 * Author URI: http://www.killeapps.pl
 * License: GPLv2 or later
 */

$killerapps_gallery_path = plugin_dir_path(__FILE__);
$killerapps_gallery_url = plugin_dir_url(__FILE__);

require_once $killerapps_gallery_path . '/dependencies.php';

/**
 * 
 */
add_image_size( 'killerapps-gallery-thumbnail', 340, 340, TRUE );
add_image_size( 'killerapps-gallery-highres', 1366, 768, FALSE );

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'killerapps_gallery_scripts' );
function killerapps_gallery_scripts() {
	global $killerapps_gallery_url;

	wp_enqueue_script( 'stylehatch-photoset-grid', $killerapps_gallery_url . 'js/photoset-grid/jquery.photoset-grid.js', array('jquery'), '', true );
	wp_enqueue_script( 'jquery-colorbox', $killerapps_gallery_url . 'js/photoset-grid/jquery.colorbox.js', array('jquery'), '', true );
	wp_enqueue_style( 'jquery-colorbox', $killerapps_gallery_url . 'js/photoset-grid/css/colorbox.css' );

	wp_enqueue_script( 'killerapps-gallery', $killerapps_gallery_url . 'js/killerapps-gallery.js', array('jquery'), '', true );
}

/**
 * 
 */
class killerapps_Gallery {
	function killerapps_Gallery($post_types) {

		$this->post_types = $post_types;
		add_filter( 'rwmb_meta_boxes', array($this,'_register_metaboxes') );
	}

	function _register_metaboxes($meta_boxes) {
	    $prefix = 'killerapps_';
	
	    $meta_boxes[] = array(
	        'id'       => 'gallery',
	        'title'  => __('Gallery'),
	        'pages'    => $this->post_types,
	        'context'  => 'normal',
	
	        'fields' => array(
	            array(
	                'name'  => __('Gallery'),
	                'id'    => $prefix . 'gallery',
	                'type'  => 'image_advanced',
	            ),
	        )
	    );
	    return $meta_boxes;
	}

	static function show($post_id=NULL) { ?>
		<?php
		if ($gallery = rwmb_meta( 'killerapps_gallery', 'type=checkbox_list', $post_id)):
			$layout = sizeof($gallery)>1?"23":"1";
		?>
			<div class="killerapps-photoset-grid" data-layout="<?php echo $layout ?>" style="visibility: hidden;">
				<?php foreach (killerapps_array($gallery) as $img_id): ?>
					<?php
					$thumbnail = wp_get_attachment_image_src( $img_id, 'killerapps-gallery-thumbnail' );
					$thumbnail = $thumbnail[0];
					$src = wp_get_attachment_image_src( $img_id, 'huge' );
					$src = $src[0];
					$title = htmlspecialchars(get_the_title($img_id));
					echo "<img src='{$thumbnail}' data-highres='{$src}' alt='{$title}'/>";
					?>
				<?php endforeach; ?>
			</div>
		<?php
		endif;
		?>		
	<?php }
}

/**
 * Alias function for static killerapps_Gallery::show() method
 */
function killerapps_the_gallery() {
	killerapps_Gallery::show();
}


do_action('killerapps_gallery_loaded');