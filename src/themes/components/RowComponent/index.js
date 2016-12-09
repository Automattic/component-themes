/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, styled } = ComponentThemes;

const RowComponent = ( props ) => {
	const { children, className } = props;
	// TODO: omit would be so nice; can we get it without all of lodash?
	const childProps = Object.keys( props ).reduce( ( keep, propKey ) => {
		if ( propKey !== 'children' && propKey !== 'className' ) {
			return Object.assign( {}, keep, { [ propKey ]: props[ propKey ] } );
		}
		return keep;
	}, {} );
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
