import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
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
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import DescriptionInput from './components/DescriptionInput';
import SlugInput from './components/SlugInput';
import NameInput from './components/NameInput';
import Header from './components/Header';

const EditCourseCategory = () => {
	const { categoryId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const cancelRef = useRef<any>();
	const categoryAPI = new API(urls.categories);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);

	const categoryQuery = useQuery(
		[`courseCategory${categoryId}`, categoryId],
		() => categoryAPI.get(categoryId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const updateCategory = useMutation(
		(data: object) => categoryAPI.update(categoryId, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Category Updated Successfully', 'masteriyo'),
					description: data.name + __(' has been updated successfully.'),
					isClosable: true,
					status: 'success',
				});
			},
			onError: (error) => {
				toast({
					title: __('Failed to update category', 'masteriyo'),
					description: `${error}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const deleteCategory = useMutation(
		(categoryId: number) => categoryAPI.delete(categoryId),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Category Deleted Successfully', 'masteriyo'),
					description: data.name + __(' has been deleted successfully.'),
					isClosable: true,
					status: 'error',
				});
			},
			onError: (error) => {
				toast({
					title: __('Failed to delete category', 'masteriyo'),
					description: `${error}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: object) => {
		updateCategory.mutate(data);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		deleteCategory.mutate(categoryId);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	if (categoryQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<FormProvider {...methods}>
					<Box bg="white" p="10" shadow="box">
						<Stack direction="column" spacing="8">
							<Flex aling="center" justify="space-between">
								<Heading as="h1" fontSize="x-large">
									{__('Edit Category', 'masteriyo')}
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
										<MenuItem icon={<BiTrash />} onClick={onDeletePress}>
											{__('Delete', 'masteriyo')}
										</MenuItem>
									</MenuList>
								</Menu>
							</Flex>

							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<Stack direction="column" spacing="6">
									<NameInput defaultValue={categoryQuery.data.name} />
									<SlugInput defaultValue={categoryQuery.data.slug} />
									<DescriptionInput
										defaultValue={categoryQuery.data.description}
									/>

									<Box py="3">
										<Divider />
									</Box>

									<ButtonGroup>
										<Button
											colorScheme="blue"
											type="submit"
											isLoading={updateCategory.isLoading}>
											{__('Update Category', 'masteriyo')}
										</Button>
										<Button
											variant="outline"
											onClick={() =>
												history.push(routes.course_categories.list)
											}>
											{__('Cancel', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</Stack>
							</form>
						</Stack>
					</Box>
					<AlertDialog
						isOpen={isDeleteModalOpen}
						onClose={onDeleteModalClose}
						isCentered
						leastDestructiveRef={cancelRef}>
						<AlertDialogOverlay>
							<AlertDialogContent>
								<AlertDialogHeader>
									{__('Delete Category')} {name}
								</AlertDialogHeader>

								<AlertDialogBody>
									{__(
										"Are you sure? You can't restore this category",
										'masteriyo'
									)}
								</AlertDialogBody>
								<AlertDialogFooter>
									<ButtonGroup>
										<Button
											ref={cancelRef}
											onClick={onDeleteModalClose}
											variant="outline">
											{__('Cancel', 'masteriyo')}
										</Button>
										<Button
											colorScheme="red"
											onClick={onDeleteConfirm}
											isLoading={deleteCategory.isLoading}>
											{__('Delete', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</AlertDialogFooter>
							</AlertDialogContent>
						</AlertDialogOverlay>
					</AlertDialog>
				</FormProvider>
			</Container>
		</Stack>
	);
};

export default EditCourseCategory;
