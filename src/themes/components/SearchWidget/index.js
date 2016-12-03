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

SearchWidget.description = 'A search field.';
SearchWidget.editableProps = {
	placeholder: {
		type: 'string',
		label: 'The placeholder text for the search field.'
	}
};

registerComponent( 'SearchWidget', SearchWidget );

