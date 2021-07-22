import React from 'react';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import urls from '../../constants/urls';
import CourseRouter from '../../router/CourseRouter';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';

const Course: React.FC = () => {
	const { courseId }: any = useParams();
	const courseAPI = new API(urls.courses);
	const courseQuery = useQuery<CourseDataMap>(
		[`courses${courseId}`, courseId],
		() => courseAPI.get(courseId)
	);

	if (courseQuery.isSuccess) {
		return <CourseRouter />;
	}

	return <FullScreenLoader />;
};

export default Course;
