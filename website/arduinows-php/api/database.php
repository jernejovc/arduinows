<?php
include "../db.config.php";

class DB {
  private $conn = null;

  public function connect() {
      $conn =  mysqli_connect($DB_HOST,
                              $DB_USER,
                              $DB_PASSWORD,
                              $DB_DATABASE) or die("Cannot connect to database!");
  }
  
  public function connection() {
    return $conn;
  }
  
  function __destruct() {
    if( $conn != null)
      mysqli_close($conn);
  }
}

?>
