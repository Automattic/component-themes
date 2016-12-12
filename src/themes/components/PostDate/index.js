/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

const PostDate = ( { date, className } ) => {
	return (
		<span className={ className }>
			{ date || 'No date' }
		</span>
	);
};

registerComponent( 'PostDate', PostDate, {
	title: 'Post Date',
	description: 'The date for a post. Use inside a PostBody.',
	editableProps: {
		date: {
			type: 'string',
			label: 'The date string for the post'
		},
	},
} );

