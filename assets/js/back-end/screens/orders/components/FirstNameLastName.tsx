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
	defaultFirstName?: string;
	defaultLastName?: string;
}
const FirstNameLastName: React.FC<Props> = (props) => {
	const { defaultFirstName, defaultLastName } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<Stack direction="row" spacing="8" py="3">
			<FormControl isInvalid={!!errors?.first_name}>
				<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
				<Input
					defaultValue={defaultFirstName}
					{...register('billing[first_name]')}
				/>
				<FormErrorMessage>
					{errors?.first_name && errors?.first_name?.message}
				</FormErrorMessage>
			</FormControl>
			<FormControl isInvalid={!!errors?.last_name}>
				<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
				<Input
					defaultValue={defaultLastName}
					{...register('billing[last_name]')}
				/>
				<FormErrorMessage>
					{errors?.last_name && errors?.last_name?.message}
				</FormErrorMessage>
			</FormControl>
		</Stack>
	);
};

export default FirstNameLastName;
