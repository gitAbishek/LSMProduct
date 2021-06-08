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
		label: __('Paypal', 'masteriyo'),
		value: 'paypal',
	},
];

const PaymentMethod: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<FormControl isInvalid={errors?.payment_method} py="3">
			<FormLabel>{__('Payment method', 'masteriyo')}</FormLabel>
			<Select
				placeholder={__('Select a payment method', 'masteriyo')}
				defaultValue={defaultValue}
				{...register('payment_method', {
					required: __('Please select a payment method', 'masteriyo'),
				})}>
				{options.map((option) => (
					<option key={option.value} value={option.value}>
						{option.label}
					</option>
				))}
			</Select>

			<FormErrorMessage>
				{errors?.payment_method && errors?.payment_method?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default PaymentMethod;
