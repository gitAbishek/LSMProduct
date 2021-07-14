import { FormControl, FormLabel, Input, Select, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultSource?: any;
	defaultSourceUrl?: any;
}
const VideoSource: React.FC<Props> = (props) => {
	const { defaultSource, defaultSourceUrl } = props;
	const { register } = useFormContext();

	return (
		<Stack direction="row" spacing="6">
			<FormControl>
				<FormLabel>{__('Video Source', 'masteriyo')}</FormLabel>
				<Select {...register('video_source')} defaultValue={defaultSource}>
					<option value="self-hosted">{__('Self Hosted', 'materiyo')}</option>
					<option value="youtube">{__('YouTube', 'materiyo')}</option>
					<option value="vimeo">{__('Vimeo', 'materiyo')}</option>
				</Select>
			</FormControl>

			<FormControl>
				<FormLabel>{__('Video Source URL', 'masteriyo')}</FormLabel>
				<Input
					type="text"
					defaultValue={defaultSourceUrl}
					{...register('video_source_url')}
				/>
			</FormControl>
		</Stack>
	);
};

export default VideoSource;
