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
import React, { Fragment, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';

const EditLesson: React.FC = () => {
	const { lessonId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const lessonAPI = new API(urls.lessons);

	const lessonQuery = useQuery([`section${lessonId}`, lessonId], () =>
		lessonAPI.get(lessonId)
	);

	const updateLesson = useMutation(
		(data: object) => lessonAPI.update(lessonId, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Lesson Updated Successfully', 'masteriyo'),
					description: data.name + __(' has been updated successfully.'),
					isClosable: true,
					status: 'success',
				});
				history.push(routes.section.replace(':courseId', data.course_id));
			},
		}
	);

	const onSubmit = (data: object) => {
		updateLesson.mutate(data);
	};

	return (
		<FormProvider {...methods}>
			<Box bg="white" p="10" shadow="box">
				{lessonQuery.isLoading && (
					<Center minH="xs">
						<Spinner />
					</Center>
				)}
				{lessonQuery.isSuccess && (
					<Stack direction="column" spacing="8">
						<Flex aling="center" justify="space-between">
							<Heading as="h1" fontSize="x-large">
								{__('Edit Lesson', 'masteriyo')}
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
								<Name defaultValue={lessonQuery.data.name} />
								<Description defaultValue={lessonQuery.data.description} />
								<FeaturedImage defaultValue={lessonQuery.data.featured_image} />

								<Box py="3">
									<Divider />
								</Box>

								<ButtonGroup>
									<Button
										colorScheme="blue"
										type="submit"
										isLoading={updateLesson.isLoading}>
										{__('Update Lesson', 'masteriyo')}
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

export default EditLesson;
