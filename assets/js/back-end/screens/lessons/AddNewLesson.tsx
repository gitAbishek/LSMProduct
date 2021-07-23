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
import PageNav from '../../components/common/PageNav';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import HeaderBuilder from '../../components/layout/HeaderBuilder';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
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
	const sectionQuery = useQuery([`section${sectionId}`, sectionId], () =>
		sectionsAPI.get(sectionId)
	);

	// adds lesson on the database
	const addLesson = useMutation((data: object) => lessonAPI.store(data), {
		onSuccess: (data: any) => {
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

	const onSubmit = (data: object) => {
		const newData = {
			course_id: courseId,
			parent_id: sectionId,
		};
		addLesson.mutate(mergeDeep(data, newData));
	};

	if (sectionQuery.isSuccess) {
		return (
			<Stack direction="column" spacing="8" alignItems="center">
				<HeaderBuilder courseId={courseId} />
				<Container maxW="container.xl">
					<PageNav isCurrentTitle={__('Add New Lesson')} courseId={courseId} />
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
				</Container>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default AddNewLesson;
