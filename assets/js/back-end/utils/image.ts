import { MediaSchema } from '../schemas';

export const getSrcSet = (mediaObject: MediaSchema) => {
	const mediaDetails = mediaObject.media_details.sizes;
	const mainData: string[] = [];

	const mediaDetailsArr = Object.entries(mediaDetails);
	const filteredArr = mediaDetailsArr.filter(function ([key, value]) {
		return !key.includes('masteriyo_');
	});

	filteredArr.length > 0 &&
		filteredArr.map((sizes) => {
			sizes.map((size: any) => {
				size.source_url
					? mainData.push(size.source_url + ' ' + size.width + 'w')
					: null;
			});
		});

	const srcSet = mainData.join(',');

	return srcSet;
};
