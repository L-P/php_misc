<?php
/** Image PlaceHolder for PHP.
 * Usage : iph.php?<WIDTH>x<HEIGHT>
 * Ex : iph.php?800x600
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

	$query = explode($matches[1], $matches[0]);
	if(count($query) != 2)
		return null;

	list($x, $y) = array_map('intval', $query);
	return (object) compact('x', 'y');
}


/** Fetch the requested image color.
 * \returns (int) : image color.
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
	$filling = get_filling_from_query();

	if(!$size)
		return;

	$img = imagecreatetruecolor($size->x, $size->y);

	if(!$filling)
		$filling = 0xE7F1F0;

	imagefill($img, 0, 0, $filling);
//	imageborder($img, $size, null, 2);

	print_png($img);
}

