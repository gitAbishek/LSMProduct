import { Flex, Radio, RadioGroup, Stack, Text } from '@chakra-ui/react';
import React, { useState } from 'react';
import { SingleChoiceSchema } from '../../../../back-end/schemas';

interface Props {
	answers: [];
}

const FieldSingleChoice: React.FC<Props> = (props) => {
	const { answers } = props;
	const [value, setValue] = useState<any>(null);

	return (
		<>
			<RadioGroup onChange={(val) => setValue(val)} value={value}>
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
