import { Edit, Trash } from '../../assets/icons';
import { Link, useHistory } from 'react-router-dom';
import React, { Fragment, useState } from 'react';
import { deleteCourse, fetchCourses } from '../../utils/api';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import Icon from 'Components/common/Icon';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Modal from 'Components/common/Modal';
import ModalBody from 'Components/common/ModalBody';
import ModalFooter from 'Components/common/ModalFooter';
import ModalHeader from 'Components/common/ModalHeader';
import Spinner from 'Components/common/Spinner';
import { __ } from '@wordpress/i18n';

const AllCourses = () => {
	const courseQuery = useQuery('courseData', fetchCourses);
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
								<tr key={course.id}>
									<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base">
										<Link to={`/builder/${course.id}`}>{course.name}</Link>
									</td>
									<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base"></td>
									<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base">
										{course.price}
									</td>
									<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base">
										<ul className="mto-flex mto-list-none mto-text-base mto-justify-end">
											<li
												onClick={() => onEditPress(course.id)}
												className="mto-text-gray-800 hover:mto-text-blue-500 mto-cursor-pointer mto-ml-4">
												<Icon icon={<Edit />} />
											</li>
											<li
												onClick={() => onDeletePress(course.id, course.name)}
												className="mto-text-gray-800 hover:mto-text-red-600 mto-cursor-pointer mto-ml-4">
												<Icon icon={<Trash />} />
											</li>
										</ul>
									</td>
								</tr>
							))}
						</tbody>
					</table>
				)}
				<Modal
					isOpen={showDeleteModal}
					onClose={() => setShowDeleteModal(false)}>
					<ModalHeader>
						{__('Delete Course', 'masteriyo')} {removableCourse.name}
					</ModalHeader>
					<ModalBody>
						<p className="mto-text-md mto-text-gray-500">
							{__(
								"Are you sure want to delete this course. You won't be able to recover it back",
								'masteriyo'
							)}
						</p>
					</ModalBody>
					<ModalFooter>
						<Button
							className="mto-w-full sm:mto-w-auto"
							onClick={() => setShowDeleteModal(false)}>
							{__('Cancel', 'masteriyo')}
						</Button>
						<Button
							layout="accent"
							className="mto-w-full sm:mto-w-auto"
							onClick={() => {
								deleteMutation.mutate(removableCourse.id);
								setShowDeleteModal(false);
							}}>
							{__('Delete', 'masteriyo')}
						</Button>
					</ModalFooter>
				</Modal>
			</MainLayout>
		</Fragment>
	);
};

export default AllCourses;
