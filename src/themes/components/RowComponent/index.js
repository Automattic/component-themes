/**
 * External dependencies
 */
import React from 'react';

/**
 * Internal dependencies
 */
import Styles from '~/src/components/Styles';

const css = `
.RowComponent {
	display: flex;
	justify-content: space-between;
}
`;

const RowComponent = ( { children, className } ) => {
	return (
		<div className={ className }>
			<Styles styles={ css } />
			{ children }
		</div>
	);
};

RowComponent.description = 'A wrapper for a row of components. Always use this as the parent component for content components.';
RowComponent.hasChildren = true;

export default RowComponent;
