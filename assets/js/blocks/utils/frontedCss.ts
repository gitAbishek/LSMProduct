import { generateFrontEndCSS } from './generateFrontEndCSS';
import { hasMasteriyoBlocks } from './hasMasteriyoBlocks';

export function frontedCSS(): Promise<any> {
	const allBlocks = wp.data.select('core/block-editor').getBlocks();
	const { getCurrentPostId } = wp.data.select('core/editor');
	let css = '';

	if (hasMasteriyoBlocks(allBlocks)) {
		css = generateFrontEndCSS(allBlocks);
	}

	return wp.apiFetch({
		path: '/masteriyo/v1/blocks/save_css',
		method: 'POST',
		data: { css, postId: getCurrentPostId() },
	});
}
