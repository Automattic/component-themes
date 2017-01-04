const locales = {
	en: {
		month: [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
		monthAbbr: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
		weekday: [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thurday', 'Friday', 'Saturday' ],
		weekdayAbbr: [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],
		meridiem: [ 'am', 'pm' ],
	}
};
const timezone = { id: '', abbr: '', offset: 0 };
const ordinalSuffix = [ 'th', 'st', 'nd', 'rd' ];

function fillZeros( num, size ) {
	num = num + '';
	while( num.length < size ) {
		num = '0' + num;
	}
	return num;
}

function getI18nStr( locale, key, index ) {
	if ( locales[ locale ] && locales[ locale ][ key ] && locales[ locale ][ key ][ index ] ) {
		return locales[ locale ][ key ][ index ];
	}

	return locales.en[ key ][ index ];
}

function getDayOfYear( date ) {
	const dateObj = new Date( date );
}

function countDaysOfMonth( date ) {
	const month = date.getMonth() + 1;
	if ( 2 === month ) {
		return isLeapYear( date ) ? 29 : 28;
	}

	return ( ( month < 8 && month % 2 ) || ( month > 7 && 0 === month % 2 ) ) ? 31 : 30;
}

function isLeapYear( date ) {
	const dateObj = new Date( date );
	dateObj.setMonth( 1 );
	dateObj.setDate( 29 );

	return ( dateObj.getMonth() === 1 );
}

export default {
	format( format, date, locale = '' ) {
		const year = date.getFullYear();
		const month = date.getMonth();
		const day = date.getDate();
		const weekday = date.getDay();
		const hours = date.getHours();
		const minutes = date.getMinutes();
		const seconds = date.getSeconds();

		const parts = {
			/* day */
			d: fillZeros( day, 2 ),
			D: getI18nStr( locale, 'weekdayAbbr', weekday ),
			j: day,
			l: getI18nStr( locale, 'weekday', weekday ),
			N: weekday || 7,
			S: ordinalSuffix[ day % 10 ] || ordinalSuffix[ 0 ],
			w: weekday,
			z: getDayOfYear( date ),
			/* month */
			F: getI18nStr( locale, 'month', month ),
			m: fillZeros( month + 1, 2 ),
			M: getI18nStr( locale, 'monthAbbr', month ),
			n: month + 1,
			t: countDaysOfMonth( date ),
			/* year */
			L: isLeapYear( date ) ? 1 : 0,
			Y: year,
			y: ( year + '' ).substr( 2 ),
			/* time */
			a: getI18nStr( locale, 'meridiem',  hours < 12 ? 0 : 1 ),
			A: getI18nStr( locale, 'meridiem',  hours < 12 ? 0 : 1 ).toUpperCase(),
			g: hours % 12 || 1,
			G: hours,
			h: fillZeros( hours % 12 || 1, 2 ),
			H: fillZeros( hours, 2 ),
			i: fillZeros( minutes, 2 ),
			s: fillZeros( seconds, 2 ),
			u: '000000',
			e: timezone.id,
			T: timezone.abbr,
			Z: timezone.offset,
		};

		return format.replace( /\\?[a-zA-Z\\]/g, ( match ) => {
			if ( 2 === match.length ) {
				return match[1];
			}
			return parts[ match ] || match;
		} );
	},
	addLocale( locale, data ) {
		locales[ locale ] = data;
	},
	setTimezone( id, abbr, offset ) {
		timezone.id = id;
		timezone.abbr = abbr;
		timezone.offset = offset;
	}
};
