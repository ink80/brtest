<?php
/* Plugin name: My plugin 
* Description: Плагин для тестового задания BR Lab
*/


function register_taxonomy_bet_type() {
 
	$args = array(
		'labels' => array(
			'menu_name' => 'Тип ставки'
		),
		'public' => true,
		'hierarchical' => true,
	);
	register_taxonomy( 'bet_type', 'bets', $args );
}

function add_taxonomy_tags() {
    wp_insert_term( 'Ординар', 'bet_type', array(
        'description' => '',
        'parent'      => 0,
        'slug'        => 'ordinare',
    ) );

    wp_insert_term( 'Экспресс', 'bet_type', array(
        'description' => '',
        'parent'      => 0,
        'slug'        => 'express',
    ) );
}

function register_post_type_bets() {

    $labels = array(
        'name' => 'Ставки',
        'singular_name' => 'ставка',
        'add_new' => 'Добавить ставку',
        'add_new_item' => 'Добавить ставку',
        'edit_item' => 'Редактировать ставку',
        'new_item' => 'Новая ставка',
        'all_items' => 'Все ставки',
        'search_items' => 'Искать ставку',
        'not_found' =>  'Ставка по заданным критериям не найдено.',
        'not_found_in_trash' => 'В корзине нет ставок.',
        'menu_name' => 'Ставки'
    );

    $capabilities = array(
        'publish_posts' => 'publish_bets',
        'edit_posts' => 'edit_bets',
        'edit_others_posts' => 'edit_others_bets',
        'delete_posts' => 'delete_bets',
        'delete_others_posts' => 'delete_others_bets',
        'read_private_posts' => 'read_private_bets',
        'edit_post' => 'edit_bets',
        'delete_post' => 'delete_bets',
        'read_post' => 'read_bets'
    );
  
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_in_admin_bar'   => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-admin-post',
        'menu_position' => 2,
        'capability_type' => 'post',
        'capabilities' => $capabilities,
        'query_var' => true,
        'supports' => array( 'title', 'editor', 'author' ),
        'taxonomies' => ['bet_type'],
    );
  
    register_post_type( 'bets', $args );
}

function plugin_init(){
    register_taxonomy_bet_type();
    add_taxonomy_tags();
    register_post_type_bets();
}

add_action( 'init', 'plugin_init' );


register_activation_hook( __FILE__, 'new_role_plugin_on_activate' );
 
function new_role_plugin_on_activate() {
    $cupper = get_role( 'cupper' );
    if ($cupper) {
        remove_role( 'cupper' );
    }
    
    $subscriber = get_role( 'subscriber' );
    $cupper_capabilities = $subscriber->capabilities;
	add_role( 'cupper', 'Каппер', $cupper_capabilities );

    $moderator = get_role( 'moderator' );
    if ($moderator) {
        remove_role( 'moderator' );
    }

    $moderator_capabilities = array
        (
            'publish_bets' => true,
            'edit_bets' => true,
            'edit_bet' => true,
            'edit_others_bets' => true,
            'delete_bets' => true,
            'delete_others_bets' => true,
            'read_private_bets' => true,
            'delete_bets' => true,
            'read_bets' => true,
          //  'edit_posts' => true,
        );

	add_role( 'moderator', 'Модератор', $moderator_capabilities );
}


add_filter( 'user_has_cap', 'cupper_has_cap', 10, 4 );

/**
 * Меняем возможности ролей.
 *
 * @param array   $afllcaps
 * @param array   $caps
 * @param array   $args
 * @param WP_User $user
 *
 * @return array
 */
function cupper_has_cap( $allcaps, $caps, $args, $user ) {

	$role = 'cupper';
	if ( ! empty( $allcaps[ $role ] ) ) {
    		$allcaps['read'] = true;
            $allcaps['edit_bets']           = true;
    		$allcaps['edit_published_bets'] = true;
    	}

    $role = 'administrator';
	if ( ! empty( $allcaps[ $role ] ) ) {
    		$allcaps['read'] = true;
            $allcaps['edit_bets']           = true;
    		$allcaps['edit_published_bets'] = true;
    		$allcaps['edit_other_bets'] = true;
    		$allcaps['delete_bets'] = true;
    	}

	return $allcaps;
}


add_action( 'admin_menu', 'remove_admin_menus' );

function remove_admin_menus(){
    if( current_user_can('cupper') or current_user_can('moderator') ) { 

        global $menu;
    
        $unset_titles = [
            __( 'Posts' ),
        ];
    
        end( $menu );
        while( prev( $menu ) ){
    
            $value = explode( ' ', $menu[ key( $menu ) ][0] );
            $title = $value[0] ?: '';
    
            if( in_array( $title, $unset_titles, true ) ){
                unset( $menu[ key( $menu ) ] );
            }
        }
    
    }
}