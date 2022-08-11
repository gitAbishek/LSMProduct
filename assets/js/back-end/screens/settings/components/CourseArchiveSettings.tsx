import {
	Box,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Icon,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Text,
	Tooltip,
	useRadio,
	useRadioGroup,
	UseRadioProps,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import {
	CoursePerRow1,
	CoursePerRow1Active,
	CoursePerRow2,
	CoursePerRow2Active,
	CoursePerRow3,
	CoursePerRow3Active,
	CoursePerRow4,
	CoursePerRow4Active,
} from '../../../../back-end/constants/images';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { CourseArchiveSettingsMap } from '../../../types';

interface Props {
	courseArchiveData?: CourseArchiveSettingsMap;
}

const RadioCard: React.FC<{
	normalComponent: React.ReactNode;
	activeComponent: React.ReactNode;
	radioProps: UseRadioProps;
}> = (props) => {
	const { radioProps, normalComponent, activeComponent } = props;
	const { state, getInputProps, getCheckboxProps } = useRadio(radioProps);

	const input = getInputProps();
	const checkbox = getCheckboxProps();

	return (
		<Box as="label">
			<input {...input} />
			<Box {...checkbox} cursor="pointer" p={1}>
				{state.isChecked ? activeComponent : normalComponent}
			</Box>
		</Box>
	);
};

const CourseArchiveSettings: React.FC<Props> = (props) => {
	const { courseArchiveData } = props;
	const {
		register,
		control,
		formState: { errors },
		setValue,
	} = useFormContext();

	const watchCoursePerPage = useWatch({
		name: 'course_archive.display.per_page',
		defaultValue: courseArchiveData?.display.per_page,
		control,
	});

	const { getRootProps, getRadioProps } = useRadioGroup({
		name: 'course_per_row',
		defaultValue: courseArchiveData?.display.per_row + '',
		onChange: (data) => {
			setValue('course_archive.display.per_row', data);
		},
	});

	const group = getRootProps();

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel>
										{__('Show Search', 'masteriyo')}
										<Tooltip
											label={__('Display search on courses page.', 'masteriyo')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Switch
										{...register('course_archive.display.enable_search')}
										defaultChecked={courseArchiveData?.display?.enable_search}
									/>
								</Stack>
							</FormControl>

							<FormControl
								isInvalid={!!errors?.course_archive?.display?.per_page}>
								<FormLabel>
									{__('Courses Per Page', 'masteriyo')}
									<Tooltip
										label={__(
											'Total number of courses to be shown in courses page.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Controller
									name="course_archive.display.per_page"
									defaultValue={watchCoursePerPage || 12}
									rules={{ required: true }}
									render={({ field }) => (
										<Slider
											{...field}
											aria-label="course-per-page"
											max={24}
											min={1}>
											<SliderTrack>
												<SliderFilledTrack />
											</SliderTrack>
											<SliderThumb boxSize="6" bgColor="blue.500">
												<Text fontSize="xs" fontWeight="semibold" color="white">
													{watchCoursePerPage || 12}
												</Text>
											</SliderThumb>
										</Slider>
									)}
								/>
								<FormErrorMessage>
									{errors?.course_archive?.display?.per_page &&
										errors?.course_archive?.display?.per_page.message}
								</FormErrorMessage>
							</FormControl>

							<FormControl
								isInvalid={!!errors?.course_archive?.display?.per_row}>
								<FormLabel>
									{__('Courses Per Row', 'masteriyo')}
									<Tooltip
										label={__(
											'Total number of courses to be shown per row in courses page.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Stack {...group} display="flex" direction={['column', 'row']}>
									<RadioCard
										radioProps={getRadioProps({ value: '1' })}
										normalComponent={<CoursePerRow1 />}
										activeComponent={<CoursePerRow1Active />}
									/>
									<RadioCard
										radioProps={getRadioProps({ value: '2' })}
										normalComponent={<CoursePerRow2 />}
										activeComponent={<CoursePerRow2Active />}
									/>
									<RadioCard
										radioProps={getRadioProps({ value: '3' })}
										normalComponent={<CoursePerRow3 />}
										activeComponent={<CoursePerRow3Active />}
									/>
									<RadioCard
										radioProps={getRadioProps({ value: '4' })}
										normalComponent={<CoursePerRow4 />}
										activeComponent={<CoursePerRow4Active />}
									/>
								</Stack>
								<FormErrorMessage>
									{errors?.course_archive?.display?.per_row &&
										errors?.course_archive?.display?.per_row.message}
								</FormErrorMessage>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default CourseArchiveSettings;
