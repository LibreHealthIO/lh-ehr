<?php


// logout process
session_destroy();
header('location: index.php');