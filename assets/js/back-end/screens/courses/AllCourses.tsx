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
	Center,
	Container,
	Icon,
	IconButton,
	Link,
	List,
	ListIcon,
	ListItem,
	Select,
	Stack,
	Table,
	Tbody,
	Text,
	Th,
	Thead,
	Tr,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiBook, BiPlus } from 'react-icons/bi';
import { FaChevronLeft, FaChevronRight } from 'react-icons/fa';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink, useHistory } from 'react-router-dom';
import Header from '../../components/common/Header';
import {
	navActiveStyles,
	navLinkStyles,
	tableStyles,
} from '../../config/styles';
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
				<Box bg="white" py="12" shadow="box" mx="auto">
					<Stack direction="column" spacing="10">
						<CourseFilter setFilterParams={setFilterParams} />

						<Stack direction="column" spacing="8">
							<Table size="sm" sx={tableStyles}>
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
										courseQuery.data.data.map((course: any) => (
											<CourseList
												id={course.id}
												name={course.name}
												price={course.price}
												categories={course.categories}
												key={course.id}
												createdOn={course.date_created}
												permalink={course.permalink}
												author={course.author}
												onDeletePress={onDeletePress}
												status={course.status}
											/>
										))}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
			</Container>
			{courseQuery.isSuccess && (
				<Center pb="8">
					<Text fontSize="sm" fontWeight="semibold">
						{__('Courses Per Page:', 'masteriyo')}
					</Text>
					<Select
						defaultValue={courseQuery?.data?.meta?.per_page}
						onChange={(e: any) => {
							setFilterParams({
								per_page: parseInt(e.target.value),
							});
						}}
						w="15"
						ml="2.5"
						mr="2.5">
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="40">40</option>
						<option value="50">50</option>
					</Select>

					{/* <Box
						color="gray.500"
						borderColor="blue.300"
						borderWidth="6px"
						w="36"
						mr="2.5">
						<Text textAlign="center" fontSize="sm" fontWeight="semibold">
							{__(
								`${courseQuery?.data?.meta?.per_page} of ${courseQuery?.data?.meta?.total} courses`,
								'masteriyo'
							)}
						</Text>
					</Box> */}

					<ButtonGroup colorScheme="blue">
						<IconButton
							isDisabled={courseQuery?.data?.meta?.current_page === 1}
							aria-label="Previous page"
							onClick={() =>
								setFilterParams({
									page:
										courseQuery?.data?.meta?.current_page != 1
											? courseQuery?.data?.meta?.current_page - 1
											: courseQuery?.data?.meta?.current_page,
									per_page: filterParams.per_page,
								})
							}
							icon={<FaChevronLeft />}
						/>
						<IconButton
							isDisabled={
								courseQuery?.data?.meta?.current_page ===
								courseQuery?.data?.meta?.pages
							}
							aria-label="Next page"
							onClick={() =>
								setFilterParams({
									page:
										courseQuery?.data?.meta?.current_page <
										courseQuery?.data?.meta?.pages
											? courseQuery?.data?.meta?.current_page + 1
											: courseQuery?.data?.meta?.current_page,
									per_page: filterParams.per_page,
								})
							}
							icon={<FaChevronRight />}
						/>
					</ButtonGroup>
				</Center>
			)}
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
