<?php
include_once( dirname(__FILE__) . "/IDatabaseConnection.php" );
class OracleDatabaseConn implements IDatabaseConnection {
	private $connResource;


	public final function __construct($oraUser, $oraPassword, $oraName) {
		$dbConn = @oci_connect($oraUser, $oraPassword, $oraName);
		if ($dbConn == false){
			$err = oci_error();
			throw new Exception("Error attempting to connect to database. " . $err["message"] );
		}
		$this->setConnResource($dbConn);
	}
	protected function & getConnResource() {
		return $this->connResource;
	}
	protected function setConnResource($conn) {
		$this->connResource = $conn;
	}
	public final function isConnected() {
     	return !is_null( $this->connResource );
    }

	public final function disconnect() {
		oci_close( $this->getConnResource() );
		$this->setConnResource(null);
	}

	public final function query($strQuery, array $arrBindVar = array()) {
     	$stmt = & $this->createStmt($strQuery, $arrBindVar );

		/* returns the data in statement as an array; this array
		holds a bunch of arrays that describe each column
		*/
		$iNumRowsSkip = 0;
		$iTotalRows = -1;
		$iNumRows = oci_fetch_all($stmt, $arrResults, $iNumRowsSkip, $iTotalRows, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($stmt);

        return $arrResults;
    }

	public final function querySubset(array & $arrResults, $iNumRowsSkip, $iTotalRows, $strQuery, array $arrBindVar = array() ) {
     	$stmt = & $this->createStmt($strQuery, $arrBindVar );

		oci_fetch_all( $stmt, $arrResults, $iNumRowsSkip, $iTotalRows, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW + OCI_RETURN_NULLS );

		oci_free_statement($stmt);
    }


	public final function nextRecord( $stmt ) {
		return oci_fetch_assoc( $stmt );
	}

	/**
	 * Returns statement object.
	 * @param string $strQuery
	 * @param array $arrBindVar
	 * @return statement
	 */
    public final function & createStmt( $strQuery, array $arrBindVar ) {
        $dbConn = & $this->getConnResource();

    	if ( $this->isConnected() ) {
			// *** returns a statement identifier for the Oracle query ***
			$stmt = @oci_parse($dbConn, $strQuery);

			if ($stmt == false) {
				$error_stmt = oci_error();
				oci_free_statement($stmt);

				$strErrorText = "ERROR: {$error_stmt['code']} {$error_stmt['message']}";

				throw new Exception("Error executing SQL query. $strErrorText");
			}
			else {
				// *** Bind variables if applicable ***
				if ( !empty($arrBindVar) ) {
					foreach($arrBindVar as $strVarName => $varVarValue ) {
						oci_bind_by_name($stmt, ":$strVarName", $arrBindVar[$strVarName]);
					}
				}


				// *** execute query; data is loaded into statement ***
				$bLoadResult = @oci_execute($stmt, OCI_DEFAULT);

				if($bLoadResult == false){
					$error_stmt = oci_error($stmt);
					oci_free_statement($stmt);

					$strErrorText = "ERROR: {$error_stmt['code']} {$error_stmt['message']}";

					throw new Exception("Error executing SQL query. $strErrorText");
				}
				else {
					return $stmt;
				}
			}
        }
        else {
			throw new Exception("Cannot run query because not connected to database.");
        }
	    //return $arrNewResults;
	}

	/**
	 * Runs a single SQL command.
	 * @param string $strSQLCommand
	 * @param array $arrBindVar
	 * @param array $arrReturnVals
	 * @return boolean
	 */
    public final function runCommand($strSQLCommand, array $arrBindVar, array & $arrReturnVals) {
    	$dbConn = & $this->getConnResource();
	    $bSuccess = true;

		$stmt = @oci_parse($dbConn, $strSQLCommand);
		$strErrorText = "";

		// *** Bind variables if applicable ***
		if ( !empty($arrBindVar) ) {
			foreach($arrBindVar as $strVarName => $varVarValue ) {
				oci_bind_by_name($stmt, ":$strVarName", $arrBindVar[$strVarName]);
			}
		}

		if ( @oci_execute($stmt, OCI_DEFAULT) ) {
			oci_free_statement($stmt);

			// NEW array keys get precedence over old array keys
			$arrReturnVals = $this->getReturnVarFromBindArray($strSQLCommand, $arrBindVar) + $arrReturnVals;
		}
		else {
			$bSuccess = false;

			$error_stmt = OCIError($stmt);
			oci_free_statement($stmt);

			$strErrorText = "ERROR: {$error_stmt['code']} {$error_stmt['message']}";
		}

		if ( $bSuccess === false ) {
			$this->rollback();
			throw new Exception(
				"Error executing SQL command. $strErrorText"
			);
		}

        return $bSuccess;
    }

	/**
	 * Commits changes to database or rolls back changes.
	 * @param boolean $bSuccesss - whether was successful
	 */
    public final function commit() {
    	$dbConn = & $this->getConnResource();
		oci_commit($dbConn);
	}

	public final function rollback() {
    	$dbConn = & $this->getConnResource();
		oci_rollback($dbConn);
    }

	private function getReturnVarFromBindArray($strSQLCommand, array & $arrBindVar) {
		// *** Find return value, if applicable ***
		// Assuming only ONE RETURNING clause per command allowed.
		$strTemp = str_replace(" ", "", $strSQLCommand);
		$pos = strrpos(strtoupper($strTemp), "INTO:");
		$arrReturnVals = array();
		
		if ( $pos !== false ) {
			$strReturnBindVar = substr( $strTemp, $pos + 5 );
			if ( ereg( "^[a-zA-Z0-9_]+\$", $strReturnBindVar ) ) {
				$arrReturnVals[$strReturnBindVar] = $arrBindVar[$strReturnBindVar];
			}
		}

		return $arrReturnVals;
	}
}
?>