import { Box, Radio, RadioGroup } from '@chakra-ui/react';
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
				{answers.map((answer: SingleChoiceSchema, index: number) => (
					<Box key={index}>
						<Radio value={index.toString()}>{answer.name}</Radio>
					</Box>
				))}
			</RadioGroup>
		</>
	);
};

export default FieldSingleChoice;
