import {
	Flex,
	FormControl,
	FormLabel,
	Radio,
	RadioGroup,
	Stack,
} from '@chakra-ui/react';
import React from 'react';

const RecordingSetting = () => {
	return (
		<FormControl>
			<Flex direction="column">
				<FormLabel>Recording Settings</FormLabel>
				<RadioGroup defaultValue="1">
					<Stack direction={['column', 'row']}>
						<Radio value="1">No Recording</Radio>
						<Radio value="2">Local</Radio>
						<Radio value="3">Zoom Cloud</Radio>
					</Stack>
				</RadioGroup>
			</Flex>
		</FormControl>
	);
};

export default RecordingSetting;
