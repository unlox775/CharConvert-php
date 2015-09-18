<?php

/********************
 *
 * CharConvert - Toolkit for common real-simple Charset Manipulation
 *
 * 2015 by David Buchanan - http://joesvolcano.net/
 *
 * GitHub: https://github.com/unlox775/CharConvert-php
 *
 ********************/

class CharConvert {

	public static function forceStdAscii($str) {
		if ( is_null($str) || ! is_string($str) ) { return $str; }

		// $str = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $str);

		$str = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $str);

		///  Scrubbing
		$find_replace_patterns = array(
			array( '/[\xc0-\xc5]/',             'A'   ),
			array( '/[\xc8-\xcb]/',             'E'   ),
			array( '/[\xcc-\xcf]/',             'I'   ),
			array( '/[\xd3-\xd6\xd8]/',         'O'   ),
			array( '/[\xd9-\xdc]/',             'U'   ),
			array( '/[\xe0-\xe5]/',             'a'   ),
			array( '/[\xe8-\xeb]/',             'e'   ),
			array( '/[\xec-\xef]/',             'i'   ),
			array( '/[\xf2-\xf6\xf0\xf8]/',     'o'   ),
			array( '/[\xf9-\xfc]/',             'u'   ),
			array( '/[\xc6]/',                  'AE'  ),
			array( '/[\xe6]/',                  'ae'  ),
			array( '/[\xc7]/',                  'C'   ),
			array( '/[\xe7]/',                  'c'   ),
			array( '/[\xd0]/',                  'D'   ),
			array( '/[\xd1]/',                  'N'   ),
			array( '/[\xf1]/',                  'n'   ),
			array( '/[\xdd]/',                  'Y'   ),
			array( '/[\xfd\xff]/',              'y'   ),

			array( '/\xaa/',                    "TM"  ),
			array( '/[\xd5\x92]/',              "'"   ),
			array( '/[\x93\x94]/',              '"'   ),
			array( '/\xa8/',                    '(R)' ),
			array( '/[\xd1\xd0]/',              '-'   ),
			array( '/[\x09\xa0\xca]/',          ' '   ),
			array( '/[\xd3\xd2]/',              '"'   ),

			array( '/[\x7f-\xff]/',              ''    ), // Done with ext ascii, strip so that Unicode ones don't barf...

			array( '/\x{2112}/u', 'TM'  ),
			array( '/\x{'. dechex(8482) .'}/u', '(c)' ),
			array( '/\x{'. dechex(174)  .'}/u', '(R)' ),
			array( '/\x{'. dechex(8820) .'}/u', '"'   ),
			array( '/\x{'. dechex(8821) .'}/u', '"'   ),
			array( '/\x{'. dechex(8216) .'}/u', "'"   ),
			array( '/\x{'. dechex(8217) .'}/u', "'"   ),

			/// finally remove all non-low-ascii values remaining
			array('/[^\n\x20-\x7E]/',           ''    ),
			);
		foreach ( $find_replace_patterns as $pat ) {
			$new_val = preg_replace($pat[0], $pat[1], $str);
			if ($new_val != $str ) error_log("forceStdAscii Worked: before=[$str] after=[$new_val]");
			///  In case of Regex errors ( Because of Unicode )
			if ( ! is_null( $new_val ) ) $str = $new_val;
			else {
				// trigger_error("forceStdAscii ERROR: invalid unicode chars (failed on, ". $pat[0] ." -> ". $pat[1] ."): ". $str, E_USER_WARNING);
				error_log("forceStdAscii ERROR: invalid unicode chars (failed on, ". $pat[0] ." -> ". $pat[1] ."): ". $str);
				continue;
			}
		}

		return $str;
	}

}
