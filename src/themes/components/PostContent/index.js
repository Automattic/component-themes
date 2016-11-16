import React from 'react';
import { getPropsFromParent } from '~/src/lib/component-builder';

function convertNewlines( content ) {
	return content.replace( /\n/g, '<br />' );
}

// eslint-disable-next-line react/no-danger
const PostContentInner = ( { content } ) => <div className="PostContent__content" dangerouslySetInnerHTML={ { __html: convertNewlines( content ) } } />;

const PostContent = ( { content, className } ) => {
	return (
		<div className={ className }>
			<PostContentInner content={ content || 'No content' } />
		</div>
	);
};

PostContent.description = 'The content of a post, rendered as html. Use inside a PostBody.';
PostContent.editableProps = {
	content: {
		type: 'string',
		label: 'The html content of a post.'
	}
};

const mapProps = ( { content } ) => ( { content } );
export default getPropsFromParent( mapProps )( PostContent );
