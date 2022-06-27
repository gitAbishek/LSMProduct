import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Badge,
	Box,
	Button,
	ButtonGroup,
	Container,
	Icon,
	List,
	ListIcon,
	ListItem,
	SkeletonCircle,
	Stack,
	Text,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import {
	BiBook,
	BiBookBookmark,
	BiBookOpen,
	BiPlus,
	BiTrash,
} from 'react-icons/bi';
import { IconType } from 'react-icons/lib';
import { MdOutlineArrowDropDown, MdOutlineArrowDropUp } from 'react-icons/md';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseStatus } from '../../enums/Enum';
import { SkeletonCourseList } from '../../skeleton';
import API from '../../utils/api';
import { deepMerge, isEmpty } from '../../utils/utils';
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
	orderby: string;
	order: 'asc' | 'desc';
}

const AllCourses = () => {
	const courseAPI = new API(urls.courses);
	const history = useHistory();
	const toast = useToast();
	const [filterParams, setFilterParams] = useState<FilterParams>({
		order: 'desc',
		orderby: 'id',
	});
	const [deleteCourseId, setDeleteCourseId] = useState<number>();
	const queryClient = useQueryClient();
	const { onClose, onOpen, isOpen } = useDisclosure();
	const [active, setActive] = useState('any');
	const [courseStatusCount, setCourseStatusCount] = useState({
		any: null,
		publish: null,
		draft: null,
		trash: null,
	});

	const courseQuery = useQuery(
		['courseList', filterParams],
		() => courseAPI.list(filterParams),
		{
			onSuccess: (data: any) => {
				setCourseStatusCount({
					any: data?.meta?.courses_count?.any,
					publish: data?.meta?.courses_count?.publish,
					draft: data?.meta?.courses_count?.draft,
					trash: data?.meta?.courses_count?.trash,
				});
			},
			keepPreviousData: true,
		}
	);

	const cancelRef = React.useRef<any>();

	const deleteCourse = useMutation(
		(id: number) => courseAPI.delete(id, { force: true, children: true }),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('courseList');
				onClose();
			},
		}
	);

	const restoreCourse = useMutation((id: number) => courseAPI.restore(id), {
		onSuccess: () => {
			toast({
				title: __('Course Restored', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			queryClient.invalidateQueries('courseList');
		},
	});

	const trashCourse = useMutation((id: number) => courseAPI.delete(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('courseList');
			toast({
				title: __('Course Trashed', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
		},
	});

	const onTrashPress = (courseId: number) => {
		courseId && trashCourse.mutate(courseId);
	};

	const onDeletePress = (courseId: number) => {
		onOpen();
		setDeleteCourseId(courseId);
	};

	const onDeleteConfirm = () => {
		deleteCourseId ? deleteCourse.mutate(deleteCourseId) : null;
	};

	const onRestorePress = (courseId: number) => {
		courseId ? restoreCourse.mutate(courseId) : null;
	};

	const onChangeCourseStatus = (status: string) => {
		setActive(status);
		setFilterParams(
			deepMerge(filterParams, {
				status: status,
			})
		);
	};

	const courseStatusBtnStyles = {
		mr: '10',
		py: '6',
		d: 'flex',
		gap: 1,
		justifyContent: 'flex-start',
		alignItems: 'center',
		fontWeight: 'medium',
		fontSize: ['xs', null, 'sm'],
	};

	const courseStatusButton = (courseStatus: string, iconName: IconType) => {
		const statusList = {
			[CourseStatus.Any]: __('All Courses', 'masteriyo'),
			[CourseStatus.Publish]: __('Published', 'masteriyo'),
			[CourseStatus.Draft]: __('Draft', 'masteriyo'),
			[CourseStatus.Trash]: __('Trash', 'masteriyo'),
		};

		let buttonText: string = statusList[courseStatus]
			? statusList[courseStatus]
			: courseStatus[0].toUpperCase() + courseStatus.slice(1);

		const courseCount = courseStatusCount[courseStatus];

		return (
			<Button
				color="gray.600"
				variant="link"
				sx={courseStatusBtnStyles}
				_active={navActiveStyles}
				rounded="none"
				isActive={active === courseStatus}
				_hover={{ color: 'blue.500' }}
				onClick={() => onChangeCourseStatus(courseStatus)}>
				<ListIcon as={iconName} />
				{buttonText}
				{courseCount === null ? (
					<SkeletonCircle size="3" w="17px" ml="1" mb="1" rounded="sm" />
				) : (
					<Badge color="inherit">{courseCount}</Badge>
				)}
			</Button>
		);
	};

	const filterCoursesBy = (order: 'asc' | 'desc', orderBy: string) =>
		setFilterParams(
			deepMerge({
				...filterParams,
				order: order,
				orderby: orderBy,
			})
		);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header
				thirdBtn={{
					label: __('Add New Course', 'masteriyo'),
					action: () => history.push(routes.courses.add),
					icon: <Icon as={BiPlus} fontSize="md" />,
				}}>
				<List d="flex">
					<ListItem mb="0">{courseStatusButton('any', BiBook)}</ListItem>
					<ListItem mb="0">
						{courseStatusButton('publish', BiBookOpen)}
					</ListItem>
					<ListItem mb="0">
						{courseStatusButton('draft', BiBookBookmark)}
					</ListItem>
					<ListItem mb="0">{courseStatusButton('trash', BiTrash)}</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="10">
						<CourseFilter
							setFilterParams={setFilterParams}
							filterParams={filterParams}
							courseStatus={active}
						/>

						<Stack direction="column" spacing="8">
							<Table>
								<Thead>
									<Tr>
										<Th>
											<Stack direction="row" alignItems="center">
												<Text>{__('Title', 'masteriyo')}</Text>
												<Stack direction="column">
													{filterParams?.order === 'desc' ? (
														<Icon
															as={MdOutlineArrowDropUp}
															h={6}
															w={6}
															cursor="pointer"
															color="lightgray"
															transition="1s"
															_hover={{ color: 'black' }}
															onClick={() => filterCoursesBy('asc', 'title')}
														/>
													) : (
														<Icon
															as={MdOutlineArrowDropDown}
															h={6}
															w={6}
															cursor="pointer"
															color="lightgray"
															transition="1s"
															_hover={{ color: 'black' }}
															onClick={() => filterCoursesBy('desc', 'title')}
														/>
													)}
												</Stack>
											</Stack>
										</Th>
										<Th>{__('Categories', 'masteriyo')}</Th>
										<Th>{__('Instructor', 'masteriyo')}</Th>
										<Th>{__('Price', 'masteriyo')}</Th>
										<Th>{__('Date', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{courseQuery.isLoading || courseQuery.isFetching ? (
										<SkeletonCourseList />
									) : courseQuery.isSuccess &&
									  isEmpty(courseQuery?.data?.data) ? (
										<EmptyInfo message={__('No courses found.', 'masteriyo')} />
									) : (
										courseQuery?.data?.data?.map((course: any) => (
											<CourseList
												id={course?.id}
												name={course?.name}
												price={course?.price}
												categories={course?.categories}
												key={course?.id}
												createdOn={course?.date_created}
												permalink={course?.permalink}
												editPostLink={course?.edit_post_link}
												author={course?.author}
												onDeletePress={onDeletePress}
												onTrashPress={onTrashPress}
												onRestorePress={onRestorePress}
												status={course?.status}
											/>
										))
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{courseQuery.isSuccess && !isEmpty(courseQuery?.data?.data) && (
					<MasteriyoPagination
						metaData={courseQuery?.data?.meta}
						setFilterParams={setFilterParams}
						perPageText={__('Courses Per Page:', 'masteriyo')}
						extraFilterParams={{
							order: filterParams?.order,
							orderby: filterParams?.orderby,
							search: filterParams?.search,
							status: filterParams?.status,
							category: filterParams?.category,
							price_type: filterParams?.price,
						}}
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
							{__('Deleting Course', 'masteriyo')} {name}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__(
								'Are you sure? You can’t restore after deleting.',
								'masteriyo'
							)}
						</AlertDialogBody>
						<AlertDialogFooter>
							<ButtonGroup>
								<Button onClick={onClose} variant="outline" ref={cancelRef}>
									{__('Cancel', 'masteriyo')}
								</Button>
								<Button
									colorScheme="red"
									isLoading={deleteCourse.isLoading}
									onClick={onDeleteConfirm}>
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
