import React from 'react';
import { useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const queryClient = useQueryClient();
	const history = useHistory();

	const courseAPI = new API(urls.courses);

	const courseQuery = useQuery(
		[`courses/${courseId}`, courseId],
		() => courseAPI.get(courseId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	if (courseQuery.isLoading) {
		return;
	}
};

export default Builder;
