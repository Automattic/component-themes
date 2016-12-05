/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

const ColumnComponent = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};

ColumnComponent.description = 'A generic layout component for rendering children with no styling included.';
ColumnComponent.hasChildren = true;

registerComponent( 'ColumnComponent', ColumnComponent );
