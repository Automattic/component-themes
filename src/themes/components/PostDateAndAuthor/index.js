import React from 'react';
import { getPropsFromParent } from '~/src/lib/component-builder';

const PostDateAndAuthor = ( { date, author, className } ) => {
	return (
		<div className={ className }>
			{ date || 'No date' } by { author || 'No author' }
		</div>
	);
};

PostDateAndAuthor.description = 'The date and author block for a post. Use inside a PostBody.';
PostDateAndAuthor.editableProps = {
	date: {
		type: 'string',
		label: 'The date string for the post'
	},
	author: {
		type: 'string',
		label: 'The author string for the post'
	},
};

const mapProps = ( { date, author } ) => ( { date, author } );
export default getPropsFromParent( mapProps )( PostDateAndAuthor );
