import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: any;
}
const VideoSourceUrl: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { register } = useFormContext();
	return (
		<FormControl>
			<FormLabel>{__('Video Source URL', 'masteriyo')}</FormLabel>
			<Input
				type="text"
				defaultValue={defaultValue}
				{...register('video_source_url')}
			/>
		</FormControl>
	);
};

export default VideoSourceUrl;
