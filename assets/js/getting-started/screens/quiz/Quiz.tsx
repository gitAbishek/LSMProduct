import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Input,
	InputGroup,
	InputRightAddon,
	Link,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
interface Props {
	setTabIndex?: any;
	dashboardURL: string;
}

const Quiz: React.FC<Props> = (props) => {
	const { setTabIndex, dashboardURL } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl id="quiz-time-limit">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Time Limit', 'masteriyo')}
							</FormLabel>
							<InputGroup w="md" size="md">
								<Input defaultValue="60" {...register('time_limit')} />
								<InputRightAddon children={`Minutes`} />
							</InputGroup>
						</Flex>
					</FormControl>

					<FormControl id="quiz-attempts-allowed">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Attempts Allowed', 'masteriyo')}
							</FormLabel>
							<InputGroup w="md" size="md">
								<Input defaultValue="5" {...register('attempts_allowed')} />
								<InputRightAddon children={`Attempts`} />
							</InputGroup>
						</Flex>
					</FormControl>

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
								<Link href={dashboardURL ? dashboardURL : '#'}>
									{__('Skip to Dashboard', 'masteriyo')}
								</Link>
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
