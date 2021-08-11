import {
	Flex,
	FormControl,
	FormErrorMessage,
	Radio,
	RadioGroup,
	Stack,
	Text,
} from '@chakra-ui/react';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { SingleChoiceSchema } from '../../../../back-end/schemas';

interface Props {
	answers: [];
	questionId: any;
}

const FieldTrueFalse: React.FC<Props> = (props) => {
	const { answers, questionId } = props;
	const {
		formState: { errors },
	} = useFormContext();

	return (
		<>
			<FormControl isInvalid={errors[questionId]}>
				<Controller
					name={questionId}
					render={({ field }) => (
						<RadioGroup {...field}>
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
										<Radio value={answer.name}></Radio>
									</Flex>
								))}
							</Stack>
						</RadioGroup>
					)}
				/>
				{errors[questionId] && (
					<FormErrorMessage fontSize="xs">
						{errors[questionId].message}
					</FormErrorMessage>
				)}
			</FormControl>
		</>
	);
};

export default FieldTrueFalse;
