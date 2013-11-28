<?php
ini_set("display_errors", true);
error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL ^ E_DEPRECATED | E_STRICT);

include_once(dirname(__FILE__) . '/classes/OracleDatabaseConn.class.php');
$db = new OracleDatabaseConn('taskslocal', 'xe', 'xe');








undoMagicQuotes($_POST);
function undoMagicQuotes( & $value ) {
	if ( get_magic_quotes_gpc() ) {
		if ( is_array($value) ) {
			foreach( array_keys($value) as $key2 ) {
				$value[$key2] = undoMagicQuotes( $value[$key2] );
			}
		}
		else {
			$value = (string)$value;

			if ( strpos( $value, "\\'" ) !== false ) {
				$value = str_replace( "\\'", "'", $value );
			}
			if ( strpos( $value, "\\\"" ) !== false ) {
				$value = str_replace( "\\\"", "\"", $value );
			}
			if ( strpos( $value, "\\\\" ) !== false ) {
				$value = str_replace( "\\\\", "\\", $value );
			}
		}
	}
	return $value;
}
?>
