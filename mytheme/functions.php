<?php
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form' ) );
add_theme_support( 'woocommerce' );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'blankslate' ) ) );
}

add_action( 'wp_enqueue_scripts', 'blankslate_enqueue' );
function blankslate_enqueue() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
}
add_action( 'wp_footer', 'blankslate_footer' );
function blankslate_footer() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );
function blankslate_document_title_separator( $sep ) {
$sep = '|';
return $sep;
}
add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
if ( $title == '' ) {
return '...';
} else {
return $title;
}
}
add_filter( 'nav_menu_link_attributes', 'blankslate_schema_url', 10 );
function blankslate_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'blankslate_wp_body_open' ) ) {
function blankslate_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'blankslate_skip_link', 5 );
function blankslate_skip_link() {
echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'blankslate' ) . '</a>';
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );
function blankslate_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );
function blankslate_excerpt_read_more_link( $more ) {
if ( !is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );
function blankslate_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
unset( $sizes['1536x1536'] );
unset( $sizes['2048x2048'] );
return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );
function blankslate_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function blankslate_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url( comment_author_link() ); ?></li>
<?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );
function blankslate_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}


/*   
 * ?????????????????????? ???????? ??????
*/

add_action ( 'wp_head', 'javascript_variables' );

function javascript_variables(){ ?>
    <script type="text/javascript">
        var ajax_nonce = '<?php echo wp_create_nonce( "security" ); ?>';
    </script><?php
}


wp_enqueue_script( 
	'validate_js',
	get_stylesheet_directory_uri() . '/js/validate.js',
	array( ),
	time(), 
	true
);


add_filter( 'custom_filter', 'custom_filter_function' );
 
function custom_filter_function( $form_description ) {
	$form_description = preg_replace("/[a-z]/i", "", $form_description); 
	return $form_description;
}


add_action('wp_ajax_send_form', 'send_form');

function send_form(){
 
    check_ajax_referer( 'security', 'security' );

	$form_description = sanitize_text_field( $_POST['description'] );
	$form_description = apply_filters( 'custom_filter', $form_description );
	
	$post_data = array(
		'post_title'    => sanitize_text_field( $_POST['title']  ),
		'post_content'  => $form_description,
		'post_status'   => 'publish',
		'post_author'   => get_current_user_id(),
		'post_type' => 'bets'
	);

	$post_id = wp_insert_post( $post_data );
	
	if( is_wp_error($post_id) ){
		echo $post_id->get_error_message();
    }
	else {
		wp_set_post_terms( $post_id, array((int) $_POST['bet_type'] ), 'bet_type', true );
    }
    wp_die();
}


add_action('wp_footer','betform_javascript',5);

function betform_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function() {
		jQuery( 'form[name="betForm"]' ).on( 'submit', function() {
			var form_data = jQuery( this ).serializeArray();
			form_data.push( { "name" : "security", "value" : ajax_nonce } );
			jQuery.ajax({
				url : '/wp-admin/admin-ajax.php', 
				data : form_data,
				type : 'post',
				success : function( response ) {
					jQuery('.go').attr('disabled', true);
					alert( '???????????? ?????????????? ????????????????????' );
				},
				fail : function( err ) {
					alert( "There was an error: " + err );
				}
			});

			return false;
		});
	});
	</script> <?php
}


add_action('wp_ajax_do_bet', 'do_bet');

function do_bet(){
 
    check_ajax_referer( 'security', 'security' );
	$post_id = (int) $_POST['post_id'];
	$bet = (int) $_POST['bet'];
	$value = add_post_meta( $post_id, 'bet_vote', $bet, true );
	if (! $value) {
		$value = update_post_meta( $post_id, 'bet_vote', $bet );
	}
    wp_die();
}


add_action('wp_footer','dobetform_javascript',6);

function dobetform_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function() {
		jQuery( 'form[name="doBetForm"]' ).on( 'submit', function() {
			var form_data = jQuery( this ).serializeArray();
			form_data.push( { "name" : "security", "value" : ajax_nonce } );

			jQuery.ajax({
				url : '/wp-admin/admin-ajax.php', // Here goes our WordPress AJAX endpoint.
				type : 'post',
				data : form_data,
				success : function( response ) {
					jQuery('.go').attr('disabled', true);
					alert( '???????????? ?????????????? ????????????????????' );
				},
				fail : function( err ) {
					alert( "There was an error: " + err );
				}
			});
			return false;
		});
	});
	</script> <?php
}


add_action( 'add_meta_boxes', 'bet_metabox' );
 
function bet_metabox() {
 
	add_meta_box(
		'bet_metabox',
		'????????????',
		'bet_metabox_callback',
		'bets',
		'normal',
		'default'
	);
}
 
function bet_metabox_callback( $post ) {
	$bet_vote = get_post_meta( $post->ID, 'bet_vote', true );
	if (!$bet_vote) {
		$bet_vote = '???????????? ???? ??????????????????????';
	}
	 echo $bet_vote;
}

