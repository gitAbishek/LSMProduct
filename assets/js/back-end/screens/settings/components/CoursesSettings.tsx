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
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { CoursesSettingsMap } from '../../../types';

interface Props {
	coursesData?: CoursesSettingsMap;
}
const CoursesSettings: React.FC<Props> = (props) => {
	const { coursesData: coursesData } = props;
	const { register, setValue } = useFormContext();

	return (
		<Stack direction="column" spacing="8">
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

					<FormControl>
						<FormLabel>{__('Placeholder Image', 'masteriyo')}</FormLabel>
						<ImageUpload
							name="courses.placeholder_image"
							setValue={setValue}
							register={register}
						/>
					</FormControl>
					<FormControl>
						<FormLabel>{__('Add to cart behavior', 'masteriyo')}</FormLabel>
						<Select
							{...register('courses.add_to_cart_behavior')}
							defaultValue={coursesData?.add_to_cart_behavior}>
							<option value="open-link">{__('Open Link', 'masteriyo')}</option>
						</Select>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Course Per Page', 'masteiryo')}</FormLabel>
						<Controller
							name="courses.per_page"
							render={({ field }) => (
								<NumberInput {...field}>
									<NumberInputField />
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
							<FormLabel>
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
				<Stack direction="column" spacing="6">
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
						<FormLabel>{__('Course Category Base', 'masteiryo')}</FormLabel>
						<Select
							{...register('courses.category_base')}
							defaultValue={coursesData?.category_base}>
							<option value="uncategorized" key="uncategorized">
								uncategorized
							</option>
						</Select>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Couses Tag Base', 'masteriyo')}</FormLabel>
						<Select
							{...register('courses.tag_base')}
							defaultValue={coursesData?.tag_base}>
							<option value="something">{__('data', 'masteriyo')}</option>
						</Select>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Course Difficulty base', 'masteriyo')}</FormLabel>
						<Select
							{...register('courses.difficulty_base')}
							defaultValue={coursesData?.difficulty_base}>
							<option value="beginner">{__('Beginner', 'masteriyo')}</option>
						</Select>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Single Course Permalink', 'masteriyo')}</FormLabel>
						<Controller
							name="courses.single_course_permalink"
							render={({ field }) => (
								<RadioGroup {...field}>
									<Stack spacing={3} direction="column">
										<Radio value="default">
											http://example.com?masteriyo_course=sample-course
										</Radio>
										<Radio value="pretty">
											http://example.com/course/sample-course
										</Radio>
									</Stack>
								</RadioGroup>
							)}
						/>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Single Lesson Permalink', 'masteriyo')}</FormLabel>
						<RadioGroup>
							<Stack spacing={3} direction="column">
								<Radio colorScheme="blue" value="default">
									http://example.com?masteriyo_course=sample-course
								</Radio>
								<Radio colorScheme="green" value="pretty">
									http://example.com/course/sample-course
								</Radio>
							</Stack>
						</RadioGroup>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Single Quiz Permalink', 'masteriyo')}</FormLabel>
						<RadioGroup>
							<Stack spacing={3} direction="column">
								<Radio colorScheme="blue" value="default">
									http://example.com?masteriyo_course=sample-course
								</Radio>
								<Radio colorScheme="green" value="pretty">
									http://example.com/course/sample-course
								</Radio>
							</Stack>
						</RadioGroup>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Single Section Permalink', 'masteriyo')}</FormLabel>
						<RadioGroup>
							<Stack spacing={3} direction="column">
								<Radio colorScheme="blue" value="default">
									http://example.com?masteriyo_course=sample-course
								</Radio>
								<Radio colorScheme="green" value="pretty">
									http://example.com/course/sample-course
								</Radio>
							</Stack>
						</RadioGroup>
					</FormControl>

					<FormControl>
						<Stack direction="row" spacing="4">
							<FormLabel>
								{__('Enable Single Course Permalink', 'masteriyo')}
							</FormLabel>
							<Switch
								{...register('courses.enable_single_course_permalink')}
								defaultChecked={coursesData?.enable_single_course_permalink}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<Stack direction="row" spacing="4">
							<FormLabel>
								{__('Enable Single Course Editing', 'masteriyo')}
							</FormLabel>
							<Switch
								{...register('courses.enable_single_course_permalink')}
								defaultChecked={coursesData?.single_course_enable_editing}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<Stack direction="row" spacing="4">
							<FormLabel>{__('Show Thumbnail', 'masteriyo')}</FormLabel>
							<Switch
								{...register('courses.show')}
								defaultChecked={coursesData?.show_thumbnail}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<FormLabel>{__('Thumbnail Size', 'masteriyo')}</FormLabel>
						<Input
							type="text"
							{...register('courses.thumbnail_size')}
							defaultValue={coursesData?.thumbnail_size}
						/>
					</FormControl>
				</Stack>
			</Box>
		</Stack>
	);
};

export default CoursesSettings;
