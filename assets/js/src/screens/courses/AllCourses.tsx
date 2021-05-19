import {
	Box,
	Button,
	Container,
	Flex,
	Heading,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Header from 'Components/layout/Header';
import React from 'react';
import { useQuery } from 'react-query';

import urls from '../../constants/urls';
import { SkeletonCourseList } from '../../skeleton';
import API from '../../utils/api';
import CourseList from './components/CourseList';

const AllCourses = () => {
	const courseAPI = new API(urls.courses);
	const courseQuery = useQuery('courseList', () => courseAPI.list());
	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<Box bg="white" p="12" shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<Flex justify="space-between" aling="center">
							<Heading as="h1" size="lg">
								{__('Courses', 'masteriyo')}
							</Heading>
						</Flex>

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
									courseQuery.data.map((course: any) => (
										<CourseList
											id={course.id}
											name={course.name}
											price={course.price}
											categories={course.categories}
											key={course.id}
											previewLink={course.permalink}
										/>
									))}
							</Tbody>
						</Table>
					</Stack>
				</Box>
			</Container>
		</Stack>
	);
};

export default AllCourses;
