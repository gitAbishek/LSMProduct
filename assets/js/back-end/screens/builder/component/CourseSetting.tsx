import {
	Box,
	Center,
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
	Text,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import urls from '../../../constants/urls';
import { CourseDataMap } from '../../../types/course';
import API from '../../../utils/api';
import localized from '../../../utils/global';
import { convertMinutesToHours } from '../../../utils/math';
import { decodeEntity } from '../../../utils/utils';
import ChangeInstructorSetting from './ChangeInstructorSetting';

interface Props {
	courseData?: CourseDataMap | any;
}

const CourseSetting: React.FC<Props> = (props) => {
	const { courseData } = props;

	const [enrollDisplayValue, setEnrollDisplayValue] = useState(
		courseData?.enrollment_limit != 0 ? '1' : '0'
	);

	const [pricingDisplayValue, setPricingDisplayValue] = useState(
		courseData?.price_type || 'free'
	);

	const [isLargerThan768] = useMediaQuery('(min-width: 768px)');

	const [accessModeType, setAccessModeType] = useState(
		courseData?.access_mode || 'open'
	);

	const [hours, minutes] = convertMinutesToHours(courseData?.duration || 0);

	const difficultiesAPI = new API(urls.difficulties);
	const {
		register,
		formState: { errors },
		setValue,
	} = useFormContext();

	const diffultiesQuery = useQuery('difficulties', () =>
		difficultiesAPI.list()
	);

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
			return;
		}
	};

	const tabStyles = {
		justifyContent: 'flex-start',
		w: ['60px', '60px', '150px', '150px'],
		borderLeft: 0,
		borderRight: ['none', 'none', '2px solid #E2E8F0'],
		borderRightColor: 'transparent',
		marginLeft: 0,
		marginRight: '-2px',
		pl: 0,
		fontSize: 'sm',
		textAlign: 'left',
	};

	const tabListStyles = {
		borderLeft: 0,
		borderRight: ['none', 'none', '2px solid #E2E8F0'],
		justifyContent: ['center', 'center', 'left', 'left'],
		gap: ['3', '3', 0, 0],
	};

	const currencyCode = localized.currency.code;
	const currencySymbol = localized.currency.symbol;

	const onChangePriceType = (priceType: string) => {
		setPricingDisplayValue(priceType);
		setValue('price_type', priceType);
	};

	let tabOrientation: 'vertical' | 'horizontal' = 'vertical';
	if (isLargerThan768) {
		tabOrientation = 'vertical';
	} else {
		tabOrientation = 'horizontal';
	}

	return (
		<Box bg="white" p="10" shadow="box">
			<form>
				<Tabs orientation={tabOrientation}>
					<Stack direction={['column', 'column', 'row', 'row']}>
						<TabList sx={tabListStyles}>
							<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Pricing', 'masteriyo')}</Tab>
						</TabList>
						<TabPanels flex="1">
							<TabPanel>
								{diffultiesQuery?.isLoading ? (
									<Center>
										<Spinner />
									</Center>
								) : (
									<Stack direction="column" spacing="8">
										<ChangeInstructorSetting courseData={courseData} />

										<FormControl>
											<FormLabel>{__('Difficulty', 'masteriyo')}</FormLabel>
											<Select
												placeholder={__('Choose Course Level', 'masteriyo')}
												defaultValue={courseData?.difficulty?.id}
												{...register('difficulty.id')}>
												{renderDifficultiesOption()}
											</Select>
										</FormControl>
										<Stack direction="column" spacing="0">
											<FormLabel>{__('Duration', 'masteriyo')}</FormLabel>
											<Stack direction={['column', 'column', 'row']}>
												<FormControl isInvalid={!!errors?.duration_hour}>
													<Controller
														name="duration_hour"
														defaultValue={hours || 0}
														rules={{
															required: __('Hours is required.', 'masteriyo'),
															min: 0,
														}}
														render={({ field }) => (
															<InputGroup display="flex" flexDirection="row">
																<NumberInput {...field} w="100%">
																	<NumberInputField rounded="sm" />
																	<NumberInputStepper>
																		<NumberIncrementStepper />
																		<NumberDecrementStepper />
																	</NumberInputStepper>
																</NumberInput>
																<InputRightAddon>
																	{__('Hours', 'masteriyo')}
																</InputRightAddon>
															</InputGroup>
														)}
													/>
													<FormErrorMessage>
														{errors?.duration_hour &&
															errors?.duration_hour?.message}
													</FormErrorMessage>
												</FormControl>

												<FormControl isInvalid={!!errors?.duration_minute}>
													<Controller
														name="duration_minute"
														defaultValue={minutes || 0}
														rules={{
															required: __('Minutes is required.', 'masteriyo'),
															min: 0,
															max: 59,
														}}
														render={({ field }) => (
															<InputGroup display="flex" flexDirection="row">
																<NumberInput {...field} w="100%">
																	<NumberInputField rounded="sm" />
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
													<FormErrorMessage>
														{errors?.duration_minute &&
															errors?.duration_minute?.message}
													</FormErrorMessage>
												</FormControl>
											</Stack>
										</Stack>
										<FormControl>
											<FormLabel>
												{__('Maximum Students', 'masteriyo')}
											</FormLabel>
											<RadioGroup
												onChange={setEnrollDisplayValue}
												value={enrollDisplayValue}>
												<Stack direction="column" spacing="4">
													<Stack direction="row" spacing="8" align="flex-start">
														<Radio
															onChange={(e: any) =>
																setValue('enrollment_limit', e.target.value)
															}
															value="0">
															{__('No limit', 'masteriyo')}
														</Radio>

														<Radio value="1">{__('Limit', 'masteriyo')}</Radio>
													</Stack>
													<Collapse
														in={enrollDisplayValue != '0'}
														animateOpacity>
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
											</RadioGroup>
										</FormControl>
									</Stack>
								)}
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
												<Stack
													direction={{ base: 'column', sm: 'row' }}
													justifyContent="center"
													alignItems="center">
													<Radio value="true" />
													<Text textAlign="center">
														{__('Always Visible', 'masteriyo')}
													</Text>
												</Stack>
												<Stack
													direction={{ base: 'column', sm: 'row' }}
													justifyContent="center"
													alignItems="center">
													<Radio value="false" />
													<Text textAlign="center">
														{__('Only Visible to Enrollers', 'masteriyo')}
													</Text>
												</Stack>
											</Stack>
										</RadioGroup>
									</FormControl>
								</Stack>
							</TabPanel>

							<TabPanel>
								<Stack direction="column" spacing="8">
									<FormControl>
										<FormLabel>{__('Pricing Option', 'masteriyo')}</FormLabel>
										<RadioGroup
											onChange={onChangePriceType}
											value={pricingDisplayValue}
											defaultValue={courseData?.price_type}>
											<Stack direction="column" spacing="4">
												<Stack direction="column">
													<Radio
														value="free"
														onChange={() => {
															setAccessModeType('open');
															setValue('access_mode', 'open');
														}}>
														{__('Free', 'masteriyo')}
													</Radio>
													<Collapse
														in={pricingDisplayValue != 'paid'}
														animateOpacity>
														<RadioGroup
															onChange={(accessMode: string) => {
																setValue('access_mode', accessMode);
																setAccessModeType(accessMode);
															}}
															value={accessModeType}>
															<Stack direction="column" spacing="3" ml="5">
																<Radio
																	value="open"
																	isChecked={accessModeType === 'open'}>
																	{__(
																		'Does not need registration',
																		'masteriyo'
																	)}
																</Radio>
																<Radio
																	value="need_registration"
																	isChecked={
																		accessModeType === 'need_registration'
																	}>
																	{__('Needs registration', 'masteriyo')}
																</Radio>
															</Stack>
														</RadioGroup>
													</Collapse>
												</Stack>
												<Radio
													onChange={() => setValue('access_mode', 'one_time')}
													value="paid">
													{__('Paid', 'masteriyo')}
												</Radio>
												<Collapse
													in={pricingDisplayValue != 'free'}
													animateOpacity>
													<FormControl>
														<FormLabel>
															{__('One Time Fee', 'masteriyo')}
														</FormLabel>
														<Controller
															name="regular_price"
															defaultValue={
																courseData?.regular_price
																	? courseData.regular_price
																	: ''
															}
															render={({ field }) => (
																<NumberInput {...field} w="full" min={0}>
																	<NumberInputField
																		borderRadius="sm"
																		shadow="input"
																	/>
																	<NumberInputStepper>
																		<NumberIncrementStepper />
																		<NumberDecrementStepper />
																	</NumberInputStepper>
																</NumberInput>
															)}
														/>
														<FormHelperText>
															{currencyCode} ({decodeEntity(currencySymbol)})
														</FormHelperText>
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
			</form>
		</Box>
	);
};

export default CourseSetting;
