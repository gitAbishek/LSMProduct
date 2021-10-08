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
	Icon,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiBook, BiPlus } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink, useHistory } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonCourseList } from '../../skeleton';
import API from '../../utils/api';
import CourseFilter from './components/CourseFilter';
import CourseList from './components/CourseList';

interface FilterParams {
	category?: string | number;
	search?: string;
	status?: string;
	isOnlyFree?: boolean;
	price?: string | number;
	per_page?: number;
	page?: number;
}

const AllCourses = () => {
	const courseAPI = new API(urls.courses);
	const history = useHistory();
	const [filterParams, setFilterParams] = useState<FilterParams>({});
	const [deleteCourseId, setDeleteCourseId] = useState<number>();

	const queryClient = useQueryClient();
	const { onClose, onOpen, isOpen } = useDisclosure();
	const courseQuery = useQuery(['courseList', filterParams], () =>
		courseAPI.list(filterParams)
	);

	const cancelRef = React.useRef<any>();

	const deleteCourse = useMutation((id: number) => courseAPI.delete(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('courseList');
			onClose();
		},
	});

	const onDeletePress = (courseId: number) => {
		onOpen();
		setDeleteCourseId(courseId);
	};

	const onDeleteCofirm = () => {
		deleteCourseId && deleteCourse.mutate(deleteCourseId);
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header
				thirdBtn={{
					label: __('Add New Course', 'masteriyo'),
					action: () => history.push(routes.courses.add),
					icon: <Icon as={BiPlus} fontSize="md" />,
				}}>
				<List d="flex">
					<ListItem mb="0">
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							_hover={{ color: 'blue.500' }}
							to={routes.courses.list}>
							<ListIcon as={BiBook} />
							{__('All Courses', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="10">
						<CourseFilter setFilterParams={setFilterParams} />

						<Stack direction="column" spacing="8">
							<Table>
								<Thead>
									<Tr>
										<Th>{__('Title', 'masteriyo')}</Th>
										<Th>{__('Categories', 'masteriyo')}</Th>
										<Th>{__('Author', 'masteriyo')}</Th>
										<Th>{__('Price', 'masteriyo')}</Th>
										<Th>{__('Date', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{courseQuery.isLoading && <SkeletonCourseList />}
									{courseQuery.isSuccess &&
									courseQuery?.data?.data.length === 0 ? (
										<EmptyInfo message={__('No courses found.', 'masteriyo')} />
									) : (
										courseQuery?.data?.data.map((course: any) => (
											<CourseList
												id={course.id}
												name={course.name}
												price={course.price}
												categories={course.categories}
												key={course.id}
												createdOn={course.date_created}
												permalink={course.permalink}
												editPostLink={course.edit_post_link}
												author={course.author}
												onDeletePress={onDeletePress}
												status={course.status}
											/>
										))
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{courseQuery.isSuccess && courseQuery?.data?.data.length > 0 && (
					<MasteriyoPagination
						metaData={courseQuery.data.meta}
						setFilterParams={setFilterParams}
						perPageText={__('Courses Per Page:', 'masteriyo')}
					/>
				)}
			</Container>
			<AlertDialog
				isOpen={isOpen}
				onClose={onClose}
				isCentered
				leastDestructiveRef={cancelRef}>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader>
							{__('Deleting Course')} {name}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__("Are you sure? You can't restore this back", 'masteriyo')}
						</AlertDialogBody>
						<AlertDialogFooter>
							<ButtonGroup>
								<Button onClick={onClose} variant="outline" ref={cancelRef}>
									{__('Cancel', 'masteriyo')}
								</Button>
								<Button
									colorScheme="red"
									isLoading={deleteCourse.isLoading}
									onClick={onDeleteCofirm}>
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

export default AllCourses;
