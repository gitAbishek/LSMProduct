import {
	Box,
	Button,
	ButtonGroup,
	Center,
	Heading,
	Icon,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { HiOutlineAcademicCap } from 'react-icons/hi';
import { IoIosArrowForward } from 'react-icons/io';
import { useQuery } from 'react-query';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import CourseGridItem from '../../components/CourseGridItem';
import { MyCoursesSchema } from '../../schemas';

const Dashboard: React.FC = () => {
	const courseAPI = new API(urls.myCourses);
	const dashboardCourseQuery = useQuery('dashboardCourseQuery', () =>
		courseAPI.list()
	);
	if (dashboardCourseQuery.isSuccess) {
		return (
			<>
				<Stack direction="column" spacing="10">
					<Stack direction="row" spacing="8">
						<Stack direction="row" spacing="8" justify="space-around">
							{dashboardCourseQuery?.data?.data && (
								<Box w="xs" borderWidth="1px" borderColor="gray.100">
									<Stack p="6">
										<Stack direction="row" spacing="4">
											<Stack
												direction="row"
												spacing="4"
												align="center"
												justify="space-between">
												<Center
													bg="green.10"
													w="10"
													h="10"
													rounded="full"
													color="green.400"
													fontSize="lg">
													<Icon as={HiOutlineAcademicCap} />
												</Center>
												<Heading size="sm" color="blue.900">
													{__('In Progress', 'masteriyo')}
												</Heading>
											</Stack>
										</Stack>
										<Stack direction="column">
											<Text fontWeight="bold" color="blue.900" fontSize="xl">
												{dashboardCourseQuery?.data?.data?.length}
											</Text>
											<Text color="gray.700">{__('Courses', 'masteriyo')}</Text>
										</Stack>
									</Stack>
								</Box>
							)}
						</Stack>
					</Stack>

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
								<Button
									rightIcon={<IoIosArrowForward size={15} color={'gray.500'} />}
									color="gray.500"
									size="sm"
									borderRadius="full"
									borderColor="gray.400"
									variant="outline">
									{__('SHOW ALL', 'masteriyo')}
								</Button>
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
