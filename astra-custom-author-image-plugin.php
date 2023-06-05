<?php
/**
 * Plugin Name: Custom Author Image for Astra Theme
 * Plugin URL: https://github.com/syllod/Custom-Author-Image-for-Astra_wp-plugin
 * Description: A plugin to add a custom author image in the author box - for Astra theme
 * Version: 1.0.0
 * Author: Sylvain L - Syllod
 * Author URI: https://github.com/syllod
*/

// Add image URL field in user account profile
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) {
	?>
	<h3><?php _e("Additional Information", "custom-author-image"); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="author_image"><?php _e("Author Image", "custom-author-image"); ?></label></th>
			<td>
				<input type="text" name="author_image" id="author_image" value="<?php echo esc_attr( get_the_author_meta( 'author_image', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Please enter the URL of your image. In Settings/Discussion, make sure the Gravatar checkbox is unchecked.", "custom-author-image"); ?></span>
			</td>
		</tr>
	</table>
	<?php
}

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_user_meta( $user_id, 'author_image', $_POST['author_image'] );
}

// Display the image in Author Box and author archive link
function prefix_astra_child_post_author_output( $output ) {
	$author_image = get_the_author_meta( 'author_image' );
	if ( ! empty( $author_image ) ) {
		$author_image_html = '<img src="' . esc_url( $author_image ) . '" alt="Author Image" style="width: 100px; height: 100px; border-radius: 50%; margin-right: 10px; object-fit: cover;">';
		$output = str_replace( '<div class="post-author-avatar">', '<div class="post-author-avatar">' . $author_image_html, $output );
	}
	return $output;
}
add_filter( 'astra_post_author_output', 'prefix_astra_child_post_author_output' );
