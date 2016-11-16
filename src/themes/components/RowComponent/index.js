import React from 'react';
//import './RowComponent.css';

const RowComponent = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};

RowComponent.description = 'A wrapper for a row of components. Always use this as the parent component for content components.';
RowComponent.hasChildren = true;

export default RowComponent;
