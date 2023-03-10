import { Box, Stack } from '@chakra-ui/react';
import React from 'react';
import { CourseDataMap } from '../../types/course';
import Categories from './components/Categories';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Highlights from './components/Highlights';
import Name from './components/Name';
import Slug from './components/Slug';

interface Props {
	courseData: CourseDataMap;
	tabIndex: boolean;
}

const EditCourse: React.FC<Props> = (props) => {
	const { courseData } = props;

	return (
		<form>
			<Stack direction={['column', null, 'row']} spacing="8">
				<Box
					flex="1"
					bg="white"
					p={['4', null, '10']}
					shadow="box"
					d="flex"
					flexDirection="column"
					justifyContent="space-between">
					<Stack direction="column" spacing="6">
						<Name defaultValue={courseData?.name} />
						<Description defaultValue={courseData?.description} />
					</Stack>
				</Box>
				<Box
					w={['100%', null, '400px']}
					bg="white"
					p={['4', null, '10']}
					shadow="box">
					<Stack direction="column" spacing="6">
						<Highlights defaultValue={courseData?.highlights} />
						<Categories defaultValue={courseData?.categories} />
						<Slug defaultValue={courseData?.slug} />
						<FeaturedImage defaultValue={courseData?.featured_image} />
					</Stack>
				</Box>
			</Stack>
		</form>
	);
};

export default EditCourse;
