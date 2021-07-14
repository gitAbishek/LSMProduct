import React from 'react';
import { getYoutubeId } from '../../../back-end/utils/helper';

interface Props {
	type: 'self-hosted' | 'youtube' | 'vimeo';
	url?: string;
}
const VideoPlayer: React.FC<Props> = (props) => {
	const { type, url } = props;
	return (
		<>
			{type === 'youtube' && (
				<iframe
					id="ytplayer"
					width="100%"
					height="500"
					src={
						'https://www.youtube.com/embed/' + getYoutubeId(url || '')
					}></iframe>
			)}
		</>
	);
};

export default VideoPlayer;
