import { FormErrorMessage, FormLabel, Input } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import FormControlTwoCol from '../../../components/common/FormControlTwoCol';

const Title: React.FC = () => {
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<FormControlTwoCol isInvalid={!!errors?.title}>
			<FormLabel>{__('Review Title', 'masteriyo')}</FormLabel>
			<Input
				placeholder={__('Your Review Title', 'masteriyo')}
				{...register('title', {
					required: __('You must provide a title for the review.', 'masteriyo'),
				})}
			/>
			<FormErrorMessage>
				{errors?.title && errors?.title?.message}
			</FormErrorMessage>
		</FormControlTwoCol>
	);
};

export default Title;
