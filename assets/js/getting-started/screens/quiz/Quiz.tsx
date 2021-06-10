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
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
}

const Quiz: React.FC<Props> = (props) => {
	const { dashboardURL, prevStep, nextStep } = props;
	const { register } = useFormContext();
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Time Limit', 'masteriyo')}
							</FormLabel>
							<InputGroup w="md" size="md">
								<Input defaultValue="60" {...register('quizzes.time_limit')} />
								<InputRightAddon>{__('Minutes', 'masteriyo')}</InputRightAddon>
							</InputGroup>
						</Flex>
					</FormControl>

					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Attempts Allowed', 'masteriyo')}
							</FormLabel>
							<InputGroup w="md" size="md">
								<Input
									defaultValue={5}
									{...register('quizzes.attempts_allowed')}
								/>
								<InputRightAddon>{__('Attempts', 'masteriyo')}</InputRightAddon>
							</InputGroup>
						</Flex>
					</FormControl>

					<Flex justify="space-between" align="center">
						<Button
							onClick={prevStep}
							rounded="3px"
							colorScheme="blue"
							variant="outline">
							{__('Back', 'masteriyo')}
						</Button>
						<ButtonGroup>
							<Button variant="ghost">
								<Link href={dashboardURL ? dashboardURL : '#'}>
									{__('Skip to Dashboard', 'masteriyo')}
								</Link>
							</Button>
							<Button onClick={nextStep} rounded="3px" colorScheme="blue">
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
