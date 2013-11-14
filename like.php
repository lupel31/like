<?php
class sql
{
    public  $_dbase = false;
    private $_host  = '127.0.0.1';
    private $_user  = 'root';
    private $_pass  = 'root';
    private $_base  = 'test';

    public function __construct()
    {
        if( $this->_dbase === false )
        {
            $this->connect();
        }
    }

    public function getConnection()
    {
        return $this->_dbase;
    }

    private function connect()
    {
        try{
            $this->_dbase = new PDO( 'mysql:host='.$this->_host.';dbname='.$this->_base, $this->_user, $this->_pass );
            $this->_dbase->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->_dbase->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
        }
        catch( PDOException $e )
        {
            echo( $e->getMessage() );
        }
    }
}



$conn = new sql();
$con = $conn->getConnection();

$search = isset( $_POST[ 'search' ] ) ? $_POST[ 'search' ] : null;

if( !empty( $search ) )
{
    $search = '%'.$search.'%';
    $stmt = $con->prepare( 'SELECT name, username FROM members WHERE username LIKE :search' );
    $stmt->bindParam( ':search', $search, PDO::PARAM_STR );
    $stmt->execute();

    $fetch = $stmt->fetchAll( PDO::FETCH_ASSOC );

    foreach( $fetch AS $index => $user )
    {
        echo( $user[ 'name' ].' - '.$user[ 'username' ].'<br />' );
    }
}