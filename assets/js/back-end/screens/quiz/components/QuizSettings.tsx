import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { QuizSchema } from '../../../schemas';

interface Props {
	quizData?: QuizSchema;
}

const QuizSettings: React.FC<Props> = (props) => {
	const { quizData } = props;
	const {
		formState: { errors },
	} = useFormContext<QuizSchema>();

	return (
		<Stack direction="column" spacing="6">
			<FormControl isInvalid={!!errors?.full_mark}>
				<FormLabel>{__('Full Mark', 'masteriyo')}</FormLabel>
				<Controller
					name="full_mark"
					defaultValue={quizData?.full_mark || 100}
					rules={{
						required: __('Full Mark is required for the quiz', 'masteriyo'),
					}}
					render={({ field }) => (
						<NumberInput {...field} w="full">
							<NumberInputField borderRadius="sm" shadow="input" />
							<NumberInputStepper>
								<NumberIncrementStepper />
								<NumberDecrementStepper />
							</NumberInputStepper>
						</NumberInput>
					)}
				/>
				<FormErrorMessage>
					{errors?.full_mark && errors?.full_mark?.message}
				</FormErrorMessage>
			</FormControl>

			<FormControl isInvalid={!!errors?.pass_mark}>
				<FormLabel>{__('Pass Mark', 'masteriyo')}</FormLabel>
				<Controller
					name="pass_mark"
					defaultValue={quizData?.pass_mark || 100}
					rules={{
						required: __('Pass mark is required for the quiz', 'masteriyo'),
					}}
					render={({ field }) => (
						<NumberInput {...field} w="full">
							<NumberInputField borderRadius="sm" shadow="input" />
							<NumberInputStepper>
								<NumberIncrementStepper />
								<NumberDecrementStepper />
							</NumberInputStepper>
						</NumberInput>
					)}
				/>
				<FormErrorMessage>
					{errors?.pass_mark && errors?.pass_mark?.message}
				</FormErrorMessage>
			</FormControl>
		</Stack>
	);
};

export default QuizSettings;
