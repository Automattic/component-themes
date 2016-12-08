<?php

function ct_get_value( $ary, $key, $default = null ) {
	$ary = (array) $ary;
	return ! empty( $ary[ $key ] ) ? $ary[ $key ] : $default;
}

function ct_or( $val, $default ) {
	return ! empty( $val ) ? $val : $default;
}
