<?php
	/*	Sanitizes data passed in as parameter.
	*		@return sanitized data.
	*/
	function filterinput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = strip_tags($data);
		$data = filter_var($data, FILTER_SANITIZE_STRING);
		return $data;
	}
?>
