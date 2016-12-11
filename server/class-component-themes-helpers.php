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
		return $result;
	}
}
