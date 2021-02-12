import { Fragment, React } from '@wordpress/element';

import { BaseLine } from 'Config/defaultStyle';
import ContentLoader from 'react-content-loader';
import Flex from 'Components/common/Flex';
import FlexRow from 'Components/common/FlexRow';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import { fetchCourses } from '../../utils/api';
import styled from 'styled-components';
import { useQuery } from 'react-query';

const Courses = () => {
	const { data: coursesData, isLoading } = useQuery('courseData', fetchCourses);
	const renderCategories = (categories) => {
		return (
			<ul style={{ margin: 0, padding: 0 }}>
				{categories?.map(({ id, name }) => (
					<li key={id}>{name}</li>
				))}
			</ul>
		);
	};
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
						{coursesData?.map(({ id, name, categories, price }) => (
							<tr key={id}>
								<td>{id}</td>
								<td>{name}</td>
								<td>{renderCategories(categories)}</td>
								<td>{price}</td>
							</tr>
						))}
					</tbody>
				</table>
			</MainLayout>
		</Fragment>
	);
};

const CourseContainer = styled(FlexRow)`
	align-items: flex-start;
	margin-left: -${BaseLine * 2}px;
	margin-right: -${BaseLine * 2}px;
`;

const CourseInner = styled(Flex)`
	padding-left: ${BaseLine * 2}px;
	padding-right: ${BaseLine * 2}px;
`;

const CourseLeftContainer = styled(CourseInner)`
	flex: 1;
`;

const CourseRightContainer = styled(CourseInner)`
	flex-basis: 400px;
`;

const FeaturedImageActions = styled(FlexRow)`
	justify-content: space-between;
	margin-top: ${BaseLine * 3}px;
`;

export default Courses;
