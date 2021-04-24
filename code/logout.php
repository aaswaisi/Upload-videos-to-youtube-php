<?php
/*
- The function of this program is to Upload videos to youtube using php.
- Islamic University Of Gaza.
- Developed by: Abd Alaziz M. Alswasis.
- @2021-2022
*/
//include api config file
require_once 'config.php';
//revoke token & destroy session
$client->revokeToken();
session_destroy();
//redirect to the homepage
header("Location:index.php"); exit;
?>
