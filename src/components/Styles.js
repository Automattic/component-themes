/**
 * External dependencies
 */
import React from 'react';

const Styles = ( { styles } ) => {
	// eslint-disable-next-line react/no-danger
	return <style dangerouslySetInnerHTML={ { __html: styles } } />;
};

export default Styles;

