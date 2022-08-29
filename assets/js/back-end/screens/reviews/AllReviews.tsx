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
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { BiBook, BiTrash } from 'react-icons/bi';
import { FaHandHoldingWater } from 'react-icons/fa';
import { FcApproval } from 'react-icons/fc';
import { IconType } from 'react-icons/lib';
import { RiSpam2Fill } from 'react-icons/ri';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles } from '../../config/styles';
import urls from '../../constants/urls';
import { ReviewStatus } from '../../enums/Enum';
import { CourseReviewResponse, CourseReviewSchema } from '../../schemas';
import { SkeletonReviewsList } from '../../skeleton';
import API from '../../utils/api';
import { deepClean, deepMerge, isEmpty } from '../../utils/utils';
import ReviewFilter from './components/ReviewFilter';
import ReviewList from './components/ReviewList';

interface Status {
	status: string;
	icon: IconType;
	name: string;
}

const statusList: Status[] = [
	{
		status: 'all',
		icon: BiBook,
		name: __('All Reviews', 'masteriyo'),
	},
	{
		status: ReviewStatus.approve,
		icon: FcApproval,
		name: __('Approved', 'masteriyo'),
	},
	{
		status: ReviewStatus.hold,
		icon: FaHandHoldingWater,
		name: __('On Hold', 'masteriyo'),
	},
	{
		status: ReviewStatus.spam,
		icon: RiSpam2Fill,
		name: __('Spam', 'masteriyo'),
	},
	{
		status: ReviewStatus.trash,
		icon: BiTrash,
		name: __('Trash', 'masteriyo'),
	},
];

interface FilterParams {
	course?: number | string;
	user?: number | string;
	search?: string;
	status?: string;
	per_page?: number;
	page?: number;
	parent: number;
}

interface ReviewCount {
	all: number | undefined;
	approve: number | undefined;
	hold: number | undefined;
	spam: number | undefined;
	trash: number | undefined;
}

const AllReviews = () => {
	const reviewsAPI = new API(urls.reviews);
	const toast = useToast();
	const [filterParams, setFilterParams] = useState<FilterParams>({ parent: 0 });
	const [deleteReviewId, setDeleteReviewId] = useState<number>();
	const [reviewStatus, setReviewStatus] = useState<string>('all');
	const [reviewStatusCount, setReviewStatusCount] = useState<ReviewCount>({
		all: undefined,
		approve: undefined,
		hold: undefined,
		spam: undefined,
		trash: undefined,
	});

	const queryClient = useQueryClient();
	const { onClose, onOpen, isOpen } = useDisclosure();
	const cancelRef = useRef<any>();

	const reviewQuery = useQuery<CourseReviewResponse>(
		['reviewList', filterParams],
		() => reviewsAPI.list(filterParams),
		{
			onSuccess: (data: CourseReviewResponse) => {
				setReviewStatusCount({
					all: data?.meta?.reviews_count?.all,
					approve: data?.meta?.reviews_count?.approve,
					hold: data?.meta?.reviews_count?.hold,
					spam: data?.meta?.reviews_count?.spam,
					trash: data?.meta?.reviews_count?.trash,
				});
			},
		}
	);

	const onDeletePress = (reviewId: number) => {
		onOpen();
		setDeleteReviewId(reviewId);
	};

	const deleteReviewMutation = useMutation(
		(id: number) =>
			reviewsAPI.delete(id, { force_delete: true, children: true }),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('reviewList');
				onClose();
			},
		}
	);

	const onDeleteConfirm = () => {
		deleteReviewId ? deleteReviewMutation.mutate(deleteReviewId) : null;
	};

	const trashReviewMutation = useMutation(
		(id: number) => reviewsAPI.delete(id),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('reviewList');
				toast({
					title: __('Review Trashed', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const onTrashPress = (reviewId: number) => {
		reviewId ? trashReviewMutation.mutate(reviewId) : null;
	};

	const onReviewStatusChange = (status: string) => {
		setReviewStatus(status);
		setFilterParams(
			deepMerge(filterParams, {
				status,
			})
		);
	};

	const updateReviewMutation = useMutation(
		(data: CourseReviewSchema) => reviewsAPI.update(data.id, data),
		{
			onSuccess: (responseData) => {
				queryClient.invalidateQueries('reviewList');
				toast({
					title: __(
						`Review ${
							responseData.status === ReviewStatus.spam ? 'Spammend' : 'Holded'
						}`,
						'masteriyo'
					),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const restoreReviewMutation = useMutation(
		(id: number) => reviewsAPI.restore(id),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('reviewList');
				toast({
					title: __(`Review Restored`, 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const onRestorePress = (reviewId: number) => {
		reviewId ? restoreReviewMutation.mutate(reviewId) : null;
	};

	const onReviewUpdate = (data: CourseReviewSchema, status: string) => {
		updateReviewMutation.mutate(deepClean(deepMerge(data, { status })));
	};

	const reviewStatusBtnStyles = {
		mr: '10',
		py: '6',
		d: 'flex',
		gap: 1,
		justifyContent: 'flex-start',
		alignItems: 'center',
		fontWeight: 'medium',
		fontSize: ['xs', null, 'sm'],
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header>
				<List d={['none', 'none', 'flex']} flexDirection="row">
					{statusList.map((button: Status) => (
						<ListItem key={button.status} mb="0">
							<Button
								color="gray.600"
								variant="link"
								sx={reviewStatusBtnStyles}
								_active={navActiveStyles}
								isActive={button.status === reviewStatus}
								_hover={{ color: 'primary.500' }}
								onClick={() => onReviewStatusChange(button.status)}>
								<ListIcon as={button.icon} />
								{button.name}
								{reviewStatusCount[button.status] === undefined ? (
									<SkeletonCircle
										size="3"
										w="17px"
										ml="1"
										mb="1"
										rounded="sm"
									/>
								) : (
									<Badge color="inherit">
										{reviewStatusCount[button.status]}
									</Badge>
								)}
							</Button>
						</ListItem>
					))}
				</List>
				{/* Start Mobile View */}
				<Stack direction="column" display={{ md: 'none' }}>
					{statusList.map((button: Status) => (
						<Stack key={button.status}>
							<Button
								color="gray.600"
								variant="link"
								sx={reviewStatusBtnStyles}
								_active={navActiveStyles}
								isActive={button.status === reviewStatus}
								_hover={{ color: 'primary.500' }}
								onClick={() => onReviewStatusChange(button.status)}>
								<Icon as={button.icon} />
								{button.name}
								{reviewStatusCount[button.status] !== undefined ? (
									<Badge color="inherit">
										{reviewStatusCount[button.status]}
									</Badge>
								) : (
									<SkeletonCircle size="4" ml="1" mb="1" />
								)}
							</Button>
						</Stack>
					))}
				</Stack>
				{/* End Mobile View */}
			</Header>
			<Container maxW="container.xl">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="10">
						<ReviewFilter
							setFilterParams={setFilterParams}
							filterParams={filterParams}
						/>
						<Stack direction="column" spacing="10">
							<Table>
								<Thead>
									<Tr>
										<Th>{__('Title', 'masteriyo')}</Th>
										<Th>{__('Course', 'masteriyo')}</Th>
										<Th>{__('Status', 'masteriyo')}</Th>
										<Th>{__('Author', 'masteriyo')}</Th>
										<Th>{__('Replies', 'masteriyo')}</Th>
										<Th>{__('Date', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{reviewQuery.isLoading || !reviewQuery.isFetched ? (
										<SkeletonReviewsList />
									) : reviewQuery.isSuccess &&
									  isEmpty(reviewQuery?.data?.data) ? (
										<EmptyInfo message={__('No reviews found.', 'masteriyo')} />
									) : (
										reviewQuery?.data?.data?.map(
											(review: CourseReviewSchema) => (
												<ReviewList
													id={review.id}
													key={review.id}
													title={review.title}
													author={{
														id: review.author_id,
														display_name: review.author_name,
														avatar_url: review.author_avatar_url,
													}}
													replies_count={review.replies_count}
													course={review?.course?.name}
													rating={review.rating}
													status={review.status}
													createdAt={review.date_created}
													onDeletePress={() => onDeletePress(review.id)}
													onTrashPress={() => onTrashPress(review.id)}
													onRestorePress={() => onRestorePress(review.id)}
													onSpamPress={() =>
														onReviewUpdate(review, ReviewStatus.spam)
													}
													onUnSpamPress={() =>
														onReviewUpdate(review, ReviewStatus.hold)
													}
												/>
											)
										)
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{reviewQuery.isSuccess && !isEmpty(reviewQuery?.data) && (
					<MasteriyoPagination
						metaData={reviewQuery?.data?.meta}
						setFilterParams={setFilterParams}
						perPageText={__('Reviews Per Page:', 'masteriyo')}
						extraFilterParams={{
							search: filterParams?.search,
							course: filterParams?.course,
							user: filterParams?.user,
							status: filterParams?.status,
							parent: 0,
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
							{__('Deleting Review', 'masteriyo')} {name}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__(
								"Are you sure? All the replies related to this review will also be deleted and you can't restore.",
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
									isLoading={deleteReviewMutation.isLoading}
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

export default AllReviews;
