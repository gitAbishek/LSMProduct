import { Button, ButtonGroup, Heading, Icon, Stack } from '@chakra-ui/react';
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
import CountBox from '../../components/CountBox';
import CourseGridItem from '../../components/CourseGridItem';
import routes from '../../constants/routes';
import { MyCoursesSchema } from '../../schemas';

const Dashboard: React.FC = () => {
	const courseAPI = new API(urls.myCourses);
	const courseProgressAPI = new API(urls.courseProgress);

	const dashboardCourseQuery = useQuery('dashboardCourseQuery', () =>
		courseAPI.list()
	);

	const courseProgressQuery = useQuery('dashboardCourseProgress', () =>
		courseProgressAPI.list()
	);

	const enrolledCoursesCount = courseProgressQuery?.data?.length;

	const inProgressCoursesCount = courseProgressQuery?.data?.filter(
		(course: CourseProgressSchema) => course.status === 'progress'
	).length;

	const completedCoursesCount = courseProgressQuery?.data?.filter(
		(course: CourseProgressSchema) => course.status === 'completed'
	).length;

	const startedCoursesCount = courseProgressQuery?.data?.filter(
		(course: CourseProgressSchema) => course.status === 'started'
	).length;

	console.log(
		inProgressCoursesCount,
		completedCoursesCount,
		startedCoursesCount
	);

	console.log(courseProgressQuery?.data);

	if (dashboardCourseQuery.isSuccess) {
		return (
			<>
				<Stack direction="column" spacing="10">
					<Row gutterWidth={30}>
						<Col sm={12} md={4}>
							<CountBox
								title="Enrolled Courses"
								count={enrolledCoursesCount}
								icon={<Icon as={BsBook} fontSize="xl" />}
								colorScheme="cyan"
							/>
						</Col>
						<Col sm={12} md={4}>
							<CountBox
								title="In Progress Courses"
								count={inProgressCoursesCount}
								icon={<Icon as={BsBookHalf} fontSize="xl" />}
								colorScheme="blue"
							/>
						</Col>
						<Col sm={12} md={4}>
							<CountBox
								title="Completed Courses"
								count={enrolledCoursesCount}
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
									{__(' Continue Studying ', 'masteriyo')}
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
						{dashboardCourseQuery?.data?.data?.map(
							(course: MyCoursesSchema) => (
								<CourseGridItem key={course.id} course={course} />
							)
						)}
					</Stack>
				</Stack>
			</>
		);
	}

	return <FullScreenLoader />;
};

export default Dashboard;
