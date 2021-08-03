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
	Flex,
	Heading,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import PageNav from '../../components/common/PageNav';
import { tableStyles } from '../../config/styles';
import urls from '../../constants/urls';
import { SkeletonCourseTaxonomy } from '../../skeleton';
import API from '../../utils/api';
import CategoryRow from './components/CategoryRow';
import Header from './components/Header';

const AllCourseCategories = () => {
	const categoryAPI = new API(urls.categories);
	const queryClient = useQueryClient();
	const toast = useToast();
	const categoriesQuery = useQuery('courseCategoriesList', () =>
		categoryAPI.list()
	);
	const [deleteCategoryId, setDeleteCategoryId] = useState<number>();
	const { onClose, onOpen, isOpen } = useDisclosure();

	const cancelRef = useRef<any>();

	const deleteCategory = useMutation(
		(id: number) => categoryAPI.delete(id, { force: true }),
		{
			onSuccess: () => {
				onClose();
				queryClient.invalidateQueries('courseCategoriesList');
			},
			onError: (error: any) => {
				onClose();
				toast({
					title: __('Failed to delete category', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onDeletePress = (categoryId: number) => {
		onOpen();
		setDeleteCategoryId(categoryId);
	};

	const onDeleteConfirm = () => {
		deleteCategoryId && deleteCategory.mutate(deleteCategoryId);
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<PageNav currentTitle={__('Categories', 'masteriyo')} />
					<Box bg="white" p="12" shadow="box" mx="auto">
						<Stack direction="column" spacing="8">
							<Flex justify="space-between" aling="center">
								<Heading as="h1" size="lg">
									{__('Categories', 'masteriyo')}
								</Heading>
							</Flex>

							<Table size="sm" sx={tableStyles}>
								<Thead>
									<Tr>
										<Th>{__('Name', 'masteriyo')}</Th>
										<Th>{__('Description', 'masteriyo')}</Th>
										<Th>{__('Slug', 'masteriyo')}</Th>
										<Th>{__('Count', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{categoriesQuery.isLoading && <SkeletonCourseTaxonomy />}
									{categoriesQuery.isSuccess &&
										categoriesQuery.data.map((cat: any) => (
											<CategoryRow
												key={cat.id}
												id={cat.id}
												name={cat.name}
												description={cat.description}
												slug={cat.slug}
												count={cat.count}
												link={cat.link}
												onDeletePress={onDeletePress}
											/>
										))}
								</Tbody>
							</Table>
						</Stack>
					</Box>
				</Stack>
			</Container>
			<AlertDialog
				isOpen={isOpen}
				onClose={onClose}
				isCentered
				leastDestructiveRef={cancelRef}>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader>
							{__('Delete Category')} {name}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__("Are you sure? You can't restore this category", 'masteriyo')}
						</AlertDialogBody>
						<AlertDialogFooter>
							<ButtonGroup>
								<Button ref={cancelRef} onClick={onClose} variant="outline">
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
		</Stack>
	);
};

export default AllCourseCategories;
