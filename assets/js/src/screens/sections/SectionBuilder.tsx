import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import Spinner from 'Components/common/Spinner';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Section from './components/Section';

const SectionBuilder = () => {
	const { courseId }: any = useParams();
	const queryClient = useQueryClient();
	const history = useHistory();
	const courseAPI = new API(urls.courses);
	const sectionAPI = new API(urls.sections);

	const courseQuery = useQuery(
		['builderCourse', courseId],
		() => courseAPI.get(courseId),
		{
			onError: () => {
				history.push(routes.courses.list);
			},
		}
	);

	courseQuery.isError && history.push('/');
	const sectionQuery = useQuery(['builderSections', courseId], () =>
		sectionAPI.get(courseId)
	);

	const addSectionMutation = useMutation(
		(newSection: any) => sectionAPI.store(newSection),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('builderSections');
			},
		}
	);

	const onAddNewSectionClick = () => {
		addSectionMutation.mutate({
			parent_id: courseId,
			name: 'New Section',
		});
	};

	return (
		<>
			<div className="mto-container mto-mx-auto">
				{sectionQuery?.data?.map((section: any, index: number) => (
					<Section
						key={section.id}
						id={section.id}
						name={section.name}
						description={section.description}
						courseId={courseId}
					/>
				))}
				<div className="mto-flex mto-justify-center mto-p-12">
					<AddNewButton onClick={onAddNewSectionClick}>
						{__('Add New Section', 'masteriyo')}
					</AddNewButton>
				</div>
			</div>
		</>
	);
};

export default SectionBuilder;
