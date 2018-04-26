<?php
	/*Authors: Patrick Kunza (pskggc), Ryan Olson (raozp4), Chase Scanlan (cwswx7), Michael Yizhuo Du (ydypb), Jay Toebbon (ejtn78)
	Model-View-Controller implementation of Movie Browser */

	require('MovieController.php');

	$controller = new MovieController();
	$controller->run();
?>
