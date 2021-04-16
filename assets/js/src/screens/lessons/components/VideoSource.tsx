import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Select from 'Components/common/Select';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: any;
}
const VideoSource: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { control, register } = useFormContext();

	const options = [
		{ value: 'youtube', label: 'YouTube' },
		{ value: 'vimeo', label: 'Vimeo' },
	];

	return (
		<FormControl>
			<FormLabel>{__('Video Source', 'masteriyo')}</FormLabel>
			<Controller
				render={({ field }) => (
					<Select
						{...field}
						closeMenuOnSelect={false}
						defaultValue={defaultValue}
						isMulti
						options={options}
					/>
				)}
				control={control}
				name="categories"
			/>
		</FormControl>
	);
};

export default VideoSource;
