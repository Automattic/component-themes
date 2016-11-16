import React from 'react';
import { makeComponentWith } from '~/src/lib/component-builder';

const PostList = ( { posts, post, className } ) => {
	const defaultPostConfig = { componentType: 'PostBody', children: [
		{ componentType: 'PostTitle' },
		{ componentType: 'PostDateAndAuthor' },
		{ componentType: 'PostContent' }
	] };
	return (
		<div className={ className }>
			{ ( posts || [] ).map( postData => makeComponentWith( post || defaultPostConfig, postData ) ) }
			{ ! posts || posts.length < 1 ? <p>No posts</p> : null }
		</div>
	);
};

PostList.description = 'A list of posts.';
PostList.editableProps = {
	posts: {
		type: 'array',
		label: 'The post data objects. Usually provided by content rather than props.'
	},
	post: {
		type: 'object',
		label: 'The component to use for rendering each post. Use PostBody and PostTitle, PostContent, etc. Defaults to a standard blog post format.'
	}
};

export default PostList;
