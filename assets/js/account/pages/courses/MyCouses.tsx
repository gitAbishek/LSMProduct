import { Heading, SimpleGrid, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import CourseItem from '../../components/CourseItem';
import { MyCoursesSchema } from '../../schemas';

const MyCourses: React.FC = () => {
	const courseAPI = new API(urls.myCourses);

	const myCourseQuery = useQuery(['myCourses'], () => courseAPI.list());

	console.log(myCourseQuery?.data);

	if (myCourseQuery.isSuccess) {
		return (
			<Stack direction="column" spacing="8">
				<Heading as="h1" size="xl">
					{__('My Courses', 'masteriyo')}
				</Heading>
				<SimpleGrid columns={3} spacing="6">
					{myCourseQuery?.data?.data.map((myCourse: MyCoursesSchema) => {
						return <CourseItem key={myCourse.id} courseData={myCourse} />;
					})}
				</SimpleGrid>
			</Stack>
		);
	}
	return <FullScreenLoader />;
};

export default MyCourses;
