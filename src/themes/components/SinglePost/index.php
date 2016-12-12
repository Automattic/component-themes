<?php
$single_post = function( $props, $children ) {
	$post_data = ct_get_value( $props, 'postData', [] );
	$class_name = ct_get_value( $props, 'className', '' );
	$new_children = React::mapChildren( $children, function( $child ) use ( &$post_data ) {
		return React::cloneElement( $child, $post_data );
	} );
	return React::createElement( 'div', [ 'className' => $class_name ], $new_children );
};

$wrapped = Component_Themes::api_data_wrapper( $single_post, function( $get_api_endpoint, $state ) {
	$info = ct_get_value( $state, 'pageInfo', [] );
	$post_id = ct_get_value( $info, 'postId' );
	if ( ! $post_id ) {
		return [];
	}
	$post = call_user_func( $get_api_endpoint, '/wp/v2/posts/' . $post_id );
	$author = call_user_func( $get_api_endpoint, '/wp/v2/users/' . $post['author'] );
	return [
		'postData' => [
			'title' => $post['title']['rendered'],
			'date' => $post['date'],
			'content' => $post['content']['rendered'],
			'link' => $post['link'],
			'author' => $author['name'],
		],
	];
} );
Component_Themes::register_component( 'SinglePost', $wrapped );

