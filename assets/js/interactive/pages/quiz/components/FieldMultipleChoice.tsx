import {
	Checkbox,
	CheckboxGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	SimpleGrid,
	Text,
} from '@chakra-ui/react';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { SingleChoiceSchema } from '../../../../back-end/schemas';

interface Props {
	answers: [];
	questionId: any;
}

const FieldMultipleChoice: React.FC<Props> = (props) => {
	const { answers, questionId } = props;
	const {
		formState: { errors },
	} = useFormContext();

	return (
		<>
			<FormControl isInvalid={errors[questionId]}>
				<Controller
					name={questionId}
					render={({ field: { onChange, value } }) => (
						<CheckboxGroup onChange={onChange} value={value}>
							<SimpleGrid spacing="4" columns={4}>
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
										shadow="input">
										<Text fontSize="sm">{answer.name}</Text>
										<Checkbox value={answer.name} />
									</Flex>
								))}
							</SimpleGrid>
						</CheckboxGroup>
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

export default FieldMultipleChoice;
