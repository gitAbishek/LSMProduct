import React from 'react';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../back-end/components/layout/FullScreenLoader';
import urls from '../../back-end/constants/urls';
import API from '../../back-end/utils/api';
import InteractiveRouter from '../router/InteractiveRouter';
import { CourseProgressMap } from '../schemas';
import Header from './Header';
import Sidebar from './Sidebar';

const Interactive: React.FC = () => {
	const { courseId }: any = useParams();
	const progressAPI = new API(urls.courseProgress);

	const courseProgressQuery = useQuery<CourseProgressMap>(
		[`courseProgress${courseId}`, courseId],
		() => progressAPI.store({ course_id: courseId }),
		{
			enabled: !!courseId,
		}
	);

	if (courseProgressQuery.isSuccess) {
		return (
			<>
				<Header summary={courseProgressQuery.data.summary} />
				<Sidebar
					items={courseProgressQuery.data.items}
					name={courseProgressQuery.data.name}
				/>
				<InteractiveRouter />
			</>
		);
	}

	return <FullScreenLoader />;
};

export default Interactive;
