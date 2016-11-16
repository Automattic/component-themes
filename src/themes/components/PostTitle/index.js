import React from 'react';
import { getPropsFromParent } from '~/src/lib/component-builder';

const PostTitle = ( { link, title, className } ) => {
	return (
		<h1 className={ className }>
			<a className="PostTitle_link" href={ link }>{ title || 'No title' }</a>
		</h1>
	);
};

PostTitle.description = 'The title block for a post. Use inside a PostBody.';
PostTitle.editableProps = {
	title: {
		type: 'string',
		label: 'The title string for the post'
	},
	link: {
		type: 'string',
		label: 'The url for the post title link'
	},
};

const mapProps = ( { link, title } ) => ( { link, title } );
export default getPropsFromParent( mapProps )( PostTitle );
