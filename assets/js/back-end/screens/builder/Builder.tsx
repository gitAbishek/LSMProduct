import {
	Spinner,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const queryClient = useQueryClient();
	const history = useHistory();
	const courseAPI = new API(urls.courses);

	const tabStyles = {
		fontWeight: 'medium',
		py: '4',
	};

	const tabPanelStyles = {
		px: '0',
	};

	const courseQuery = useQuery(
		[`courses/${courseId}`, courseId],
		() => courseAPI.get(courseId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	if (courseQuery.isLoading) {
		return <Spinner />;
	}

	return (
		<Tabs>
			<TabList borderBottom="1px">
				<Tab sx={tabStyles}>{__('Course', 'masteriyo')}</Tab>
				<Tab sx={tabStyles}>{__('Builder', 'masteriyo')}</Tab>
				<Tab sx={tabStyles}>{__('Settings', 'masteriyo')}</Tab>
			</TabList>
			<TabPanels>
				<TabPanel sx={tabPanelStyles}></TabPanel>
				<TabPanel sx={tabPanelStyles}></TabPanel>
			</TabPanels>
		</Tabs>
	);
};

export default Builder;
