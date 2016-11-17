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

class ThemePreview extends Component {
	render() {
		const children = buildComponentsFromTheme(
			this.props.theme,
			this.props.content || {}
		);
		const styles = buildStylesFromTheme( this.props.theme, this.props.content );
		return (
			<div className="PrometheusBuilder">
				<Styles styles={ styles } />
				{ children }
			</div>
		);
	}
}

ThemePreview.propTypes = {
	theme: PropTypes.object.isRequired,
	content: PropTypes.object,
};

export default ThemePreview;
