import React from 'react';
import { registerComponent } from 'ComponentThemes/lib';

const PostAuthor = ( { author, className } ) => {
	return (
		<span className={ className }>
			by { author || 'No author' }
		</span>
	);
};

registerComponent( 'PostAuthor', PostAuthor, {
	title: 'Post Author',
	description: 'The author block for a post. Use inside a PostBody.',
	editableProps: {
		author: {
			type: 'string',
			label: 'The author string for the post'
		},
	},
} );
