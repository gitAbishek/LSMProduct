import {
	Container,
	Heading,
	Stack,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React, { useState } from 'react';
import { useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import EditCourse from '../courses/EditCourse';
import SectionBuilder from '../sections/SectionBuilder';
import Header from './component/Header';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const history = useHistory();
	const courseAPI = new API(urls.courses);
	const [builderData, setBuilderData] = useState<any>(null);
	const [courseUpdateData, setCourseUpdateData] = useState<any>(null);

	const tabPanelStyles = {
		px: '0',
	};

	const courseQuery = useQuery<CourseDataMap>(
		[`courses${courseId}`, courseId],
		() => courseAPI.get(courseId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const onSave = () => {
		console.log(builderData);
	};

	if (courseQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Tabs>
			<Stack direction="column" spacing="10" align="center">
				<Header
					previewUrl={courseQuery.data?.preview_permalink}
					onSave={onSave}
				/>
				<Container maxW="container.xl">
					<Stack direction="column" spacing="6">
						<Heading as="h1" fontSize="x-large">
							{__('Edit Course: ', 'masteriyo')} {courseQuery.data?.name}
						</Heading>
						<TabPanels>
							<TabPanel sx={tabPanelStyles}>
								<EditCourse courseData={courseQuery.data} />
							</TabPanel>
							<TabPanel sx={tabPanelStyles}>
								<SectionBuilder
									courseId={courseQuery.data?.id}
									builderData={builderData}
									setBuilderData={setBuilderData}
								/>
							</TabPanel>
						</TabPanels>
					</Stack>
				</Container>
			</Stack>
		</Tabs>
	);
};

export default Builder;
