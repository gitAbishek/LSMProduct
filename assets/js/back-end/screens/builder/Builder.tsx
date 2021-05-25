import {
	Box,
	Container,
	Flex,
	Image,
	Spinner,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery, useQueryClient } from 'react-query';
import { Link, useHistory, useParams } from 'react-router-dom';

import { Logo } from '../../constants/images';
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
			<Box bg="white" w="full">
				<Container maxW="container.xl">
					<Flex direction="row" justifyContent="space-between" align="center">
						<Stack direction="row" spacing="12" align="center">
							<Box>
								<Link to={routes.courses.list}>
									<Image src={Logo} alt="Masteriyo Logo" w="120px" />
								</Link>
							</Box>
							<TabList borderBottom="none" bg="white">
								<Tab sx={tabStyles}>{__('Course', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Builder', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Settings', 'masteriyo')}</Tab>
							</TabList>
						</Stack>
					</Flex>
				</Container>
			</Box>
			<TabPanels>
				<TabPanel sx={tabPanelStyles}></TabPanel>
				<TabPanel sx={tabPanelStyles}></TabPanel>
			</TabPanels>
		</Tabs>
	);
};

export default Builder;
