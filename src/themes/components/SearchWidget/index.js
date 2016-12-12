/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

const SearchWidget = ( { placeholder, className } ) => {
	return (
		<div className={ className }>
			<input className="SearchWidget__input" placeholder={ placeholder || 'search this site' } />
		</div>
	);
};

registerComponent( 'SearchWidget', SearchWidget, {
	title: 'Search Field',
	description: 'A search field.',
	editableProps: {
		placeholder: {
			type: 'string',
			label: 'The placeholder text for the search field.'
		}
	},
} );

