import {
	Box,
	Collapse,
	FormControl,
	FormErrorMessage,
	FormHelperText,
	FormLabel,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Radio,
	RadioGroup,
	Select,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { CourseDataMap } from '../../../types/course';

interface Props {
	courseData?: CourseDataMap | any;
}
const CourseSetting: React.FC<Props> = (props) => {
	const { courseData } = props;

	const [displayValue, setDisplayValue] = useState('1');

	const {
		register,
		formState: { errors },
	} = useFormContext();

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
		<Box bg="white" p="10" shadow="box">
			<form>
				<Tabs orientation="vertical">
					<Stack direction="row" flex="1">
						<TabList sx={tabListStyles}>
							<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
						</TabList>
						<TabPanels flex="1">
							<TabPanel>
								<Stack direction="column" spacing="8">
									<FormControl>
										<FormLabel>{__('Difficulty', 'masteriyo')}</FormLabel>
										<Select
											defaultValue={courseData?.difficulty}
											placeholder={__('Choose Course Level', 'masteriyo')}
											{...register('difficulty')}>
											<option value="beginner">
												{__('Beginner', 'masteriyo')}
											</option>
											<option value="intermediate">
												{__('Intermediate', 'masteriyo')}
											</option>
											<option value="advanced">
												{__('Advanced', 'masteriyo')}
											</option>
										</Select>

										<FormErrorMessage>
											{errors?.difficulty && errors?.difficulty?.message}
										</FormErrorMessage>
									</FormControl>

									<FormControl>
										<FormLabel>{__('Maximum Students', 'masteriyo')}</FormLabel>
										<RadioGroup
											onChange={setDisplayValue}
											value={displayValue}
											defaultValue="0">
											<Stack direction="column" spacing="6">
												<Stack direction="row" spacing="8" align="flex-start">
													<Stack direction="column">
														<Radio value="0">
															{__('No limit', 'masteriyo')}
														</Radio>
														<FormHelperText>
															{__(
																'Check this option if there is no limit.',
																'masteriyo'
															)}
														</FormHelperText>
													</Stack>
													<Radio value="1">{__('Limit', 'masteriyo')}</Radio>
												</Stack>
												<Collapse in={displayValue != '0'} animateOpacity>
													<FormControl>
														<FormLabel>
															{__('Number of Students', 'masteriyo')}
														</FormLabel>
														<NumberInput defaultValue={50}>
															<NumberInputField />
															<NumberInputStepper>
																<NumberIncrementStepper />
																<NumberDecrementStepper />
															</NumberInputStepper>
														</NumberInput>
													</FormControl>
												</Collapse>
											</Stack>
										</RadioGroup>
									</FormControl>
								</Stack>
							</TabPanel>

							<TabPanel>
								<Stack direction="column" spacing="8">
									<FormControl>
										<FormLabel>
											{__('Curriculum in Single page', 'masteriyo')}
										</FormLabel>
										<RadioGroup defaultValue="1">
											<Stack direction="row" spacing="8">
												<Radio value="1">
													{__('Always Visible', 'masteriyo')}
												</Radio>
												<Radio value="2">
													{__('Only Visible to Enrollers', 'masteriyo')}
												</Radio>
											</Stack>
										</RadioGroup>
									</FormControl>
								</Stack>
							</TabPanel>
						</TabPanels>
					</Stack>
				</Tabs>
			</form>
		</Box>
	);
};

export default CourseSetting;
