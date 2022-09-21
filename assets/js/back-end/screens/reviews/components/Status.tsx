import { FormControl, FormLabel, Select } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

const reviewStatusList = [
	{
		label: __('Approve', 'masteriyo'),
		value: 'approve',
	},
	{
		label: __('On Hold', 'masteriyo'),
		value: 'hold',
	},
	{
		label: __('Spam', 'masteriyo'),
		value: 'spam',
	},
	{
		label: __('Trash', 'masteriyo'),
		value: 'trash',
	},
];

const Status: React.FC = () => {
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<FormControl
			sx={{
				'.css-2b097c-container': {
					width: '100%',
				},
			}}
			display="flex"
			flexDirection={['column', 'column', 'column', 'row']}
			alignItems={{ lg: 'center' }}
			isInvalid={!!errors?.status}
			py="3">
			<FormLabel>{__('Status', 'masteriyo')}</FormLabel>
			<Select {...register('status')}>
				{reviewStatusList.map((option: any) => (
					<option key={option.value} value={option.value}>
						{option.label}
					</option>
				))}
			</Select>
		</FormControl>
	);
};

export default Status;
