<?php
function Component_Themes_Single_Post ( $props, $children ) {
	$post_data = ct_get_value( $props, 'postData', array() );
	$class_name = ct_get_value( $props, 'className', '' );
	$new_children = React::cloneChileren( $children, $post_data );
	return React::createElement( 'div', array( 'className' => $class_name ), $new_children );
};

function Component_Themes_Single_Post_Api_Mapper( $get_api_endpoint, $state ) {
	$info = ct_get_value( $state, 'pageInfo', array() );
	$post_id = ct_get_value( $info, 'postId' );

	if ( ! $post_id ) {
		return array();
	}

	$post = call_user_func( $get_api_endpoint, '/wp/v2/posts/' . $post_id );
	$author = call_user_func( $get_api_endpoint, '/wp/v2/users/' . $post['author'] );

	return array(
		'postData' => array(
			'title' => $post['title']['rendered'],
			'date' => $post['date'],
			'content' => $post['content']['rendered'],
			'link' => $post['link'],
			'author' => $author['name'],
		),
	);
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_Single_Post', 'Component_Themes_Single_Post_Api_Mapper' );

Component_Themes::register_component( 'SinglePost', $wrapped );
