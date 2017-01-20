import React from 'react';
import omit from 'lodash/omit';
import { registerComponent, styled } from 'ComponentThemes/lib';

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

const Styled = styled( RowComponent )`
	display: flex;
	justify-content: space-between;
`;

registerComponent( 'RowComponent', Styled, {
	title: 'Row',
	description: 'A wrapper for a row of components. Always use this as the parent component for content components.',
	hasChildren: true,
} );
