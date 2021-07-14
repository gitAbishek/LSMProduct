import getVideoId from 'get-video-id';
import React from 'react';

interface Props {
	type: 'self-hosted' | 'youtube' | 'vimeo';
	url?: string;
}
const VideoPlayer: React.FC<Props> = (props) => {
	const { type, url } = props;
	const { id } = getVideoId(url || '');
	return (
		<>
			{type === 'youtube' && (
				<iframe
					id="ytplayer"
					width="100%"
					height="500"
					src={'https://www.youtube.com/embed/' + id}></iframe>
			)}
			{type === 'vimeo' && (
				<iframe
					src={'https://player.vimeo.com/video/' + id}
					width="100%"
					height="500"
					allow="autoplay; fullscreen; picture-in-picture"></iframe>
			)}
		</>
	);
};

export default VideoPlayer;
