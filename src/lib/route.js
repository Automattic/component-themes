import assign from 'lodash/assign';
import storage from '~/src/lib/storage';

var initialStateSaved = false;

class Route {

	constructor() {
		this.initHistory();
	}

	initHistory() {
		window.onpopstate = ( { state } ) => {
			this.updatePageInfo( state );
		};
	}

	isInternalLink( url ) {
		// TODO:
		return true;
	}

	/**
	 * @param {String} url A relative URL to the wordpress root.
	 * @param {Object} [postInfo] An object that contains information of a single post.
	 *
	 * @example
	 * route.moveTo( '/' ); // go home. Call goHome() instead.
	 * route.moveTo( '/2016/12/post-title', { id: 1, type: 'post', slug: 'post-title' } );
	 */
	moveTo( url, postInfo ) {
		const data = storage.get();

		if ( !this.isInternalLink( url ) ) {
			location.url = url;
			return;
		}

		if ( ! initialStateSaved ) {
			history.replaceState( this.getPageInfo(), document.title, location.href );
		}

		// move to new page
		this.updatePageInfo( { type: postInfo.type, postId: postInfo.id, slug: postInfo.slug } );

		history.pushState( this.getPageInfo(), document.title, url );
	}

	goHome() {
		this.moveTo( '/', { id: '', type: 'home', slug: 'home' } );
	}

	getPageInfo() {
		return Object.assign( {}, storage.get().pageInfo );
	}

	updatePageInfo( newPageInfo ) {
		const data = storage.get();
		data.pageInfo = Object.assign( {}, data.pageInfo, newPageInfo );
		storage.update( data );
	}
}

const route = new Route();

export default route;
