<?php

/** Returns a self-combined array.
 * Ex : array('a', 'b') : array('a' => 'a', 'b' => 'b')
 * This function can take one array as a parameter or one or more scalar values 
 * to be used as key/values.
 * \return (array) : combined array.
 * */
function array_combine_self() {
	$args = func_get_args();
	$array = null;

	if((count($args) == 1) && is_array($args[0]))
		$array = array_unique($args[0]);
	else
		$array = array_unique($args);

	return array_combine($array, $array);
}


/** Returns an array containing the values of a specific key/column from
 * an array of associative arrays.
 * \param $array (array) : array to pluck.
 * \param $field (string) : field name.
 * \param $filter (function) : f(v) -> bool.
 * \return array.
 * */
function array_pluck(array $array, $field, $filter = null) {
	$filter = $filter ?: function($v) { return true; };
	$final = array();
	foreach($array as $v) {
		if($filter($v))
			$final[] = $v[$field];
	}
	return $final;
}

