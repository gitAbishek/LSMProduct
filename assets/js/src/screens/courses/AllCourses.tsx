import { Edit, Trash } from '../../assets/icons';
import { Link, useHistory } from 'react-router-dom';
import React, { Fragment, useState } from 'react';
import { deleteCourse, fetchCourses } from '../../utils/api';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import Button from 'Components/common/Button';
import CourseList from './components/CourseList';
import DeleteModal from 'Components/layout/DeleteModal';
import Icon from 'Components/common/Icon';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Spinner from 'Components/common/Spinner';
import { __ } from '@wordpress/i18n';

const AllCourses = () => {
	const courseQuery = useQuery('courseList', fetchCourses);

	return (
		<Fragment>
			<MainToolbar />

			<MainLayout>
				<div className="mto-flex mto-justify-between mto-mb-10">
					<h1 className="mto-text-xl mto-m-0 mto-font-medium">
						{__('Courses', 'masteriyo')}
					</h1>
					<Button layout="primary">
						<Link to="/courses/add-new-course">
							{__('Add New Course', 'masteriyo')}
						</Link>
					</Button>
				</div>
				{courseQuery.isLoading ? (
					<Spinner />
				) : (
					<table className="mto-min-w-full mto-divide-y mto-divide-gray-200 mto-text-gray-700">
						<thead>
							<tr>
								<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
									{__('Title', 'masteriyo')}
								</th>
								<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
									{__('Categories', 'masteriyo')}
								</th>
								<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
									{__('Price', 'masteriyo')}
								</th>
								<th className="mto-px-6 mto-py-3 mto-text-right mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
									{__('Actions', 'masteriyo')}
								</th>
							</tr>
						</thead>
						<tbody className="mto-bg-white mto-divide-y mto-divide-gray-200">
							{courseQuery?.data?.map((course: any) => (
								<CourseList
									id={course.id}
									name={course.name}
									price={course.price}
									categories={course.categories}
									key={course.id}
								/>
							))}
						</tbody>
					</table>
				)}
			</MainLayout>
		</Fragment>
	);
};

export default AllCourses;
