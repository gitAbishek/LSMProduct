import { Flex, Input, Radio, RadioGroup, Stack, Text } from '@chakra-ui/react';
import React, { useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { useParams } from 'react-router-dom';
import { SingleChoiceSchema } from '../../../../back-end/schemas';

interface Props {
	answers: [];
}

const FieldSingleChoice: React.FC<Props> = (props) => {
	const { answers } = props;
	const { quizId }: any = useParams();
	const [answer, setAnswer] = useState<any>(null);
	const { register, setValue } = useFormContext();

	return (
		<>
			<Input type="hidden" {...register('question.answer')} />
			<RadioGroup
				onChange={(val) => {
					setAnswer(val);
					setValue('question.answer', val);
				}}
				value={answer}>
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
							<Radio value={index.toString()}></Radio>
						</Flex>
					))}
				</Stack>
			</RadioGroup>
		</>
	);
};

export default FieldSingleChoice;
