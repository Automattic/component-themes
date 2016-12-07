/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, styled } = ComponentThemes;

const RowComponent = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};

RowComponent.description = 'A wrapper for a row of components. Always use this as the parent component for content components.';
RowComponent.hasChildren = true;

const Styled = styled( RowComponent )`
	display: flex;
	justify-content: space-between;
`;

registerComponent( 'RowComponent', Styled );
