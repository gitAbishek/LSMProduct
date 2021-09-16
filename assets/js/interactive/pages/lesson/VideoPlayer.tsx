import { Box } from '@chakra-ui/react';
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
					src={'https://www.youtube.com/embed/' + id}
					allowFullScreen></iframe>
			)}
			{type === 'vimeo' && (
				<iframe
					src={'https://player.vimeo.com/video/' + id}
					width="100%"
					height="500"
					allow="autoplay; fullscreen; picture-in-picture"></iframe>
			)}
			{type === 'self-hosted' && (
				<Box
					sx={{
						position: 'relative',
						overflow: 'hidden',
						width: '100%',
						paddingTop: '56.25%',
					}}>
					<iframe
						style={{
							position: 'absolute',
							top: 0,
							left: 0,
							bottom: 0,
							right: 0,
							width: '100%',
							height: '100%',
						}}
						src={url}
						allowFullScreen></iframe>
				</Box>
			)}
		</>
	);
};

export default VideoPlayer;
