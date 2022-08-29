/**
 * Returns the shaded color by percentage.
 *
 * @param {String} color color is hex string
 * @param {Number} percent color is being shaded by percent
 *
 * @returns {String} a string representation of `color`
 */
export function shadedColor(color: string, percent: number = 10) {
	const { r, g, b, a } = hexToRgba(color);
	let rr = parseInt(String((Number(r) * (100 + percent)) / 100));
	let gg = parseInt(String((Number(g) * (100 + percent)) / 100));
	let bb = parseInt(String((Number(b) * (100 + percent)) / 100));

	rr = rr < 255 ? rr : 255;
	gg = gg < 255 ? gg : 255;
	bb = bb < 255 ? bb : 255;

	const finalColor =
		'#' +
		(rr.toString(16).length == 1 ? '0' + rr.toString(16) : rr.toString(16)) +
		(gg.toString(16).length == 1 ? '0' + gg.toString(16) : gg.toString(16)) +
		(bb.toString(16).length == 1 ? '0' + bb.toString(16) : bb.toString(16));

	if (a === 1) {
		return finalColor;
	} else {
		return finalColor + a;
	}
}

/**
 * Convert Hex color to rgba color
 *
 * @param {String} hexColor Hex color string
 *
 * @returns {object}
 */

export function hexToRgba(hexColor: string) {
	const r = String(parseInt(hexColor.substring(1, 3), 16));
	const g = String(parseInt(hexColor.substring(3, 5), 16));
	const b = String(parseInt(hexColor.substring(5, 7), 16));
	const a = hexColor.substring(7, 9);
	if (!a) {
		// IF a not available in hex a is 1 in rgba
		return { r, g, b, a: 1 };
	} else {
		return { r, g, b, a };
	}
}
