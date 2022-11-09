import { colord, extend } from 'colord';
import mixPlugin from 'colord/plugins/mix';

/**
 * Generates pallete of tint and shade of color
 * @param color string
 * @returns object
 */
export const generatePallete = (color: string) => {
	extend([mixPlugin]);
	const tintedColor = colord(color)
		.tints(7)
		.map((c) => c.toHex());
	const shadedColor = colord(color)
		.shades(7)
		.map((c) => c.toHex());

	return {
		50: tintedColor[5],
		100: tintedColor[4],
		200: tintedColor[3],
		300: tintedColor[2],
		400: tintedColor[1],
		500: color,
		600: shadedColor[1],
		700: shadedColor[2],
		800: shadedColor[3],
		900: shadedColor[4],
	};
};
