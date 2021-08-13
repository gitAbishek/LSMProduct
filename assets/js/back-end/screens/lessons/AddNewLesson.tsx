import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	Flex,
	Heading,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router';
import Header from '../../components/common/Header';
import PageNav from '../../components/common/PageNav';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { LessonSchema, SectionSchema } from '../../schemas';
import API from '../../utils/api';
import { deepClean, deepMerge } from '../../utils/utils';
import FeaturedImage from '../courses/components/FeaturedImage';
import Description from './components/Description';
import Name from './components/Name';
import VideoSource from './components/VideoSource';

const AddNewLesson: React.FC = () => {
	const { sectionId, courseId }: any = useParams();
	const methods = useForm();
	const toast = useToast();
	const history = useHistory();
	const lessonAPI = new API(urls.lessons);
	const sectionsAPI = new API(urls.sections);

	// checks whether section exist or not
	const sectionQuery = useQuery<SectionSchema>(
		[`section${sectionId}`, sectionId],
		() => sectionsAPI.get(sectionId)
	);

	// adds lesson on the database
	const addLesson = useMutation((data: LessonSchema) => lessonAPI.store(data), {
		onSuccess: (data: LessonSchema) => {
			toast({
				title: __('Lesson Added', 'masteriyo'),
				description: data.name + __(' is successfully added.', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			history.push({
				pathname: routes.courses.edit.replace(':courseId', courseId),
				search: '?page=builder',
			});
		},
	});

	const onSubmit = (data: LessonSchema) => {
		const newData = {
			course_id: courseId,
			parent_id: sectionId,
		};
		addLesson.mutate(deepMerge(deepClean(data), newData));
	};

	if (sectionQuery.isSuccess && sectionQuery.data.course_id == courseId) {
		return (
			<Stack direction="column" spacing="8" alignItems="center">
				<Header showLinks />
				<Container maxW="container.xl">
					<Stack direction="column" spacing="6">
						<PageNav
							currentTitle={__('Add New Lesson')}
							courseName={sectionQuery?.data?.course_name}
							courseId={courseId}
						/>
						<FormProvider {...methods}>
							<Box bg="white" p="10" shadow="box">
								<Stack direction="column" spacing="8">
									<Flex aling="center" justify="space-between">
										<Heading as="h1" fontSize="x-large">
											{__('Add New Lesson', 'masteriyo')}
										</Heading>
										<Menu placement="bottom-end">
											<MenuButton
												as={IconButton}
												icon={<BiDotsVerticalRounded />}
												variant="outline"
												rounded="sm"
												fontSize="large"
											/>
											<MenuList>
												<MenuItem icon={<BiEdit />}>
													{__('Edit', 'masteriyo')}
												</MenuItem>
												<MenuItem icon={<BiTrash />}>
													{__('Delete', 'masteriyo')}
												</MenuItem>
											</MenuList>
										</Menu>
									</Flex>

									<form onSubmit={methods.handleSubmit(onSubmit)}>
										<Stack direction="column" spacing="6">
											<Name />
											<Description />
											<FeaturedImage />
											<VideoSource />
											<Box py="3">
												<Divider />
											</Box>

											<ButtonGroup>
												<Button
													colorScheme="blue"
													type="submit"
													isLoading={addLesson.isLoading}>
													{__('Add New Lesson', 'masteriyo')}
												</Button>
												<Button
													variant="outline"
													onClick={() => history.goBack()}>
													{__('Cancel', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</form>
								</Stack>
							</Box>
						</FormProvider>
					</Stack>
				</Container>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default AddNewLesson;
