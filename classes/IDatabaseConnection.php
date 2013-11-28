<?php
interface IDatabaseConnection {
	/**
	 * Closes the database connection.
	 */
	public function disconnect();

	/**
	 * Returns whether or not database connection is active.
	 */
	public function isConnected();

	/**
	 * Executes an sql query; data is returned from the DB
	 * in a series of arrays that describe each column, so the
	 * function converts the array so that each row describes
	 * a record
	 * @param string $strQuery - the SQL query
	 * @param array $arrBindVar
	 * @return array - the result of the query in the array of rows, where each row
	 * holds the array of data for a record; the column names are capitalized keys
	 */
	public function query($strQuery, array $arrBindVar = array());

	/**
	 * - Executes an sql query; data is returned from the DB
	 * in a series of arrays that describe each column, so the
	 * function converts the array so that each row describes a record
	 * - Only returns the rows desired; $iNumRowsSkip is how many rows to skip,
	 * and $iTotalRows is the number of rows to return after skipping $iNumRowsSkip rows
	 * @param array $arrResults - a reference to the array of results
	 * @param <type> $iNumRowsSkip
	 * @param <type> $iTotalRows
	 * @param <type> $strQuery
	 * @param array $arrBindVar
	 */
	public function querySubset(array & $arrResults, $iNumRowsSkip, $iTotalRows, $strQuery, array $arrBindVar = array() );

	public function nextRecord( $stmt );

    public function & createStmt( $strQuery, array $arrBindVar );

	/**
	 * Runs a single SQL command.
	 * @param string $strSQLCommand
	 * @param array $arrBindVar
	 * @param array $arrReturnVals
	 * @param array $bLogTransactions - whether or not to log the SQL transaction
	 * @return boolean - whether or not execution has been successful.
	 */
    public function runCommand($strSQLCommand, array $arrBindVar, array & $arrReturnVals);

	public function commit();
}
?>
