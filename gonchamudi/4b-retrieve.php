<?php
// (A) GET FILE & FORCE DOWNLOAD
require "2-lib-store.php";
if (isset($_POST["file"])) {
  if (!$_STORE->load($_POST["file"])) { echo $_STORE->error; }
}