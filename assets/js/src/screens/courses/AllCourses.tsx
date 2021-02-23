import { Edit, Trash } from '../../assets/icons';
import { Link, useHistory } from 'react-router-dom';
import React, { Fragment, useState } from 'react';
import { deleteCourse, fetchCourses } from '../../utils/api';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import Icon from 'Components/common/Icon';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import { __ } from '@wordpress/i18n';

const AllCourses = () => {
	const { data: coursesData, refetch: refectCourses } = useQuery(
		'courseData',
		fetchCourses
	);
	const [showDeleteModal, setShowDeleteModal] = useState(false);
	const [removableCourse, setRemovableCourse] = useState(Object);

	const history = useHistory();

	const deleteMutation = useMutation((courseId) => deleteCourse(courseId), {
		onSuccess: () => {
			refectCourses();
		},
	});

	const onDeletePress = (courseId: any, courseName: string) => {
		setRemovableCourse({ id: courseId, name: courseName });
		setShowDeleteModal(true);
	};

	const onEditPress = (courseId: any) => {
		history.push(`/courses/edit/${courseId}`);
	};

	return (
		<Fragment>
			<MainToolbar />

			<MainLayout>
				<div className="mto-flex mto-justify-between mto-mb-10">
					<h1 className="mto-text-xl mto-m-0 mto-font-medium">
						{__('Courses', 'masteriyo')}
					</h1>
					<Button appearance="primary">
						<Link to="/courses/add-new-course">
							{__('Add New Course', 'masteriyo')}
						</Link>
					</Button>
				</div>
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
							<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
								{__('Actions', 'masteriyo')}
							</th>
						</tr>
					</thead>
					<tbody className="mto-bg-white mto-divide-y mto-divide-gray-200">
						{coursesData?.map((course: any) => (
							<tr key={course.id}>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500">
									<Link to={`/courses/${course.id}`}>{course.name}</Link>
								</td>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500"></td>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500">
									{course.price}
								</td>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500">
									<ul className="mto-flex mto-list-none mto-text-base">
										<li
											onClick={() => onEditPress(course.id)}
											className="mto-text-gray-800 hover:mto-text-blue-500 mto-cursor-pointer mto-mr-4">
											<Icon icon={<Edit />} />
										</li>
										<li
											onClick={() => onDeletePress(course.id, course.name)}
											className="mto-text-gray-800 hover:mto-text-red-600 mto-cursor-pointer mto-mr-4">
											<Icon icon={<Trash />} />
										</li>
									</ul>
								</td>
							</tr>
						))}
					</tbody>
				</table>
				{showDeleteModal && removableCourse && (
					<div className="mto-fixed mto-z-10 mto-overflow-y-auto mto-bg-gray-600 mto-bg-opacity-80 mto-shadow-lg mto-inset-0">
						<div className="mto-flex mto-items-center mto-justify-center mto-w-full mto-h-full">
							<div className="mto-bg-white mto-rounded-md mto-shadow-xl mto-overflow-hidden mto-w-3/12">
								<div className="mto-p-8 mto-flex">
									<div className="mto-rounded-full mto-flex-shrink-0 mto-bg-red-200 mto-w-10 mto-h-10 mto-flex mto-items-center mto-justify-center mto-text-xl mto-text-red-600">
										<Icon icon={<Trash />} />
									</div>
									<div>
										<h3 className="mto-ml-4 mto-text-xl mto-mb-3">
											{__( 'Delete Course', 'masteriyo' )} {removableCourse.name}
										</h3>
										<p className="mto-ml-4 mto-text-md mto-text-gray-500">
											{__( "Are you sure want to delete this course. You won't be able to recover it back", 'masteriyo' )}
										</p>
									</div>
								</div>
								<footer className="mto-px-8 mto-py-4 mto-flex mto-justify-end mto-bg-gray-100">
									<Button onClick={() => setShowDeleteModal(false)}>
										{__( 'Cancel', 'masteriyo' )}
									</Button>
									<Button
										appearance="secondary"
										className="mto-ml-3"
										onClick={() => {
											deleteMutation.mutate(removableCourse.id);
											setShowDeleteModal(false);
										}}>
										{__( 'Delete', 'masteriyo' )}
									</Button>
								</footer>
							</div>
						</div>
					</div>
				)}
			</MainLayout>
		</Fragment>
	);
};

export default AllCourses;
