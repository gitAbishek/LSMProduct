import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	Input,
	InputGroup,
	InputRightAddon,
	Select,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

interface Props {
	setTabIndex?: any;
}

const Quiz: React.FC<Props> = (props) => {
	const { setTabIndex } = props;
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Time Limit', 'masteriyo')}</Text>
						</strong>
						<InputGroup w="md" size="md">
							<Input defaultValue="60" />
							<InputRightAddon children={`minutes`} />
						</InputGroup>
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Attempts Allowed', 'masteriyo')}</Text>
						</strong>
						<Select w="md" placeholder="5 attempts" />
					</Flex>

					<Flex justify="space-between" align="center">
						<Button
							onClick={() => setTabIndex(2)}
							rounded="3px"
							colorScheme="blue"
							variant="outline">
							{__('Back', 'masteriyo')}
						</Button>
						<ButtonGroup>
							<Button onClick={() => setTabIndex(4)} variant="ghost">
								{__('Skip', 'masteriyo')}
							</Button>
							<Button
								onClick={() => setTabIndex(4)}
								rounded="3px"
								colorScheme="blue">
								{__('Continue', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Quiz;
