import Emitter from 'tiny-emitter';
import assign from 'lodash/assign';

var globalStorage = {};
var listeners = {};

class Storage extends Emitter {
	get() {
		return globalStorage;
	}
	update( newState ) {
		globalStorage = assign( {}, globalStorage, newState );
		this.emit( 'update' );
	}
}

var storage = new Storage();
export default storage;
