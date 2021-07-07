import {
	Box,
	Collapse,
	Flex,
	FormControl,
	FormErrorMessage,
	FormHelperText,
	FormLabel,
	Heading,
	InputGroup,
	InputRightAddon,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Radio,
	RadioGroup,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { QuizSchema } from '../../../schemas';

interface Props {
	quizData?: QuizSchema;
}

const QuizSettings: React.FC<Props> = (props) => {
	const { quizData } = props;
	const {
		formState: { errors },
		setValue,
	} = useFormContext<QuizSchema>();

	const [displayValue, setDisplayValue] = useState(
		quizData?.questions_display_per_page != 0 ? '1' : '0'
	);

	return (
		<Stack direction="column" spacing="6">
			<Box>
				<Stack direction="column" spacing="6">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('General', 'masteriyo')}
						</Heading>
					</Flex>
					<FormControl isInvalid={!!errors?.full_mark}>
						<FormLabel>{__('Full Mark', 'masteriyo')}</FormLabel>
						<Controller
							name="full_mark"
							defaultValue={quizData?.full_mark || 100}
							rules={{
								required: __('Full Mark is required for the quiz', 'masteriyo'),
							}}
							render={({ field }) => (
								<NumberInput {...field} w="full">
									<NumberInputField borderRadius="sm" shadow="input" />
									<NumberInputStepper>
										<NumberIncrementStepper />
										<NumberDecrementStepper />
									</NumberInputStepper>
								</NumberInput>
							)}
						/>
						<FormErrorMessage>
							{errors?.full_mark && errors?.full_mark?.message}
						</FormErrorMessage>
					</FormControl>

					<FormControl isInvalid={!!errors?.pass_mark}>
						<FormLabel>{__('Pass Mark', 'masteriyo')}</FormLabel>
						<Controller
							name="pass_mark"
							defaultValue={quizData?.pass_mark || 40}
							rules={{
								required: __('Pass mark is required for the quiz', 'masteriyo'),
							}}
							render={({ field }) => (
								<NumberInput {...field} w="full">
									<NumberInputField borderRadius="sm" shadow="input" />
									<NumberInputStepper>
										<NumberIncrementStepper />
										<NumberDecrementStepper />
									</NumberInputStepper>
								</NumberInput>
							)}
						/>
						<FormErrorMessage>
							{errors?.pass_mark && errors?.pass_mark?.message}
						</FormErrorMessage>
					</FormControl>

					<FormControl isInvalid={!!errors?.duration}>
						<FormLabel>{__('Duration', 'masteriyo')}</FormLabel>

						<Controller
							name="duration"
							defaultValue={quizData?.duration || 60}
							rules={{
								required: __('Duration is required', 'masteriyo'),
							}}
							render={({ field }) => (
								<InputGroup>
									<NumberInput defaultValue={quizData?.duration || 60} w="full">
										<NumberInputField {...field} rounded="sm" />
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
									<InputRightAddon>
										{__('Minutes', 'masteriyo')}
									</InputRightAddon>
								</InputGroup>
							)}
						/>
						<FormHelperText>
							{__('Duration should be in minutes', 'masteriyo')}
						</FormHelperText>
						<FormErrorMessage>
							{errors?.duration && errors?.duration?.message}
						</FormErrorMessage>
					</FormControl>

					<FormControl isInvalid={!!errors?.attempts_allowed}>
						<FormLabel>{__('Attempts Allowed', 'masteriyo')}</FormLabel>

						<Controller
							name="attempts_allowed"
							defaultValue={quizData?.attempts_allowed || 5}
							rules={{
								required: __('Attempts allowed is required', 'masteriyo'),
							}}
							render={({ field }) => (
								<InputGroup>
									<NumberInput
										defaultValue={quizData?.attempts_allowed || 5}
										w="full">
										<NumberInputField {...field} rounded="sm" />
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
									<InputRightAddon>
										{__('Attempts', 'masteriyo')}
									</InputRightAddon>
								</InputGroup>
							)}
						/>
						<FormErrorMessage>
							{errors?.attempts_allowed && errors?.attempts_allowed?.message}
						</FormErrorMessage>
					</FormControl>
				</Stack>
			</Box>
			<Box>
				<Stack direction="column" spacing="6">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('Display', 'masteriyo')}
						</Heading>
					</Flex>

					<RadioGroup onChange={setDisplayValue} value={displayValue}>
						<Stack direction="row" spacing="6" align="flex-start">
							<Controller
								name="questions_display_per_page"
								render={({ field }) => (
									<>
										<Radio
											{...field}
											value="0"
											onChange={(e: any) =>
												setValue('questions_display_per_page', e.target.value)
											}>
											{__('From Global Settings', 'masteriyo')}
										</Radio>
									</>
								)}
							/>

							<Stack direction="column" spacing="6">
								<Radio value="1">{__('Set Individually', 'masteriyo')}</Radio>

								<Collapse
									in={displayValue != '0' ? true : false}
									animateOpacity>
									<FormControl isInvalid={!!errors?.questions_display_per_page}>
										<FormLabel>
											{__('Question Display Per Page', 'masteriyo')}
										</FormLabel>

										<Controller
											name="questions_display_per_page"
											defaultValue={quizData?.questions_display_per_page || 5}
											rules={{
												required: __(
													'Question display per page is required',
													'masteriyo'
												),
											}}
											render={({ field }) => (
												<InputGroup>
													<NumberInput
														defaultValue={
															quizData?.questions_display_per_page || 5
														}
														w="full">
														<NumberInputField {...field} rounded="sm" />
														<NumberInputStepper>
															<NumberIncrementStepper />
															<NumberDecrementStepper />
														</NumberInputStepper>
													</NumberInput>
												</InputGroup>
											)}
										/>
										<FormErrorMessage>
											{errors?.questions_display_per_page &&
												errors?.questions_display_per_page?.message}
										</FormErrorMessage>
									</FormControl>
								</Collapse>
							</Stack>
						</Stack>
					</RadioGroup>
				</Stack>
			</Box>
		</Stack>
	);
};

export default QuizSettings;
