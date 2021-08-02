import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}
const OverviewTitle: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { register } = useFormContext();

	return (
		<FormControl>
			<FormLabel>{__('Overview Title', 'masteriyo')}</FormLabel>

			<Input
				type="text"
				{...register('overview_title')}
				defaultValue={defaultValue || __('Overview')}
			/>
		</FormControl>
	);
};

export default OverviewTitle;
