import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Select,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}

const options = [
	{
		label: __('Pending', 'masteriyo'),
		value: 'pending',
	},
	{
		label: __('Processing', 'masteriyo'),
		value: 'processing',
	},
	{
		label: __('On Hold', 'masteriyo'),
		value: 'on-hold',
	},
	{
		label: __('Completed', 'masteriyo'),
		value: 'completed',
	},
	{
		label: __('Cancelled', 'masteriyo'),
		value: 'cancelled',
	},
	{
		label: __('Refunded', 'masteriyo'),
		value: 'refunded',
	},
	{
		label: __('Failed', 'masteriyo'),
		value: 'failed',
	},
];

const OrderStatus: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<FormControl isInvalid={errors?.status} py="3">
			<FormLabel>{__('Status', 'masteriyo')}</FormLabel>
			<Select
				defaultValue={defaultValue}
				{...register('status', {
					required: __('Please select a status', 'masteriyo'),
				})}>
				{options.map((option) => (
					<option key={option.value} value={option.value}>{option.label}</option>
				))}
			</Select>

			<FormErrorMessage>
				{errors?.status && errors?.status?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default OrderStatus;
