import { addSection, fetchCourse, fetchSections } from '../../utils/api';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import AddNewButton from 'Components/common/AddNewButton';
import Loader from 'react-loader-spinner';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';
import Section from './components/Section';
import Spinner from 'Components/common/Spinner';
import { __ } from '@wordpress/i18n';
import { useParams } from 'react-router-dom';

const SectionBuilder = () => {
	const { courseId }: any = useParams();
	const queryClient = useQueryClient();

	const courseQuery = useQuery(['builderCourse', courseId], () =>
		fetchCourse(courseId)
	);

	const sectionQuery = useQuery(['builderSections', courseId], () =>
		fetchSections(courseId)
	);

	const addSectionMutation = useMutation(
		(newSection: any) => addSection(newSection),
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
			<MainToolbar />
			<div className="mto-container mto-mx-auto">
				{sectionQuery.isLoading ? (
					<Spinner />
				) : (
					sectionQuery?.data?.map((section: any, index: number) => (
						<Section
							key={section.id}
							id={section.id}
							name={section.name}
							description={section.description}
							courseId={courseId}
						/>
					))
				)}
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
