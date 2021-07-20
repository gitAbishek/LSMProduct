import {
	Collapse,
	FormControl,
	FormErrorMessage,
	FormHelperText,
	FormLabel,
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
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
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

	const tabStyles = {
		justifyContent: 'flex-start',
		w: '180px',
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'transparent',
		marginLeft: 0,
		marginRight: '-2px',
		pl: 0,
		fontSize: 'sm',
		textAlign: 'left',
	};

	const tabListStyles = {
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'gray.200',
	};

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl isInvalid={!!errors?.full_mark}>
								<FormLabel>{__('Full Mark', 'masteriyo')}</FormLabel>
								<Controller
									name="full_mark"
									defaultValue={quizData?.full_mark || 100}
									rules={{
										required: __(
											'Full Mark is required for the quiz',
											'masteriyo'
										),
									}}
									render={({ field }) => (
										<NumberInput {...field} w="full" min={0}>
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
										required: __(
											'Pass mark is required for the quiz',
											'masteriyo'
										),
									}}
									render={({ field }) => (
										<NumberInput {...field} w="full" min={0}>
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
											<NumberInput
												defaultValue={quizData?.duration || 60}
												w="full"
												min={0}>
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
												w="full"
												min={0}>
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
									{errors?.attempts_allowed &&
										errors?.attempts_allowed?.message}
								</FormErrorMessage>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<FormLabel>
									{__('Questions Display Per Page', 'masteriyo')}
								</FormLabel>
								<RadioGroup onChange={setDisplayValue} value={displayValue}>
									<Stack direction="column" spacing="4">
										<Stack direction="row" spacing="6" align="flex-start">
											<Controller
												name="questions_display_per_page"
												render={({ field }) => (
													<>
														<Radio
															{...field}
															value="0"
															onChange={(e: any) =>
																setValue(
																	'questions_display_per_page',
																	e.target.value
																)
															}>
															{__('From Global Settings', 'masteriyo')}
														</Radio>
													</>
												)}
											/>

											<Radio value="1">
												{__('Set Individually', 'masteriyo')}
											</Radio>
										</Stack>
										<Collapse
											in={displayValue != '0' ? true : false}
											animateOpacity>
											<FormControl
												isInvalid={!!errors?.questions_display_per_page}>
												<Controller
													name="questions_display_per_page"
													defaultValue={
														quizData?.questions_display_per_page || 5
													}
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
								</RadioGroup>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default QuizSettings;
