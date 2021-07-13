import {
	Checkbox,
	CheckboxGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { SingleChoiceSchema } from '../../../../back-end/schemas';

interface Props {
	answers: [];
	index: any;
}

const FieldMultipleChoice: React.FC<Props> = (props) => {
	const { answers, index } = props;
	const {
		formState: { errors },
	} = useFormContext();

	return (
		<>
			<FormControl isInvalid={errors[index]}>
				<Controller
					name={index}
					rules={{ required: __('Answer is required', 'masteriyo') }}
					render={({ field }) => (
						<CheckboxGroup {...field}>
							<Stack direction="row" spacing="4">
								{answers.map((answer: SingleChoiceSchema, index: number) => (
									<Flex
										key={index}
										justify="space-between"
										align="center"
										border="1px"
										borderColor="gray.100"
										bg="white"
										rounded="sm"
										py="3"
										px="4"
										minW="200px"
										shadow="input">
										<Text fontSize="sm">{answer.name}</Text>
										<Checkbox value={index.toString()} />
									</Flex>
								))}
							</Stack>
						</CheckboxGroup>
					)}
				/>
				{errors[index] && (
					<FormErrorMessage fontSize="xs">
						{errors[index].message}
					</FormErrorMessage>
				)}
			</FormControl>
		</>
	);
};

export default FieldMultipleChoice;
