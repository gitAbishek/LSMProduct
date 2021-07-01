import {
	Box,
	FormControl,
	Icon,
	Input,
	InputGroup,
	InputRightElement,
	Stack,
} from '@chakra-ui/react';
import React from 'react';
import { BiSearch } from 'react-icons/bi';

const QuestionList = () => {
	return (
		<>
			<Stack direction="column" spacing="4">
				<Box as="form" action="" p="4">
					<FormControl>
						<InputGroup>
							<Input placeholder="Search a Question" />
							<InputRightElement>
								<Icon as={BiSearch} />
							</InputRightElement>
						</InputGroup>
					</FormControl>
				</Box>
			</Stack>
		</>
	);
};

export default QuestionList;
