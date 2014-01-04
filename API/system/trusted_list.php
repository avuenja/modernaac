<?php
/*This list includes allowed server that can request data from API.*/
$allowed = array();
$allowed[] = "127.0.0.1"; // Local server

/* Official servers, these are 100% safe and should not be removed (might lose functionality if you do) */
$allowed[] = gethostbyname("vapus.net"); // VAPus
$allowed[] = "188.40.136.66"; // Old New VAPus 
$allowed[] = "188.40.110.66"; // Old VAPus IP (used for the fallback server)
$allowed[] = "188.40.38.144"; // Fallback server for the updater (Might request call to isValid etc)
$allowed[] = "88.208.252.193"; // ModernAAC API Server
