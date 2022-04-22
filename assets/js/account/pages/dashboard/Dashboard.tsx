import {
	Alert,
	AlertIcon,
	Button,
	ButtonGroup,
	Heading,
	Icon,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Col, Row } from 'react-grid-system';
import { BsBook, BsBookHalf } from 'react-icons/bs';
import { HiAcademicCap } from 'react-icons/hi';
import { IoIosArrowForward } from 'react-icons/io';
import { useQuery } from 'react-query';
import { Link } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { CourseProgressSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { isEmpty } from '../../../back-end/utils/utils';
import CountBox from '../../components/CountBox';
import CourseGridItem from '../../components/CourseGridItem';
import routes from '../../constants/routes';
import { MyCoursesSchema } from '../../schemas';
import localized from '../../utils/global';

const Dashboard: React.FC = () => {
	const courseAPI = new API(urls.myCourses);
	const courseProgressAPI = new API(urls.courseProgress);

	const dashboardCourseQuery = useQuery('dashboardCourseQuery', () =>
		courseAPI.list()
	);

	const courseProgressQuery = useQuery('dashboardCourseProgress', () =>
		courseProgressAPI.list({ user_id: localized?.current_user_id })
	);

	const enrolledCoursesCount = courseProgressQuery?.data?.length;

	const inProgressCoursesCount = courseProgressQuery?.data?.filter(
		(course: CourseProgressSchema) => course.status === 'progress'
	).length;

	const completedCoursesCount = courseProgressQuery?.data?.filter(
		(course: CourseProgressSchema) => course.status === 'completed'
	).length;

	// Extracts started and inprogress courses from course progress
	const validCourses = courseProgressQuery?.data?.filter(
		(course: CourseProgressSchema) =>
			['started', 'progress'].includes(course.status)
	);

	// Adds started and inprogress courses id to an array
	const validCoursesIds = validCourses?.map(
		(course: CourseProgressSchema) => course.course_id
	);

	const coursesToContinue: MyCoursesSchema[] | undefined =
		dashboardCourseQuery?.data?.data?.filter((course: MyCoursesSchema) =>
			validCoursesIds?.includes(course.course.id)
		);

	if (dashboardCourseQuery.isSuccess && courseProgressQuery.isSuccess) {
		return (
			<>
				<Stack direction="column" spacing="10">
					<Row gutterWidth={30}>
						<Col sm={12} md={4}>
							<CountBox
								title={__('Enrolled Courses', 'masteriyo')}
								count={enrolledCoursesCount}
								icon={<Icon as={BsBook} fontSize="xl" />}
								colorScheme="cyan"
							/>
						</Col>
						<Col sm={12} md={4}>
							<CountBox
								title={__('In Progress Courses', 'masteriyo')}
								count={inProgressCoursesCount}
								icon={<Icon as={BsBookHalf} fontSize="xl" />}
								colorScheme="blue"
							/>
						</Col>
						<Col sm={12} md={4}>
							<CountBox
								title={__('Completed Courses', 'masteriyo')}
								count={completedCoursesCount}
								icon={<Icon as={HiAcademicCap} fontSize="2xl" />}
								colorScheme="green"
							/>
						</Col>
					</Row>

					<Stack direction="column" spacing="8">
						<Stack
							direction="row"
							spacing="4"
							justify="space-between"
							alignItems="center">
							<Stack direction="row">
								<Heading size="md">
									{__('Continue Studying', 'masteriyo')}
								</Heading>
							</Stack>
							<ButtonGroup>
								<Link to={routes.courses}>
									<Button
										rightIcon={
											<IoIosArrowForward size={15} color={'gray.500'} />
										}
										color="gray.500"
										size="sm"
										borderRadius="full"
										borderColor="gray.400"
										variant="outline">
										{__('SHOW ALL', 'masteriyo')}
									</Button>
								</Link>
							</ButtonGroup>
						</Stack>
						{isEmpty(coursesToContinue) ? (
							<Alert status="info">
								<AlertIcon />
								{__("You don't have any course in progress.", 'masteriyo')}
							</Alert>
						) : (
							coursesToContinue?.map((course) => (
								<CourseGridItem key={course.id} course={course} />
							))
						)}
					</Stack>
				</Stack>
			</>
		);
	}

	return <FullScreenLoader />;
};

export default Dashboard;
