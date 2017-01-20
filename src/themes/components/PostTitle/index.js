import React from 'react';
import { registerComponent } from 'ComponentThemes/lib';

const PostTitle = ( { link, title, className } ) => {
	return (
		<h1 className={ className }>
			<a className="PostTitle_link" href={ link }>{ title || 'No title' }</a>
		</h1>
	);
};

registerComponent( 'PostTitle', PostTitle, {
	title: 'Post Title',
	description: 'The title block for a post. Use inside a PostBody.',
	editableProps: {
		title: {
			type: 'string',
			label: 'The title string for the post'
		},
		link: {
			type: 'string',
			label: 'The url for the post title link'
		},
	},
} );
