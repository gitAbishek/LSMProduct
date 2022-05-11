import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Textarea,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

const Content: React.FC = () => {
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<FormControl isInvalid={!!errors?.title}>
			<FormLabel>{__('Review Content', 'masteriyo')}</FormLabel>
			<Textarea
				placeholder={__('Your Content', 'masteriyo')}
				{...register('content', {
					required: __(
						'You must provide a content for the review.',
						'masteriyo'
					),
				})}
			/>
			<FormErrorMessage>
				{errors?.content && errors?.content?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default Content;
