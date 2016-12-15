<?php

function ct_get_value( $ary, $key, $default = null ) {
	$ary = (array) $ary;
	return ! empty( $ary[ $key ] ) ? $ary[ $key ] : $default;
}

function ct_or( $val, $default ) {
	return ! empty( $val ) ? $val : $default;
}

function ct_array_flatten( $ary ) {
	$result = [];
	foreach ( $ary as $val ) {
		if ( is_array( $val ) ) {
			$result = array_merge( $result, $val );
		} else {
			$result[] = $val;
		}
	}
	return $result;
}

function ct_omit( $ary, $keys ) {
	$result = [];
	foreach ( $ary as $key => $val ) {
		if ( ! in_array( $key, $keys ) ) {
			$result[ $key ] = $val;
		}
	}
	return $result;
}

function ct_not_empty( $value ) {
	return ! empty( $value );
}
