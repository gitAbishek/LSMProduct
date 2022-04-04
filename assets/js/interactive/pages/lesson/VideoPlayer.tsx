import { Box } from '@chakra-ui/react';
import React from 'react';
import ReactPlayer from 'react-player';
interface Props {
	type: 'self-hosted' | 'youtube' | 'vimeo';
	url?: string;
}
const VideoPlayer: React.FC<Props> = (props) => {
	const { type, url } = props;

	return (
		<Box
			onContextMenu={
				type === 'self-hosted' ? (e) => e.preventDefault() : undefined
			}
			style={{
				position: 'relative',
				paddingTop: '56.25%',
			}}>
			<ReactPlayer
				style={{
					position: 'absolute',
					top: 0,
					left: 0,
					bottom: 0,
					right: 0,
					marginLeft: 'auto',
					marginRight: 'auto',
				}}
				url={url}
				controls
				height="100%"
				width="100%"
				allow="autoplay; fullscreen; picture-in-picture"
				config={{
					file: {
						attributes: {
							controlsList: 'nodownload',
						},
					},
				}}
			/>
		</Box>
	);
};

export default VideoPlayer;
