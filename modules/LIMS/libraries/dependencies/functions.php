<?php

/**
 * Escape a PHP string for use as (part of) an HTML / XML text node.
 *
 * It only escapes a few special chars: the ampersand (&) and both the left-
 * pointing angle bracket (<) and the right-pointing angle bracket (>), since
 * these are the only characters that are special in a text node.  Minimal
 * quoting is preferred because it produces smaller and more easily human-
 * readable output.
 *
 * Some characters simply cannot appear in valid XML documents, even
 * as entities but, this function does not attempt to handle them.
 *
 * NOTE: Attribute values are NOT text nodes, and require additional escaping.
 *
 * @param string $text The string to escape, possibly including "&", "<",
 *                     or ">".
 * @return string The string, with "&", "<", and ">" escaped.
 */
function text($text) {
	return htmlspecialchars($text, ENT_NOQUOTES);
}

/**
 * valueOrNull
 * Checks whether a particular value exists and returns it.
 * If the value doesn't exist, returns null.
 *
 * @param string $value
 * @return string 
 */
function valueOrNull($value) {
	if (is_array($value)) {
		if (count($value) > 0) {
			return $value;
		}
	} else {
		if (isset($value) && strlen($value) > 0) {
			return $value;
		}
	}
	return null;
}
?>