<?php
$post_list = function( $props, $children ) {
	$posts = ct_get_value( $props, 'posts', [] );
	$class_name = ct_get_value( $props, 'className', '' );
	$new_children = ct_array_flatten( array_map( function( $post_data ) use ( &$children ) {
		return React::mapChildren( $children, function( $child ) use ( &$post_data ) {
			return React::cloneElement( $child, $post_data );
		} );
	}, $posts ) );
	return React::createElement( 'div', [ 'className' => $class_name ], $new_children );
};

$wrapped = Component_Themes::api_data_wrapper( $post_list, function( $get_api_endpoint ) {
	$posts_data = call_user_func( $get_api_endpoint, '/wp/v2/posts' );
	$posts = array_map( function( $post ) use ( &$get_api_endpoint ) {
		$author = call_user_func( $get_api_endpoint, '/wp/v2/users/' . $post['author'] );
		return [
			'title' => $post['title']['rendered'],
			'date' => $post['date'],
			'content' => $post['content']['rendered'],
			'link' => $post['link'],
			'author' => $author['name'],
		];
	}, ct_or( $posts_data, [] ) );
	return [ 'posts' => $posts ];
} );
Component_Themes::register_component( 'PostList', $wrapped );
