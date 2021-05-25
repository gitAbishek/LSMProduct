import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Icon,
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
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React from 'react';
import { BiBook, BiCog, BiEdit } from 'react-icons/bi';
import { useQuery, useQueryClient } from 'react-query';
import { Link, useHistory, useParams } from 'react-router-dom';

import { Logo } from '../../constants/images';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import EditCourse from '../courses/EditCourse';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const queryClient = useQueryClient();
	const history = useHistory();
	const courseAPI = new API(urls.courses);

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mx: 4,
	};

	const tabPanelStyles = {
		px: '0',
	};

	const iconStyles = {
		mr: '2',
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
		return <FullScreenLoader />;
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
								<Tab sx={tabStyles}>
									<Icon as={BiBook} sx={iconStyles} />
									{__('Course', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiEdit} sx={iconStyles} />
									{__('Builder', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiCog} sx={iconStyles} />
									{__('Settings', 'masteriyo')}
								</Tab>
							</TabList>
						</Stack>
						<ButtonGroup>
							<Button variant="outline">Preview</Button>
							<Link to={routes.courses.add}>
								<Button colorScheme="blue">{__('Save', 'masteriyo')}</Button>
							</Link>
						</ButtonGroup>
					</Flex>
				</Container>
			</Box>
			<TabPanels>
				<TabPanel sx={tabPanelStyles}>
					<Container maxW="container.xl">
						<EditCourse courseData={courseQuery.data} />
					</Container>
				</TabPanel>
				<TabPanel sx={tabPanelStyles}></TabPanel>
			</TabPanels>
		</Tabs>
	);
};

export default Builder;
