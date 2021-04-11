import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

const Name: React.FC = () => {
	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<FormControl isInvalid={!!errors.name}>
			<FormLabel>{__('Course Name', 'masteriyo')}</FormLabel>
			<Input
				placeholder={__('Your Course Name', 'masteriyo')}
				{...register('name', {
					required: __('You must provide name for the course', 'masteriyo'),
				})}
			/>
			<FormErrorMessage>{errors.name && errors.name.message}</FormErrorMessage>
		</FormControl>
	);
};

export default Name;
