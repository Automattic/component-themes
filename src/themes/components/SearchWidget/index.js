import React from 'react';
import { registerComponent } from 'ComponentThemes/lib';

const SearchWidget = ( { placeholder, label, buttonLabel, className } ) => {
	// TODO: import the rootUrl from the state
	const rootUrl = '/';
	return (
		<div className={ className }>
			<form role="search" method="get" className="SearchWidget__form" action={ rootUrl }>
				<label>
					{ label && <span className="screen-reader-text">{ label }</span> }
					<input type="search" className="SearchWidget__input" placeholder={ placeholder || 'search this site' } name="s" />
				</label>
				{ buttonLabel && <input type="submit" className="SearchWidget__button" value={ buttonLabel } /> }
			</form>
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
		},
		label: {
			type: 'string',
			label: 'The label for the search field. No label will hide the label.'
		},
		buttonLabel: {
			type: 'string',
			label: 'The label for the search button. No label will hide the button.'
		},
	},
} );
