import {
	FormControl,
	FormHelperText,
	FormLabel,
	Input,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Radio,
	RadioGroup,
	Select,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import ImageUpload from '../../../components/common/ImageUpload';
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
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Design', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Single Course', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Course Thumbnail', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel minW="2xs">
										{__('Show/Hide Search', 'masteriyo')}
									</FormLabel>
									<Switch
										{...register('courses.enable_search')}
										defaultChecked={coursesData?.enable_search}
									/>
								</Stack>
							</FormControl>

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
								<FormLabel minW="2xs">
									{__('Course Per Row', 'masteriyo')}
								</FormLabel>
								<Controller
									name="courses.per_row"
									defaultValue={coursesData?.per_row}
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
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
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
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
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
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel minW="2xs">
										{__('Enable Review', 'masteriyo')}
									</FormLabel>
									<Switch
										{...register('courses.enable_review')}
										defaultChecked={coursesData?.enable_review}
									/>
								</Stack>
							</FormControl>

							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel minW="2xs">
										{__('Enable Questions & Answers', 'masteriyo')}
									</FormLabel>
									<Switch
										{...register('courses.enable_questions_answers')}
										defaultChecked={coursesData?.enable_questions_answers}
									/>
								</Stack>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default CoursesSettings;
