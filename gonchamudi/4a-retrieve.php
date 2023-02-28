<?php
// (A) GET FILES
require "2-lib-store.php";
$files = $_STORE->get();

// (B) HTML PICK FILE ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Output File</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="0-dummy.css">
  </head>
  <body>
    <form method="post" action="4b-retrieve.php" target="_blank">
      <select name="file"><?php
        foreach ($files as $f) {
          echo "<option>$f</option>";
        }
      ?></select>
      <input type="submit" value="Download">
    </form>
  </body>
</html>