import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n/build-types';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { QuizSchema } from '../../../schemas';

interface Props {
	quizData?: QuizSchema;
}

const QuizSettings: React.FC<Props> = (props) => {
	const { quizData } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext<QuizSchema>();

	return (
		<>
			<FormControl isInvalid={!!errors?.pass_mark}>
				<FormLabel>{__('Quiz Name', 'masteriyo')}</FormLabel>
				<Input
					defaultValue={quizData?.pass_mark}
					placeholder={__('Your Quiz Name', 'masteriyo')}
					{...register('pass_mark', {
						required: __('Please Provide Pass Mark for the quiz', 'masteriyo'),
					})}
				/>
				<FormErrorMessage>
					{errors?.pass_mark && errors?.pass_mark?.message}
				</FormErrorMessage>
			</FormControl>
		</>
	);
};

export default QuizSettings;
