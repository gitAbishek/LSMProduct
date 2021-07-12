import {
	Box,
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
	Select,
	Spinner,
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
import { useQuery } from 'react-query';
import urls from '../../../constants/urls';
import { CourseDataMap } from '../../../types/course';
import API from '../../../utils/api';

interface Props {
	courseData?: CourseDataMap | any;
}
const CourseSetting: React.FC<Props> = (props) => {
	const { courseData } = props;

	const [displayValue, setDisplayValue] = useState(
		courseData?.enrollment_limit != 0 ? '1' : '0'
	);
	const difficultiesAPI = new API(urls.difficulties);
	const {
		register,
		formState: { errors },
		setValue,
	} = useFormContext();

	const diffultiesQuery = useQuery('difficulties', () =>
		difficultiesAPI.list()
	);

	if (diffultiesQuery.isLoading) {
		return <Spinner />;
	}

	const renderDifficultiesOption = () => {
		try {
			return diffultiesQuery?.data?.map(
				(page: { id: number; name: string }) => (
					<option value={page.id} key={page.id}>
						{page.name}
					</option>
				)
			);
		} catch (error) {
			console.error(error);
			return;
		}
	};

	const tabStyles = {
		justifyContent: 'flex-start',
		w: '150px',
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
											defaultValue={courseData?.difficulty?.id}
											placeholder={__('Choose Course Level', 'masteriyo')}
											{...register('difficulty.id')}>
											{renderDifficultiesOption()}
										</Select>
									</FormControl>

									<FormControl isInvalid={errors?.duration} maxW="xs">
										<FormLabel>{__('Course Duration', 'masteriyo')}</FormLabel>
										<Controller
											name="duration"
											defaultValue={courseData?.duration}
											rules={{
												required: __(
													'Course duration is required.',
													'masteriyo'
												),
											}}
											render={({ field }) => (
												<InputGroup>
													<NumberInput {...field}>
														<NumberInputField />
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

										<FormHelperText>{__('', 'masteriyo')}</FormHelperText>

										<FormErrorMessage>
											{errors?.duration && errors?.duration?.message}
										</FormErrorMessage>
									</FormControl>

									<FormControl>
										<FormLabel>{__('Maximum Students', 'masteriyo')}</FormLabel>
										<RadioGroup onChange={setDisplayValue} value={displayValue}>
											<Stack direction="row" spacing="8" align="flex-start">
												<Stack direction="column">
													<Radio
														onChange={(e: any) =>
															setValue('enrollment_limit', e.target.value)
														}
														value="0">
														{__('No limit', 'masteriyo')}
													</Radio>
													<FormHelperText>
														{__(
															'Check this option if there is no limit.',
															'masteriyo'
														)}
													</FormHelperText>
												</Stack>
												<Stack direction="column" spacing="6">
													<Radio value="1">{__('Limit', 'masteriyo')}</Radio>
													<Collapse in={displayValue != '0'} animateOpacity>
														<FormControl>
															<FormLabel>
																{__('Number of Students', 'masteriyo')}
															</FormLabel>
															<Controller
																name="enrollment_limit"
																render={({ field }) => (
																	<NumberInput
																		{...field}
																		defaultValue={courseData?.enrollment_limit}>
																		<NumberInputField />
																		<NumberInputStepper>
																			<NumberIncrementStepper />
																			<NumberDecrementStepper />
																		</NumberInputStepper>
																	</NumberInput>
																)}
															/>
														</FormControl>
													</Collapse>
												</Stack>
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
										<RadioGroup
											defaultValue={
												courseData?.show_curriculum ? 'true' : 'false'
											}
											onChange={(value) => setValue('show_curriculum', value)}>
											<Stack direction="row" spacing="8">
												<Radio value="true">
													{__('Always Visible', 'masteriyo')}
												</Radio>
												<Radio value="false">
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
