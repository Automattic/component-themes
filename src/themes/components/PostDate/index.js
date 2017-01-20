import React from 'react';
import { date as ctDate, registerComponent, apiDataWrapper } from 'ComponentThemes/lib';

const PostDate = ( { date, date_format, className } ) => {
	let dateStr = '';

	if ( date ) {
		dateStr = ctDate.format( date_format || 'F j, Y', new Date( date ) );
	}

	return (
		<span className={ className }>
			{ dateStr || 'No date' }
		</span>
	);
};

const mapApiToProps = ( getApiEndpoint ) => {
	const settings = getApiEndpoint( '/wp/v2/settings' ) || {};
	return { date_format: settings.date_format };
};

registerComponent( 'PostDate', apiDataWrapper( mapApiToProps )( PostDate ), {
	title: 'Post Date',
	description: 'The date for a post. Use inside a PostBody.',
	editableProps: {
		date: {
			type: 'string',
			label: 'The date string for the post'
		},
	},
} );

