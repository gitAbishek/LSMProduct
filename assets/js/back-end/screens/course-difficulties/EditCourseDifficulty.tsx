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
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import DescriptionInput from './components/DescriptionInput';
import SlugInput from './components/SlugInput';
import NameInput from './components/NameInput';
import Header from './components/Header';

const EditCourseDifficulty = () => {
	const { difficultyId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const cancelRef = useRef<any>();
	const difficultyAPI = new API(urls.difficulties);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();

	const difficultyQuery = useQuery(
		[`courseDifficulty${difficultyId}`, difficultyId],
		() => difficultyAPI.get(difficultyId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const updateDifficulty = useMutation(
		(data: object) => difficultyAPI.update(difficultyId, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Difficulty Updated Successfully', 'masteriyo'),
					description: data.name + __(' has been updated successfully.'),
					isClosable: true,
					status: 'success',
				});
				history.push(routes.course_difficulties.list);
				queryClient.invalidateQueries('courseDifficultiesList');
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to update difficulty', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const deleteDifficulty = useMutation(
		(difficultyId: number) =>
			difficultyAPI.delete(difficultyId, { force: true }),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Difficulty Deleted Successfully', 'masteriyo'),
					description: data.name + __(' has been deleted successfully.'),
					isClosable: true,
					status: 'error',
				});
				history.push(routes.course_difficulties.list);
				queryClient.invalidateQueries('courseDifficultiesList');
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to delete difficulty', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: object) => {
		updateDifficulty.mutate(data);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		deleteDifficulty.mutate(difficultyId);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	if (difficultyQuery.isLoading) {
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
									{__('Edit Difficulty', 'masteriyo')}
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
									<NameInput defaultValue={difficultyQuery.data.name} />
									<SlugInput defaultValue={difficultyQuery.data.slug} />
									<DescriptionInput
										defaultValue={difficultyQuery.data.description}
									/>

									<Box py="3">
										<Divider />
									</Box>

									<ButtonGroup>
										<Button
											colorScheme="blue"
											type="submit"
											isLoading={updateDifficulty.isLoading}>
											{__('Update', 'masteriyo')}
										</Button>
										<Button
											variant="outline"
											onClick={() =>
												history.push(routes.course_difficulties.list)
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
									{__('Delete Difficulty')} {name}
								</AlertDialogHeader>

								<AlertDialogBody>
									{__(
										"Are you sure? You can't restore this difficulty",
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
											isLoading={deleteDifficulty.isLoading}>
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

export default EditCourseDifficulty;
