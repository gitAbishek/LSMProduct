import {
	Box,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n/build-types';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	quizData;
}

const QuizSettings = () => {
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<>
			<FormControl isInvalid={!!errors?.name}>
				<FormLabel>{__('Quiz Name', 'masteriyo')}</FormLabel>
				<Input
					defaultValue={defaultValue}
					placeholder={__('Your Quiz Name', 'masteriyo')}
					{...register('name', {
						required: __('You must provide name for the quiz', 'masteriyo'),
					})}
				/>
				<FormErrorMessage>
					{errors?.name && errors?.name?.message}
				</FormErrorMessage>
			</FormControl>
		</>
	);
};

export default QuizSettings;
