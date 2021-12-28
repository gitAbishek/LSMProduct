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
	Image,
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
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect } from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { infoIconStyles } from '../../../back-end/config/styles';
import {
	CoursePerRow1,
	CoursePerRow2,
	CoursePerRow3,
	CoursePerRow4,
} from '../../../back-end/constants/images';
interface Props {
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
}

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

	const RadioCard = (props: any) => {
		const { getInputProps, getCheckboxProps } = useRadio(props);

		const input = getInputProps();
		const checkbox = getCheckboxProps();

		return (
			<Box as="label">
				<input {...input} />
				<Box
					{...checkbox}
					cursor="pointer"
					borderWidth="2px"
					// borderRadius="md"
					// boxShadow="md"
					_checked={{
						// bg: 'blue.400',
						color: 'white',
						borderColor: 'blue.400',
					}}
					_focus={{
						boxShadow: 'outline',
					}}
					p={1}>
					{props.children}
				</Box>
			</Box>
		);
	};

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
									<RadioCard {...getRadioProps({ value: '1' })}>
										<Image src={CoursePerRow1} />
									</RadioCard>
									<RadioCard {...getRadioProps({ value: '2' })}>
										<Image src={CoursePerRow2} />
									</RadioCard>
									<RadioCard {...getRadioProps({ value: '3' })}>
										<Image src={CoursePerRow3} />
									</RadioCard>
									<RadioCard {...getRadioProps({ value: '4' })}>
										<Image src={CoursePerRow4} />
									</RadioCard>
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
