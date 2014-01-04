<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(FCPATH."config.php");
/*
| -------------------------------------------------------------------
| USER AGENT TYPES
| -------------------------------------------------------------------
| This file contains four arrays of user agent data.  It is used by the
| User Agent Class to help identify browser, platform, robot, and
| mobile device data.  The array keys are used to identify the device
| and the array values are used to set the actual name of the item.
|
*/

$platforms = $config['engine']['platforms'];


// The order of this array should NOT be changed. Many browsers return
// multiple browser types so we want to identify the sub-type first.
$browsers = $config['engine']['browsers'];

$mobiles = $config['engine']['mobiles'];

// There are hundreds of bots but these are the most common.
$robots = $config['engine']['robots'];

/* End of file user_agents.php */
/* Location: ./system/application/config/user_agents.php */