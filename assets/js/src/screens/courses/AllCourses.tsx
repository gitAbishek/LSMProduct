import React, { Fragment } from 'react';

import ContentLoader from 'react-content-loader';
import Icon from 'Components/common/Icon';
import { Link } from 'react-router-dom';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import { Trash } from '../../assets/icons';
import { fetchCourses } from '../../utils/api';
import { useQuery } from 'react-query';

const AllCourses = () => {
	const { data: coursesData, isLoading } = useQuery('courseData', fetchCourses);

	console.log(coursesData);
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
									<ul className="masteriyo-flex masteriyo-list-none masteriyo-text-xs">
										<li>
											<Icon icon={<Trash />} />
										</li>
									</ul>
								</td>
							</tr>
						))}
					</tbody>
				</table>
			</MainLayout>
		</Fragment>
	);
};

export default AllCourses;
