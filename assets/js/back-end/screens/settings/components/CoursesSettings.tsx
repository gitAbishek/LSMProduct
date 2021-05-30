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
import React, { useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import urls from '../../../constants/urls';
import { CoursesSettingsMap } from '../../../types';
import API from '../../../utils/api';

/* TODO
[ ] Add Real urls for the permalink settings
*/
interface Props {
	coursesData?: CoursesSettingsMap;
}

const CoursesSettings: React.FC<Props> = (props) => {
	const { coursesData: coursesData } = props;
	const { register, setValue } = useFormContext();
	const [showCustomSize, setShowCustomSize] = useState(false);

	const categoryAPI = new API(urls.categories);
	const tagsAPI = new API(urls.tags);
	const difficultiesAPI = new API(urls.difficulties);

	const categoryQuery = useQuery('categories', () => categoryAPI.list());
	const tagsQuery = useQuery('tags', () => tagsAPI.list());
	const difficultiesQuery = useQuery('difficulties', () =>
		difficultiesAPI.list()
	);

	const onThumnailSizeChange = (e: any) => {
		setValue('courses.thumbnail_size', e.target.value);
		if (e.target.value === 'custom-size') {
			setShowCustomSize(true);
		} else {
			setShowCustomSize(false);
		}
	};

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
							placeholder={__('Select Category Base', 'masteriyo')}
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
							placeholder={__('Select Tag Base', 'masteriyo')}
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
							placeholder={__('Select Difficulty Base', 'masteriyo')}
							{...register('courses.difficulty_base')}
							defaultValue={coursesData?.difficulty_base}>
							{difficultiesQuery?.data?.map(
								(difficulty: { id: number; name: string; slug: string }) => (
									<option value={difficulty.id} key={difficulty.id}>
										{difficulty.name}
									</option>
								)
							)}
						</Select>
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
							<Controller
								defaultValue={coursesData?.single_lesson_permalink}
								name="courses.single_lesson_permalink"
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
								{__('Single Quiz Permalink', 'masteriyo')}
							</FormLabel>
							<Controller
								defaultValue={coursesData?.single_quiz_permalink}
								name="courses.single_quiz_permalink"
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
								{__('Single Section Permalink', 'masteriyo')}
							</FormLabel>
							<Controller
								defaultValue={coursesData?.single_section_permalink}
								name="courses.single_section_permalink"
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
						<Select
							onChange={onThumnailSizeChange}
							defaultValue={coursesData?.thumbnail_size}>
							<option value="thumbnail">Thumbnail</option>
							<option value="medium">Medium</option>
							<option value="medium_large">Medium Large</option>
							<option value="large">large</option>
							<option value="custom-size">Custom Size</option>
						</Select>
					</FormControl>

					{showCustomSize && (
						<Stack direction="row" spacing="6">
							<FormControl>
								<FormLabel>{__('Width', 'masteriyo')}</FormLabel>
								<Controller
									name="courses.custom-height"
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
								<FormLabel>{__('Height', 'masteriyo')}</FormLabel>
								<Controller
									name="courses.custom-height"
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
						</Stack>
					)}
				</Stack>
			</Box>
		</Stack>
	);
};

export default CoursesSettings;
