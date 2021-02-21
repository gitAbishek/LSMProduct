import React, { Fragment, useState } from 'react';
import { dropCourse, fetchCourses } from '../../utils/api';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import Icon from 'Components/common/Icon';
import { Link } from 'react-router-dom';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import { Trash } from '../../assets/icons';

const AllCourses = () => {
	const { data: coursesData, isLoading, refetch: refectCourses } = useQuery(
		'courseData',
		fetchCourses
	);
	const [showDeleteModal, setShowDeleteModal] = useState(false);
	const [removableCourse, setRemovableCourse] = useState(Object);

	const deleteMutation = useMutation((courseId) => dropCourse(courseId), {
		onSuccess: () => {
			refectCourses();
		},
	});

	const onDeletePress = (courseId: any, courseName: string) => {
		setRemovableCourse({ id: courseId, name: courseName });
		setShowDeleteModal(true);
	};

	return (
		<Fragment>
			<MainToolbar />

			<MainLayout>
				<div className="mto-flex mto-justify-between mto-mb-10">
					<h1 className="mto-text-xl mto-m-0 mto-font-medium">Courses</h1>
					<Button appearance="primary">Add New Course</Button>
				</div>
				<table className="mto-min-w-full mto-divide-y mto-divide-gray-200 mto-text-gray-700">
					<thead>
						<tr>
							<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
								id
							</th>
							<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
								Title
							</th>
							<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
								Categories
							</th>
							<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
								Price
							</th>
							<th className="mto-px-6 mto-py-3 mto-text-left mto-text-xs mto-font-medium mto-text-gray-500 mto-uppercase mto-tracking-wider">
								Actions
							</th>
						</tr>
					</thead>
					<tbody className="mto-bg-white mto-divide-y mto-divide-gray-200">
						{coursesData?.map((course: any) => (
							<tr key={course.id}>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500">
									{course.id}
								</td>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500">
									<Link to={`/courses/${course.id}`}>{course.name}</Link>
								</td>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500"></td>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500">
									{course.price}
								</td>
								<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500">
									<ul className="mto-flex mto-list-none mto-text-base mto-justify-center">
										<li
											onClick={() => onDeletePress(course.id, course.name)}
											className="mto-text-gray-800 hover:mto-text-red-600 mto-cursor-pointer ">
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
											Delete Course {removableCourse.name}
										</h3>
										<p className="mto-ml-4 mto-text-md mto-text-gray-500">
											Are you sure want to delete this course. You won't be able
											to recover it back
										</p>
									</div>
								</div>
								<footer className="mto-px-8 mto-py-4 mto-flex mto-justify-end mto-bg-gray-100">
									<Button onClick={() => setShowDeleteModal(false)}>
										Cancel
									</Button>
									<Button
										appearance="secondary"
										className="mto-ml-3"
										onClick={() => {
											deleteMutation.mutate(removableCourse.id);
											setShowDeleteModal(false);
										}}>
										Delete
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
