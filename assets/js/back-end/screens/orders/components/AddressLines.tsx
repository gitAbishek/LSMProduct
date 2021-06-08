import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultAddress1?: string;
	defaultAddress2?: string;
}
const AddressLines: React.FC<Props> = (props) => {
	const { defaultAddress1, defaultAddress2 } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<Stack direction="row" spacing="8" py="3">
			<FormControl isInvalid={!!errors?.address_1}>
				<FormLabel>{__('Address 1', 'masteriyo')}</FormLabel>
				<Input
					defaultValue={defaultAddress1}
					{...register('billing[address_1]')}
				/>
				<FormErrorMessage>
					{errors?.address_1 && errors?.address_1?.message}
				</FormErrorMessage>
			</FormControl>
			<FormControl isInvalid={!!errors?.address_2}>
				<FormLabel>{__('Address 2', 'masteriyo')}</FormLabel>
				<Input
					defaultValue={defaultAddress2}
					{...register('billing[address_2]')}
				/>
				<FormErrorMessage>
					{errors?.address_2 && errors?.address_2?.message}
				</FormErrorMessage>
			</FormControl>
		</Stack>
	);
};

export default AddressLines;
