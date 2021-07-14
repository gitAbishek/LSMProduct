import React from 'react';
import { useQuery } from 'react-query';
import { useHistory, useLocation, useParams } from 'react-router-dom';
import FullScreenLoader from '../../back-end/components/layout/FullScreenLoader';
import urls from '../../back-end/constants/urls';
import API from '../../back-end/utils/api';
import InteractiveRouter from '../router/InteractiveRouter';
import { CourseProgressMap } from '../schemas';
import { getNavigationRoute } from './FloatingNavigation';
import Header from './Header';
import Sidebar from './Sidebar';

const Interactive: React.FC = () => {
	const { courseId }: any = useParams();
	const history = useHistory();
	const location = useLocation();
	const progressAPI = new API(urls.courseProgress);

	const courseProgressQuery = useQuery<CourseProgressMap>(
		[`courseProgress${courseId}`, courseId],
		() => progressAPI.store({ course_id: courseId }),
		{
			enabled: !!courseId,
			onSuccess: (data: any) => {
				try {
					if (location.pathname === `/course/${courseId}`) {
						history.push(
							getNavigationRoute(
								data.items[0].contents[0].item_id,
								data.items[0].contents[0].item_type,
								courseId
							)
						);
					}
				} catch (error) {}
			},
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
