import React, { Fragment, useEffect, useState } from 'react';
import { dropCourse, fetchCourses } from '../../utils/api';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import ContentLoader from 'react-content-loader';
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

	const deleteCourse = useMutation((courseId) => dropCourse(courseId), {
		onSuccess: () => {
			refectCourses();
		},
	});

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				{isLoading && (
					<ContentLoader
						backgroundColor="#f3f3f3"
						foregroundColor="#ecebeb"
						height={6}>
						<rect height="6" width="100%"></rect>
					</ContentLoader>
				)}
				<table className="masteriyo-min-w-full masteriyo-divide-y masteriyo-divide-gray-200">
					<thead>
						<tr>
							<th className="masteriyo-px-6 masteriyo-py-3 masteriyo-text-left masteriyo-text-xs masteriyo-font-medium masteriyo-text-gray-500 masteriyo-uppercase masteriyo-tracking-wider">
								id
							</th>
							<th className="masteriyo-px-6 masteriyo-py-3 masteriyo-text-left masteriyo-text-xs masteriyo-font-medium masteriyo-text-gray-500 masteriyo-uppercase masteriyo-tracking-wider">
								Title
							</th>
							<th className="masteriyo-px-6 masteriyo-py-3 masteriyo-text-left masteriyo-text-xs masteriyo-font-medium masteriyo-text-gray-500 masteriyo-uppercase masteriyo-tracking-wider">
								Categories
							</th>
							<th className="masteriyo-px-6 masteriyo-py-3 masteriyo-text-left masteriyo-text-xs masteriyo-font-medium masteriyo-text-gray-500 masteriyo-uppercase masteriyo-tracking-wider">
								Price
							</th>
							<th className="masteriyo-px-6 masteriyo-py-3 masteriyo-text-left masteriyo-text-xs masteriyo-font-medium masteriyo-text-gray-500 masteriyo-uppercase masteriyo-tracking-wider">
								Actions
							</th>
						</tr>
					</thead>
					<tbody className="masteriyo-bg-white masteriyo-divide-y masteriyo-divide-gray-200">
						{coursesData?.map((course: any) => (
							<tr key={course.id}>
								<td className="masteriyo-px-6 masteriyo-py-4 masteriyo-whitespace-nowrap">
									{course.id}
								</td>
								<td className="masteriyo-px-6 masteriyo-py-4 masteriyo-whitespace-nowrap">
									<Link to={`/courses/${course.id}`}>{course.name}</Link>
								</td>
								<td className="masteriyo-px-6 masteriyo-py-4 masteriyo-whitespace-nowrap"></td>
								<td className="masteriyo-px-6 masteriyo-py-4 masteriyo-whitespace-nowrap">
									{course.price}
								</td>
								<td className="masteriyo-px-6 masteriyo-py-4 masteriyo-whitespace-nowrap">
									<ul className="masteriyo-flex masteriyo-list-none masteriyo-text-base masteriyo-justify-center">
										<li
											onClick={() => deleteCourse.mutate(course.id)}
											className="masteriyo-text-gray-800 hover:masteriyo-text-red-600 masteriyo-cursor-pointer ">
											<Icon icon={<Trash />} />
										</li>
									</ul>
								</td>
							</tr>
						))}
					</tbody>
				</table>
				{showDeleteModal && (
					<div className="masteriyo-fixed masteriyo-z-10 masteriyo-overflow-y-auto masteriyo-bg-gray-600 masteriyo-bg-opacity-80 masteriyo-shadow-lg masteriyo-inset-0">
						<div className="masteriyo-flex masteriyo-items-center masteriyo-justify-center masteriyo-w-full masteriyo-h-full">
							<div className="masteriyo-bg-white masteriyo-rounded-md masteriyo-shadow-xl masteriyo-overflow-hidden masteriyo-w-3/12">
								<div className="masteriyo-p-8 masteriyo-flex">
									<div className="masteriyo-rounded-full masteriyo-flex-shrink-0 masteriyo-bg-red-200 masteriyo-w-10 masteriyo-h-10 masteriyo-flex masteriyo-items-center masteriyo-justify-center masteriyo-text-xl masteriyo-text-red-600">
										<Icon icon={<Trash />} />
									</div>
									<div>
										<h3 className="masteriyo-ml-4 masteriyo-text-xl masteriyo-mb-3">
											Delete Course #courseName
										</h3>
										<p className="masteriyo-ml-4 masteriyo-text-md masteriyo-text-gray-500">
											Are you sure want to delete this course. You won't be able
											to recover it back
										</p>
									</div>
								</div>
								<footer className="masteriyo-px-8 masteriyo-py-4 masteriyo-flex masteriyo-justify-end masteriyo-bg-gray-100">
									<Button>Cancel</Button>
									<Button appearance="secondary" className="masteriyo-ml-3">
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
