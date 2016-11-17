/**
 * External dependencies
 */
import React, { Component, PropTypes } from 'react';

/**
 * Internal dependencies
 */
import Styles from '~/src/components/Styles';
import { buildStylesFromTheme } from '~/src/lib/styles';
import { buildComponentsFromTheme } from '~/src/lib/component-builder';

class StrangerThemePage extends Component {
	render() {
		const children = buildComponentsFromTheme(
			this.props.theme,
			this.props.page,
			this.props.content || {}
		);
		const styles = buildStylesFromTheme( this.props.theme, this.props.content );
		return (
			<div className="StrangerThemes">
				<Styles styles={ styles } />
				{ children }
			</div>
		);
	}
}

StrangerThemePage.propTypes = {
	theme: PropTypes.object.isRequired,
	page: PropTypes.object.isRequired,
	content: PropTypes.object,
};

export default StrangerThemePage;
