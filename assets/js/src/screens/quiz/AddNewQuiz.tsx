import {
	Box,
	Button,
	ButtonGroup,
	Center,
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
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Description from '../courses/components/Description';
import FeaturedImage from '../courses/components/FeaturedImage';
import Name from '../courses/components/Name';
import Info from './components/Info';
import Questions from './components/Questions';

const AddNewQuiz: React.FC = () => {
	const { sectionId }: any = useParams();
	const methods = useForm();
	const history = useHistory();
	const toast = useToast();
	const contentAPI = new API(urls.contents);
	const quizAPI = new API(urls.quizes);
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
				history.goBack();
			},
		}
	);

	const sectionQuery = useQuery(
		[`section${sectionId}`, sectionId],
		() => sectionsAPI.get(sectionId),
		{
			onSuccess: (data: any) => {
				setCourseId(data.course_id);
			},
			onError: () => {
				history.goBack();
			},
		}
	);

	const addQuiz = useMutation((data: object) => quizAPI.store(data), {
		onSuccess: (data: any) => {
			toast({
				title: __('Lesson Added', 'masteriyo'),
				description: data.name + __(' is successfully added.', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			history.push(routes.section.replace(':courseId', data.course_id));
		},
	});
	const onSubmit = (data: object) => {
		addQuiz.mutate(data);
	};

	return (
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
										isLoading={addQuiz.isLoading}>
										{__('Add New Lesson', 'masteriyo')}
									</Button>
									<Button variant="outline" onClick={() => history.goBack()}>
										{__('Cancel', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Stack>
						</form>
					</Stack>
				)}
			</Box>
		</FormProvider>
	);
};

export default AddNewQuiz;
