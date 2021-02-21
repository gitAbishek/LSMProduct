import React, { Fragment } from 'react';

import ContentLoader from 'react-content-loader';
import { Link } from 'react-router-dom';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
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
				<table style={{ width: '100%', textAlign: 'left' }}>
					<thead>
						<tr>
							<th>id</th>
							<th>Title</th>
							<th>Categories</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody>
						{coursesData?.map((course: any) => (
							<tr key={course.id}>
								<td>{course.id}</td>
								<td>
									<Link to={`/courses/${course.id}`}>{course.name}</Link>
								</td>
								<td></td>
								<td>{course.price}</td>
							</tr>
						))}
					</tbody>
				</table>
			</MainLayout>
		</Fragment>
	);
};

export default AllCourses;
