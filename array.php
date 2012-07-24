<?php
/** Collection of array-related functions for PHP.
 *
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://sam.zoy.org/wtfpl/COPYING for more details.
 *
 * Author: Léo Peltier <contact@leo-peltier.fr>
 * */

/** Return a 'self-combined' array.
 * Ex : array('a', 'b') : array('a' => 'a', 'b' => 'b')
 * This function can take one array as a parameter or one or more scalar values 
 * to be used as key/values.
 * \return (array) : combined array.
 * */
function array_combine_self() {
	$args = func_get_args();
	$array = null;

	if((count($args) === 1) && is_array($args[0]))
		$array = array_unique($args[0]);
	else
		$array = array_unique($args);

	return array_combine($array, $array);
}


/** Return an array containing the values of a specific key/column from
 * an array of associative arrays.
 * \param $array (array) : array to pluck.
 * \param $field (string) : field name.
 * \return array.
 * */
function array_pluck(array $array, $field) {
	$final = array();
	foreach($array as $v) {
		$final[] = $v[$field];
	}
	return $final;
}


/** Return true if the array is multidimensional (ie. contains another array).
 * \param $array (array) : array to check.
 * \return true if the array is multidimensional, false otherwise.
 * */
function array_is_multidimensional(array $array) {
	return count($array) !== count($array, COUNT_RECURSIVE);
}


/** Convert recursively arrays an object via the (object) cast.
 * This function will not clone existing objects, change their type nor iterate
 * on them. References and object contents are preserved. If foo contains an
 * object baz and `bar = to_object(foo)`, foo and bar will have a reference to
 * the same object baz.
 * \param $data (mixed) : data to convert.
 * \return (stdClass) : converted data.
 * */
function array_to_object($data) {
	return is_array($data) ? (object) array_map('array_to_object', $data) : $data;
}


/** Converts recursively a stdClass to an array.
 * This function will only change the type of stdClass instances, other
 * objects will be left untouched.
 * \param $data (mixed) : array or stdObject to convert.
 * \return (array) : resulting array.
 * */
function object_to_array($data) {
	if(is_array($data) || (is_object($data) && $data instanceof stdClass))
		return array_map('object_to_array', (array) $data);
	else
		return $data;
}


/** Merge a 'dotted' array into a standard array.
 * \param $array (array) : the destination array.
 * \param $patch (array) : the 'dotted' array.
 * \return (array) : merge result.
 * */
function array_dot_merge(array $array, array $patch) {
	$final = $array;
	foreach($patch as $k => $v) {
		$final = array_dot_set($final, $k, $v);
	}
	return $final;
}


/** Set a value of an array using a dotted path.
 * \param $array (array) : the array to update.
 * \param $key (string) : the array key in.a.dotted.form.
 * \param $value (mixed) : the value to set.
 * \return (array) : the updated array.
 * */
function array_dot_set(array $array, $key, $value) {
	$final = $array;
	if(strpos($key, '.') === false) {
		$final[$key] = $value;
		return $final;
	}

	$target = &$final;
	$keys = explode('.', $key);
	foreach($keys as $curKey) {
		if(!array_key_exists($curKey, $target))
			$target[$curKey] = array();
		$target = &$target[$curKey];
	}
	$target = $value;

	return $final;
}


/** Set a value of an array using a dotted path.
 * \param $array (array) : the array to update.
 * \param $key (string) : the array key in.a.dotted.form.
 * \return (mixed) : the obtained value, null if not found.
 * */
function array_dot_get(array $array, $key) {
	if(strpos($key, '.') === false)
		return $array[$key];

	$target = &$array;
	$keys = explode('.', $key);
	foreach($keys as $curKey) {
		if(!array_key_exists($curKey, $target))
			return null;
		$target = &$target[$curKey];
	}

	return $target;
}

