export function updateInlineCSS(styleId: string, css: string) {
	let styleElement = document.getElementById(styleId);

	if (!styleElement) {
		styleElement = document.createElement('style');
		styleElement.setAttribute('type', 'text/css');
		styleElement.setAttribute('id', styleId);

		document.querySelector('head')?.appendChild(styleElement);
	}

	styleElement.innerHTML = css;
}
