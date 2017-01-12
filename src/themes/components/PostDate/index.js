/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, apiDataWrapper } = ComponentThemes;

const PostDate = ( { date, date_format, className } ) => {
	var dateStr = '';

	if ( date ) {
		dateStr = ComponentThemes.date.format( date_format || 'F j, Y', new Date( date ) );
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

