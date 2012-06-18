<?php
/** Image PlaceHolder for PHP.
 * Usage : iph.php?<WIDTH>x<HEIGHT>/<COLOR>
 * Ex : iph.php?800x600/FF00FF
 *
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://sam.zoy.org/wtfpl/COPYING for more details.
 *
 * Author: Léo Peltier <contact@leo-peltier.fr>
 * */

return main();


/** Fetch the requested image size from $_SERVER['QUERY_STRING'].
 * \returns ({x:int, y:int}) : queried image size.
 * */
function get_size_from_query() {
	if(empty($_SERVER['QUERY_STRING']))
		return null;

	$matches = array();
	preg_match('`\d+(x|\*|⋅|×)\d+`i', $_SERVER['QUERY_STRING'], $matches);
	if(count($matches) != 2)
		return null;

	list($x, $y) = array_map('intval', explode($matches[1], $matches[0]));
	return (object) compact('x', 'y');
}


/** Fetch the requested image color.
 * \return (int) : image color.
 * */
function get_filling_from_query() {
	if(empty($_SERVER['QUERY_STRING']))
		return null;

	$matches = array();
	preg_match('`[a-f0-9]{6}`i', $_SERVER['QUERY_STRING'], $matches);
	if(!count($matches))
		return null;

	return (int) base_convert($matches[0], 16, 10);
}


/** Draw an outer border.
 * \param $img (GD2 image) : image.
 * \param $size ({x:int, y:int}) : image size.
 * \param $color (GD2 color) : color to use for the border.
 * \param $thickness (int>0) : border thickness.
 * */
function imageborder(&$img, $size, $color = null, $thickness = 4) {
	if(!$color)
		$color = 0;

	$offset = floor($thickness/2);
	$box = (object) array(
		'ax' => $offset,
		'ay' => $offset,
		'bx' => $size->x - $offset - 1,
		'by' => $size->y - $offset - 1
	);

	imagesetthickness($img, $thickness);
	imagerectangle($img, $box->ax, $box->ay, $box->bx, $box->by, $color);
}


/// Get rid of output buffering.
function ob_end_clean_all() {
	$level = ob_get_level();
	for($i=0; $i<$level; $i++) {
		ob_end_clean();
	}
}


/** Output a PNG from a GD2 image.
 * \param $img (GD2 image) : image to show.
 * */
function print_png($img) {
	ob_end_clean_all();
	header('Content-Type: image/png');
	imagepng($img);
}


/// Script entry point.
function main() {
	$size = get_size_from_query();
	$filling = get_filling_from_query() ?: 0xE7F1F0;

	if(!$size)
		return;

	$img = imagecreatetruecolor($size->x, $size->y);
	imagefill($img, 0, 0, $filling);
	print_png($img);
}

