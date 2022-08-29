import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	HStack,
	Icon,
	Link,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	Stack,
	Text,
	Tooltip,
	useRadio,
	useRadioGroup,
	UseRadioProps,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect } from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { infoIconStyles } from '../../../back-end/config/styles';
import {
	CoursePerRow1,
	CoursePerRow1Active,
	CoursePerRow2,
	CoursePerRow2Active,
	CoursePerRow3,
	CoursePerRow3Active,
	CoursePerRow4,
	CoursePerRow4Active,
} from '../../../back-end/constants/images';
interface Props {
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
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

const Course: React.FC<Props> = (props) => {
	const { dashboardURL, prevStep, nextStep } = props;
	const {
		control,
		setValue,
		formState: { errors },
	} = useFormContext();

	const watchCoursePerPage = useWatch({
		name: 'course_archive.display.per_page',
		control,
	});

	const { getRootProps, getRadioProps } = useRadioGroup({
		name: 'course_per_row',
		defaultValue: '3',
		onChange: (data) => {
			setValue('course_archive.display.per_row', data);
		},
	});

	const group = getRootProps();

	useEffect(() => {
		setValue('course_archive.display.per_row', '3');
	}, [setValue]);

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl isInvalid={!!errors?.course_archive?.display?.per_row}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Course Per Row', 'masteriyo')}
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
							<Stack direction="column">
								<HStack {...group}>
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
								</HStack>
								<FormErrorMessage>
									{errors?.course_archive?.display?.per_row &&
										errors?.course_archive?.display?.per_row.message}
								</FormErrorMessage>
							</Stack>
						</Flex>
					</FormControl>

					<FormControl isInvalid={!!errors?.course_archive?.display?.per_page}>
						<Flex justify="space-between" align="flex-start">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Course Per Page', 'masteriyo')}
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
							<Stack direction="column">
								<Controller
									name="course_archive.display.per_page"
									defaultValue={12}
									rules={{ required: true }}
									render={({ field }) => (
										<Slider
											{...field}
											aria-label="course-per-page"
											defaultValue={watchCoursePerPage || 12}
											max={24}
											min={1}
											w="md">
											<SliderTrack>
												<SliderFilledTrack />
											</SliderTrack>
											<SliderThumb boxSize="6" bgColor="primary.500">
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
							</Stack>
						</Flex>
					</FormControl>

					<Flex justify="space-between" align="center">
						<Button onClick={prevStep} rounded="3px" variant="outline">
							{__('Back', 'masteriyo')}
						</Button>
						<ButtonGroup>
							<Link href={dashboardURL ? dashboardURL : '#'}>
								<Button variant="ghost">
									{__('Skip to Dashboard', 'masteriyo')}
								</Button>
							</Link>
							<Button onClick={nextStep} rounded="3px">
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
