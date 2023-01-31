import {
	Flex,
	FormControl,
	FormLabel,
	InputGroup,
	InputRightAddon,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
} from '@chakra-ui/react';
import React from 'react';

const TimeDuration = () => {
	return (
		<FormControl>
			<FormLabel>Time Duration</FormLabel>
			<Flex gap="4" direction="column">
				<InputGroup display="flex" flexDirection="row">
					<NumberInput>
						<NumberInputField rounded="sm" />
						<NumberInputStepper>
							<NumberIncrementStepper />
							<NumberDecrementStepper />
						</NumberInputStepper>
					</NumberInput>
					<InputRightAddon w="40%">Hours</InputRightAddon>
				</InputGroup>

				<InputGroup display="flex" flexDirection="row">
					<NumberInput>
						<NumberInputField rounded="sm" />
						<NumberInputStepper>
							<NumberIncrementStepper />
							<NumberDecrementStepper />
						</NumberInputStepper>
					</NumberInput>
					<InputRightAddon w="40%">Minutes</InputRightAddon>
				</InputGroup>
			</Flex>
		</FormControl>
	);
};

export default TimeDuration;
