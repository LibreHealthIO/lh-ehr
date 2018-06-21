<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/16/18
 * Time: 9:55 PM
 */
?>

<?php

/**
 *	Class Database (PDO Extension)
 *  ----------------------------
 *  Description : takes care of all database operations within setup of libreehr
 *	Written by  : Mua rachmann
 *	Syntax (standard)  : $db = new Database($database_host, $database_name, $database_username, $database_password, EI_DATABASE_TYPE, $is_installation);
 *	Syntax (singleton) : $db = Database::GetInstance($database_host, $database_name, $database_username, $database_password, EI_DATABASE_TYPE, $is_installation);
 *
 *Common methods and variables
 *  PUBLIC              STATIC				 PROTECTED           PRIVATE
 *  -------             ----------          ----------          ----------
 *  __construct         GetInstance                             PrepareLogSQL
 *	__destruct          IsConnected
 *	Create
 *	AllowTransactions
 *	Open
 *	Close
 *	CloseCursor
 *	GetVersion
 *	GetDbDriver
 *	Query
 *	Exec
 *	AffectedRows
 *	RowCount
 *	ColumnCount
 *	InsertID
 *	SetEncoding
 *	Error
 *	ErrorCode
 *	ErrorInfo
 *	FetchAssoc
 *	FetchArray
 *	ShowTables
 *	ShowColumns
 *
 **/
?>

<?php
class Database
{

    // connection parameters
    private $host = '';
    private $port = '';
    private $db_driver = '';
    private $database = '';
    private $user = '';
    private $password = '';



}


?>