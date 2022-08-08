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
							<SimpleGrid spacing="4" columns={[1, 2, 3, 4]}>
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
										<Checkbox value={answer.name} width="full">
											<Text fontSize="sm" wordBreak="break-all">
												{answer.name}
											</Text>
										</Checkbox>
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
