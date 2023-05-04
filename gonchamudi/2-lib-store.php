<?php
class Storage {
  // (A) CONSTRUCTOR - CONNECT TO DATABASE
  private $pdo = null;
  private $stmt = null;
  public $error = null;
  function __construct () {
    $this->pdo = new PDO(
      "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
      DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  }

  // (B) DESTRUCTOR - CLOSE DATABASE CONNECTION
  function __destruct () {
    if ($this->stmt !== null) { $this->stmt = null; }
    if ($this->pdo !== null) { $this->pdo = null; }
  }

  // (C) HELPER FUNCTION - RUN SQL QUERY
  function query ($sql, $data=null) {
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute($data);
  }

  // (D) SAVE UPLOADED FILE
  function save () {
    $this->query(
      "INSERT INTO `storage` (`file_name`, `file_mime`, `file_data`) VALUES (?,?,?)",
      [
        $_FILES["upload"]["name"],
        mime_content_type($_FILES["upload"]["tmp_name"]),
        file_get_contents($_FILES["upload"]["tmp_name"])
      ]
    );
    return true;
  }

  // (E) LOAD FILE FROM DATABASE
  function load ($name) {
    // (E1) GET FILE
    $this->query(
      "SELECT `file_mime`, `file_data` FROM `storage` WHERE `file_name`=?", [$name]
    );
    $file = $this->stmt->fetch();

    // (E2) FILE NOT FOUND
    if ($file===false) {
      $this->error = "$name not found";
      return false;
    }

    // (E3) OUTPUT FILE
    // header("Content-type: " . $file["file_mime"]);
    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"". $name ."\""); 
    echo $file["file_data"];
  }

  // (F) GET ALL FILES
  function get () {
    $this->query("SELECT `file_name` FROM `storage`");
    $files = [];
    while ($r = $this->stmt->fetchColumn()) { $files[] = $r; }
    return $files;
  }
}

// (G) DATABASE SETTINGS - CHANGE TO YOUR OWN!
define("DB_HOST", "localhost");
define("DB_NAME", "gonchamudi");
define("DB_CHARSET", "utf8mb4");
define("DB_USER", "root");
define("DB_PASSWORD", "");

// (H) NEW STORAGE OBJECT
$_STORE = new Storage();

//vjsjvhnjsnv