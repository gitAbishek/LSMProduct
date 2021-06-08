import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}
const TransactionID: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<FormControl isInvalid={!!errors?.transaction_id} py="3">
			<FormLabel>{__('Transaction ID', 'masteriyo')}</FormLabel>
			<Input defaultValue={defaultValue} {...register('transaction_id')} />
			<FormErrorMessage>
				{errors?.transaction_id && errors?.transaction_id?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default TransactionID;
