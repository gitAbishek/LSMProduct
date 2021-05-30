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
import { useQuery } from 'react-query';
import urls from '../../../constants/urls';
import { CoursesSettingsMap } from '../../../types';
import API from '../../../utils/api';

interface Props {
	coursesData?: CoursesSettingsMap;
}
const CoursesSettings: React.FC<Props> = (props) => {
	const { coursesData: coursesData } = props;
	const { register, setValue } = useFormContext();
	const categoryAPI = new API(urls.categories);
	const tagsAPI = new API(urls.tags);
	const categoryQuery = useQuery('categories', () => categoryAPI.list());
	const tagsQuery = useQuery('tags', () => tagsAPI.list());

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
						<Select
							{...register('courses.category_base')}
							defaultValue={coursesData?.category_base}>
							{categoryQuery?.data?.map(
								(category: { id: number; name: string; slug: string }) => (
									<option value={category.id} key={category.id}>
										{category.name}
									</option>
								)
							)}
						</Select>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Couses Tag Base', 'masteriyo')}
						</FormLabel>
						<Select
							{...register('courses.tag_base')}
							defaultValue={coursesData?.tag_base}>
							{tagsQuery?.data?.map(
								(tag: { id: number; name: string; slug: string }) => (
									<option value={tag.id} key={tag.id}>
										{tag.name}
									</option>
								)
							)}
						</Select>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Course Difficulty base', 'masteriyo')}
						</FormLabel>
						<Select
							{...register('courses.difficulty_base')}
							defaultValue={coursesData?.difficulty_base}>
							<option value="beginner">{__('Beginner', 'masteriyo')}</option>
						</Select>
					</FormControl>

					<FormControl>
						<Stack direction="row">
							<FormLabel minW="2xs">
								{__('Single Course Permalink', 'masteriyo')}
							</FormLabel>
							<Controller
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
							<RadioGroup>
								<Stack spacing={3} direction="column">
									<Radio colorScheme="blue" value="default">
										<FormHelperText mt="0">
											http://example.com?masteriyo_course=sample-course
										</FormHelperText>
									</Radio>
									<Radio colorScheme="green" value="pretty">
										<FormHelperText mt="0">
											http://example.com/course/sample-course
										</FormHelperText>
									</Radio>
								</Stack>
							</RadioGroup>
						</Stack>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Single Quiz Permalink', 'masteriyo')}
						</FormLabel>
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
						<FormLabel minW="2xs">
							{__('Single Section Permalink', 'masteriyo')}
						</FormLabel>
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
							<FormLabel minW="2xs">
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
							<FormLabel minW="2xs">
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
							<FormLabel minW="2xs">
								{__('Show Thumbnail', 'masteriyo')}
							</FormLabel>
							<Switch
								{...register('courses.show')}
								defaultChecked={coursesData?.show_thumbnail}
							/>
						</Stack>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Thumbnail Size', 'masteriyo')}
						</FormLabel>
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
