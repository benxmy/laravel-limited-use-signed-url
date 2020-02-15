<?php

return [
	/*
	 * The default amount of minutes to add to now() [the time the url is generated], 
	 * after which the new url will not be usable.
	 */
	'expires_in_minutes' => 2,

	/*
	 * The number of times the url will be allowed to be accessed.  
	 * Generally, 2 is the ideal value for embedded streaming links (ex: audio and video) 
	 * due to browsers making two requests for a resource.
	 */
	'uses_allowed' => 2
];