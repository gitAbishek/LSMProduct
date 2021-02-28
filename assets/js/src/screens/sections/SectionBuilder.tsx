import { fetchCourse, fetchSections } from '../../utils/api';

import AddNewButton from 'Components/common/AddNewButton';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';
import Section from './components/Section';
import { __ } from '@wordpress/i18n';
import { useParams } from 'react-router-dom';
import { useQuery } from 'react-query';

const SectionBuilder = () => {
	const { courseId }: any = useParams();
	const courseQuery = useQuery([`builderCourse`, courseId], () =>
		fetchCourse(courseId)
	);

	const sectionQuery = useQuery(['buidlerSection', courseId], () =>
		fetchSections(courseId)
	);

	if (courseQuery.isLoading) {
		return <h1>Loading</h1>;
	}

	return (
		<>
			<MainToolbar />
			<div className="mto-container mto-mx-auto">
				{sectionQuery?.data?.map((section: any, index: number) => (
					<Section
						key={section.id}
						id={section.id}
						title={section.name}
						index={index}
						courseId={courseId}
					/>
				))}
				<div className="mto-flex mto-justify-center mto-p-12">
					<AddNewButton>{__('Add New Section', 'masteriyo')}</AddNewButton>
				</div>
			</div>
		</>
	);
};

export default SectionBuilder;
