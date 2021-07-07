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
	const progressAPI = new API(urls.interactiveProgress);

	const { data, isSuccess } = useQuery<CourseProgressMap>(
		[`courseProgress${courseId}`, courseId],
		() => progressAPI.store({ course_id: courseId }),
		{
			enabled: !!courseId,
		}
	);
	console.log(data);

	if (isSuccess) {
		return (
			<>
				<Header summary={data.summary} />

				<Sidebar />
				<InteractiveRouter />
			</>
		);
	}

	return <FullScreenLoader />;
};

export default Interactive;
