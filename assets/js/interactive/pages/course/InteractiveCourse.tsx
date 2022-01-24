import React from 'react';
import { useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import { getNavigationRoute } from '../../../back-end/utils/nav';
import { CourseProgressMap } from '../../schemas';
import FourOFour from '../not-found/FourOFour';

const InteractiveCourse: React.FC = () => {
	const { courseId }: any = useParams();
	const history = useHistory();
	const progressAPI = new API(urls.courseProgress);

	const courseProgressQuery = useQuery<CourseProgressMap>(
		[`courseProgress${courseId}`, courseId],
		() => progressAPI.store({ course_id: courseId })
	);

	if (courseProgressQuery.isSuccess) {
		try {
			history.push(
				getNavigationRoute(
					courseProgressQuery?.data?.items[0]?.contents[0]?.item_id,
					courseProgressQuery?.data?.items[0]?.contents[0]?.item_type,
					courseId
				)
			);
		} catch (error) {}
	}

	if (courseProgressQuery.isError) {
		return <FourOFour />;
	}

	return <FullScreenLoader />;
};

export default InteractiveCourse;
