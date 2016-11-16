import React from 'react';

const ColumnComponent = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};

ColumnComponent.description = 'A generic layout component for rendering children with no styling included.';
ColumnComponent.hasChildren = true;

export default ColumnComponent;
