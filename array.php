<?php

/** Return a 'self-combined' array.
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


/** Return an array containing the values of a specific key/column from
 * an array of associative arrays.
 * \param $array (array) : array to pluck.
 * \param $field (string) : field name.
 * \param $filter (function) : f(v) -> bool.
 * \return array.
 * */
function array_pluck(array $array, $field, $filter = null) {
	if($filter)
		$array = array_filter($array, $filter);

	$final = array();
	foreach($array as $v) {
		$final[] = $v[$field];
	}
	return $final;
}


/** Convert recursively arrays and any other data to an object via the (object) cast.
 * This function will not clone existing objects, change their type nor iterate
 * on them. References and object contents are preserved. If foo contains an
 * object baz and `bar = to_object(foo)`, foo and bar will have a reference to
 * the same object baz.
 * \param $data (mixed) : data to convert.
 * \return (stdClass) : converted data.
 * */
function array_to_object($data) {
	if(is_array($data) && (count($data) != count($data, COUNT_RECURSIVE)))
		return (object) array_map('to_object', $data);
	else
		return (object) $data;
}

