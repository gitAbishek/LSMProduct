import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	InputGroup,
	Link,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
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
	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl
						isInvalid={!!errors?.quiz?.styling?.questions_display_per_page}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Questions Per Page', 'masteriyo')}
							</FormLabel>
							<Stack direction="column">
								<InputGroup w="md" size="md">
									<NumberInput w="md" defaultValue={5} min={1}>
										<NumberInputField
											{...register('quiz.styling.questions_display_per_page', {
												required: 'Questions per page is required.',
											})}
										/>
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
								</InputGroup>
								<FormErrorMessage>
									{errors?.quiz?.styling?.questions_display_per_page &&
										errors?.quiz?.styling?.questions_display_per_page.message}
								</FormErrorMessage>
							</Stack>
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
							<Link href={dashboardURL ? dashboardURL : '#'}>
								<Button variant="ghost">
									{__('Skip to Dashboard', 'masteriyo')}
								</Button>
							</Link>
							<Button onClick={nextStep} rounded="3px" colorScheme="blue">
								{__('Next', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Quiz;
