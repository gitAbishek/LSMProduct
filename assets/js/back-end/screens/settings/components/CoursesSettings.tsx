import {
	Stack,
	Box,
	Flex,
	Heading,
	FormControl,
	FormLabel,
	Input,
	Select,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Switch,
	Radio,
	RadioGroup,
	FormHelperText,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { CoursesSettingsMap } from '../../../types';

/* TODO
[ ] Add Real urls for the permalink settings
*/
interface Props {
	coursesData?: CoursesSettingsMap;
}

const CoursesSettings: React.FC<Props> = (props) => {
	const { coursesData: coursesData } = props;
	const { register, setValue } = useFormContext();

	return (
		<Stack direction="column" spacing="8">
			<Box>
				<Stack direction="column" spacing="8">
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

					<FormControl>
						<FormLabel minW="2xs">
							{__('Placeholder Image', 'masteriyo')}
						</FormLabel>
						<ImageUpload
							name="courses.placeholder_image"
							mediaId={coursesData?.placeholder_image}
							setValue={setValue}
							register={register}
						/>
					</FormControl>
					<FormControl>
						<FormLabel minW="2xs">
							{__('Add to cart behavior', 'masteriyo')}
						</FormLabel>
						<Select
							{...register('courses.add_to_cart_behavior')}
							defaultValue={coursesData?.add_to_cart_behavior}>
							<option value="open-link">{__('Open Link', 'masteriyo')}</option>
						</Select>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Course Per Page', 'masteriyo')}
						</FormLabel>
						<Controller
							name="courses.per_page"
							defaultValue={coursesData?.per_page}
							render={({ field }) => (
								<NumberInput {...field}>
									<NumberInputField borderRadius="sm" shadow="input" />
									<NumberInputStepper>
										<NumberIncrementStepper />
										<NumberDecrementStepper />
									</NumberInputStepper>
								</NumberInput>
							)}
						/>
					</FormControl>

					<FormControl>
						<Stack direction="row">
							<FormLabel minW="2xs">
								{__('Enable Editing Published Course', 'masteriyo')}
							</FormLabel>
							<Controller
								name="courses.enable_editing"
								render={({ field }) => (
									<Switch
										{...field}
										defaultChecked={coursesData?.enable_editing}
									/>
								)}
							/>
						</Stack>
					</FormControl>
				</Stack>
			</Box>
			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('Single Course', 'masteriyo')}
						</Heading>
					</Flex>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Course Category Base', 'masteriyo')}
						</FormLabel>
						<Input
							type="text"
							defaultValue={coursesData?.category_base}
							{...register('courses.category_base')}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Couses Tag Base', 'masteriyo')}
						</FormLabel>
						<Input
							type="text"
							{...register('courses.tag_base')}
							defaultValue={coursesData?.tag_base}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Course Difficulty base', 'masteriyo')}
						</FormLabel>
						<Input
							type="text"
							{...register('courses.difficulty_base')}
							defaultValue={coursesData?.difficulty_base}
						/>
					</FormControl>

					<FormControl>
						<Stack direction="row">
							<FormLabel minW="2xs">
								{__('Single Course Permalink', 'masteriyo')}
							</FormLabel>
							<Controller
								defaultValue={coursesData?.single_course_permalink}
								name="courses.single_course_permalink"
								render={({ field }) => (
									<RadioGroup {...field}>
										<Stack spacing={3} direction="column">
											<Radio value="default">
												<FormHelperText mt="0">
													http://example.com?masteriyo_course=sample-course
												</FormHelperText>
											</Radio>
											<Radio value="pretty">
												<FormHelperText mt="0">
													http://example.com/course/sample-course
												</FormHelperText>
											</Radio>
										</Stack>
									</RadioGroup>
								)}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<Stack direction="row">
							<FormLabel minW="2xs">
								{__('Single Lesson Permalink', 'masteriyo')}
							</FormLabel>
							<Input
								type="text"
								defaultValue={coursesData?.single_lesson_permalink}
								{...register('single_lesson_permalink')}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<Stack direction="row">
							<FormLabel minW="2xs">
								{__('Single Quiz Permalink', 'masteriyo')}
							</FormLabel>
							<Input
								type="text"
								defaultValue={coursesData?.single_quiz_permalink}
								{...register('courses.single_quiz_permalink')}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<Stack direction="row">
							<FormLabel minW="2xs">
								{__('Single Section Permalink', 'masteriyo')}
							</FormLabel>
							<Input
								type="text"
								defaultValue={coursesData?.single_section_permalink}
								{...register('courses.single_section_permalink')}
							/>
						</Stack>
					</FormControl>
				</Stack>
			</Box>
			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('Course Thumbnail', 'masteriyo')}
						</Heading>
					</Flex>
					<FormControl>
						<Stack direction="row" spacing="4">
							<FormLabel minW="2xs">
								{__('Show Thumbnail', 'masteriyo')}
							</FormLabel>
							<Switch
								{...register('courses.show_thumbnail')}
								defaultChecked={coursesData?.show_thumbnail}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Thumbnail Size', 'masteriyo')}
						</FormLabel>
						<Select
							defaultValue={coursesData?.thumbnail_size}
							{...register('courses.thumbnail_size')}>
							<option value="thumbnail">Thumbnail</option>
							<option value="medium">Medium</option>
							<option value="medium_large">Medium Large</option>
							<option value="large">large</option>
						</Select>
					</FormControl>
				</Stack>
			</Box>
		</Stack>
	);
};

export default CoursesSettings;