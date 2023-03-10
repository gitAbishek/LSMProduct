import { MediaSchema } from '../schemas';
import { isEmpty } from './utils';

export const getSrcSet = (mediaObject: MediaSchema) => {
	if (isEmpty(mediaObject)) return;

	const mediaDetails = mediaObject.media_details.sizes;
	let imageUrls: string[] = [];

	if (mediaDetails.thumbnail)
		imageUrls.push(
			`${mediaDetails.thumbnail.source_url} ${mediaDetails.thumbnail.width}w`
		);

	if (mediaDetails.medium)
		imageUrls.push(
			`${mediaDetails.medium.source_url} ${mediaDetails.medium.width}w`
		);

	if (mediaDetails.large)
		imageUrls.push(
			`${mediaDetails.large.source_url} ${mediaDetails.large.width}w`
		);

	return !isEmpty(imageUrls) ? imageUrls.join(', ') : '';
};
