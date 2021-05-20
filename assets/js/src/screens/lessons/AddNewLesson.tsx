import {
	Box,
	Button,
	ButtonGroup,
	Center,
	Container,
	Divider,
	Flex,
	Heading,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Spinner,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import HeaderBuilder from 'Components/layout/HeaderBuilder';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';

const AddNewLesson: React.FC = () => {
	const { sectionId }: any = useParams();
	const methods = useForm();
	const toast = useToast();
	const history = useHistory();
	const contentAPI = new API(urls.contents);
	const lessonAPI = new API(urls.lessons);
	const sectionsAPI = new API(urls.sections);
	const [totalContentCount, setTotalContentCount] = useState<any>(0);
	const [courseId, setCourseId] = useState<any>(null);

	// gets total number of content on section
	const contentQuery = useQuery(
		[`content${sectionId}`, sectionId],
		() => contentAPI.list({ section: sectionId }),
		{
			enabled: !!sectionId,
			onSuccess: (data: any) => {
				setTotalContentCount(data.length);
			},
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	// checks whether section exist or not
	const sectionQuery = useQuery(
		[`section${sectionId}`, sectionId],
		() => sectionsAPI.get(sectionId),
		{
			onSuccess: (data: any) => {
				setCourseId(data.course_id);
			},
			onError: () => {
				history.push(routes.notFound);
			},
		}
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
			history.push(routes.builder.replace(':courseId', data.course_id));
		},
	});

	const onSubmit = (data: object) => {
		const newData = {
			course_id: courseId,
			parent_id: sectionId,
			menu_order: totalContentCount + 1,
		};
		addLesson.mutate(mergeDeep(data, newData));
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			{sectionQuery.isSuccess && <HeaderBuilder courseId={courseId} />}
			<Container maxW="container.xl">
				<FormProvider {...methods}>
					<Box bg="white" p="10" shadow="box">
						{(contentQuery.isLoading || sectionQuery.isLoading) && (
							<Center minH="xs">
								<Spinner />
							</Center>
						)}
						{(contentQuery.isSuccess || sectionQuery.isSuccess) && (
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
						)}
					</Box>
				</FormProvider>
			</Container>
		</Stack>
	);
};

export default AddNewLesson;
