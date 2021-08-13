import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
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

const Course: React.FC<Props> = (props) => {
	const { dashboardURL, prevStep, nextStep } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl isInvalid={!!errors?.course_archive?.display?.per_row}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Course Per Row', 'masteriyo')}
							</FormLabel>
							<Stack direction="column">
								<NumberInput w="md" defaultValue={4} min={1} max={4}>
									<NumberInputField
										{...register('course_archive.display.per_row', {
											required: 'Course per row is required.',
										})}
									/>
									<NumberInputStepper>
										<NumberIncrementStepper />
										<NumberDecrementStepper />
									</NumberInputStepper>
								</NumberInput>
								<FormErrorMessage>
									{errors?.course_archive?.display?.per_row &&
										errors?.course_archive?.display?.per_row.message}
								</FormErrorMessage>
							</Stack>
						</Flex>
					</FormControl>

					<FormControl isInvalid={!!errors?.course_archive?.display?.per_page}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Course Per Page', 'masteriyo')}
							</FormLabel>
							<Stack direction="column">
								<NumberInput w="md" defaultValue={12} min={1}>
									<NumberInputField
										{...register('course_archive.display.per_page', {
											required: 'Course per page is required.',
										})}
									/>
									<NumberInputStepper>
										<NumberIncrementStepper />
										<NumberDecrementStepper />
									</NumberInputStepper>
								</NumberInput>
								<FormErrorMessage>
									{errors?.course_archive?.display?.per_page &&
										errors?.course_archive?.display?.per_page.message}
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

export default Course;
