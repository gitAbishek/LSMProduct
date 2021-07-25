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
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import Header from '../../components/layout/Header';
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
}

const AllCourses = () => {
	const courseAPI = new API(urls.courses);
	const [filterParams, setFilterParams] = useState<FilterParams>({});
	const [deleteCourseId, setDeleteCourseId] = useState<number>();

	const queryClient = useQueryClient();
	const { onClose, onOpen, isOpen } = useDisclosure();
	const courseQuery = useQuery(['courseList', filterParams], () =>
		courseAPI.list(filterParams)
	);
	const cancelRef = React.useRef<any>();

	const tableStyles = {
		th: {
			pb: '6',
			borderBottom: 'none',
		},
		'tr:nth-of-type(2n+1) td': {
			bg: '#f8f9fa',
		},

		tr: {
			'th, td': {
				':first-of-type': {
					pl: '12',
				},
				':last-child': {
					pr: '6',
				},
			},
		},
		td: {
			py: '3',
			borderBottom: 'none',
		},
	};

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
			<Header hideCoursesMenu />
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
										courseQuery.data.map((course: any) => (
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
											/>
										))}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
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
