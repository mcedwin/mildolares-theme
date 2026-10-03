<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIL_Env {

	private static $vars = null;

	public static function file() {
		return get_template_directory() . '/.env';
	}

	public static function load() {
		if ( null !== self::$vars ) {
			return self::$vars;
		}

		self::$vars = array();
		$file       = self::file();

		if ( ! is_readable( $file ) ) {
			return self::$vars;
		}

		$lines = file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

		foreach ( (array) $lines as $line ) {
			$line = trim( $line );

			if ( '' === $line || 0 === strpos( $line, '#' ) ) {
				continue;
			}

			if ( 0 === strpos( $line, 'export ' ) ) {
				$line = substr( $line, 7 );
			}

			$pos = strpos( $line, '=' );

			if ( false === $pos ) {
				continue;
			}

			$key = trim( substr( $line, 0, $pos ) );
			$val = trim( substr( $line, $pos + 1 ) );

			if ( preg_match( '/^([\'"])(.*)\1$/s', $val, $m ) ) {
				$val = $m[2];
			}

			if ( '' === $key ) {
				continue;
			}

			self::$vars[ $key ] = $val;
		}

		return self::$vars;
	}

	public static function get( $key, $default = '' ) {
		self::load();

		if ( isset( self::$vars[ $key ] ) && '' !== self::$vars[ $key ] ) {
			return self::$vars[ $key ];
		}

		if ( defined( $key ) ) {
			$const = constant( $key );

			if ( '' !== $const && null !== $const ) {
				return $const;
			}
		}

		return $default;
	}

	public static function has( $key ) {
		return '' !== self::get( $key );
	}

	public static function masked( $key ) {
		$value = self::get( $key );

		if ( '' === $value ) {
			return '';
		}

		$len = strlen( $value );

		if ( $len <= 8 ) {
			return str_repeat( '*', $len );
		}

		return substr( $value, 0, 4 ) . str_repeat( '*', 12 ) . substr( $value, -4 );
	}
}
