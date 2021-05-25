import { Box, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

import { CourseDataMap } from '../../types/course';
import Categories from './components/Categories';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';
import Price from './components/Price';

interface Props {
	courseData: CourseDataMap | any;
}

const EditCourse: React.FC<Props> = (props) => {
	const { courseData } = props;

	return (
		<form>
			<Stack direction="row" spacing="8">
				<Box
					flex="1"
					bg="white"
					p="10"
					shadow="box"
					d="flex"
					flexDirection="column"
					justifyContent="space-between">
					<Stack direction="column" spacing="6">
						<Name defaultValue={courseData.name} />
						<Description defaultValue={courseData.description} />
						<Price defaultValue={courseData.regular_price} />
					</Stack>
				</Box>
				<Box w="400px" bg="white" p="10" shadow="box">
					<Stack direction="column" spacing="6">
						<Categories defaultValue={courseData.categories} />
						<FeaturedImage defaultValue={courseData.featured_image} />
					</Stack>
				</Box>
			</Stack>
		</form>
	);
};

export default EditCourse;
