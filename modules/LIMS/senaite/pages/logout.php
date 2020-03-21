<?php


// logout process
session_destroy();
$jar->clear();
header('location: index.php');