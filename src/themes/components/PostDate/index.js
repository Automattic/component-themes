import React from 'react';
import { getPropsFromParent } from '~/src/lib/component-builder';

const PostDate = ( { date, className } ) => {
	return (
		<span className={ className }>
			{ date || 'No date' }
		</span>
	);
};

PostDate.description = 'The date for a post. Use inside a PostBody.';
PostDate.editableProps = {
	date: {
		type: 'string',
		label: 'The date string for the post'
	},
};

const mapProps = ( { date } ) => ( { date } );
export default getPropsFromParent( mapProps )( PostDate );

