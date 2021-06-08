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
const CompanyInputControl: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<FormControl isInvalid={!!errors?.company} py="3">
			<FormLabel>{__('Company', 'masteriyo')}</FormLabel>
			<Input defaultValue={defaultValue} {...register('billing[company]')} />
			<FormErrorMessage>
				{errors?.company && errors?.company?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default CompanyInputControl;
