import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Heading,
	Icon,
	Image,
	Link,
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
import { useQuery } from 'react-query';
import { Link as RouterLink, useHistory, useParams } from 'react-router-dom';

import { Logo } from '../../constants/images';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import EditCourse from '../courses/EditCourse';
import SectionBuilder from '../sections/SectionBuilder';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
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

	const courseQuery = useQuery<CourseDataMap>(
		[`courses${courseId}`, courseId],
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
			<Stack direction="column" spacing="10" align="center">
				<Box bg="white" w="full">
					<Container maxW="container.xl">
						<Flex direction="row" justifyContent="space-between" align="center">
							<Stack direction="row" spacing="12" align="center">
								<Box>
									<RouterLink to={routes.courses.list}>
										<Image src={Logo} alt="Masteriyo Logo" w="120px" />
									</RouterLink>
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
								<Link href={courseQuery.data?.preview_permalink} isExternal>
									<Button variant="outline">Preview</Button>
								</Link>

								<RouterLink to={routes.courses.add}>
									<Button colorScheme="blue">{__('Save', 'masteriyo')}</Button>
								</RouterLink>
							</ButtonGroup>
						</Flex>
					</Container>
				</Box>
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
								<SectionBuilder courseId={courseQuery.data?.id} />
							</TabPanel>
						</TabPanels>
					</Stack>
				</Container>
			</Stack>
		</Tabs>
	);
};

export default Builder;
