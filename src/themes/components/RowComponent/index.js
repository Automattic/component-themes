/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, styled, omit } = ComponentThemes;

const RowComponent = ( props ) => {
	const { children, className } = props;
	const childProps = omit( props, [ 'children', 'className' ] );
	const newChildren = React.Children.map( children, child => React.cloneElement( child, { ...childProps } ) );
	return (
		<div className={ className }>
			{ newChildren }
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
