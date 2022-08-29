import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useContext } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiCog } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink } from 'react-router-dom';
import Header from '../../components/common/Header';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { ThemeContext } from '../../context/ThemeProvider';
import { SetttingsMap } from '../../types';
import API from '../../utils/api';
import { deepClean } from '../../utils/utils';
import AdvancedSettings from './components/AdvancedSettings';
import CourseArchiveSettings from './components/CourseArchiveSettings';
import EmailSetttings from './components/EmailSettings';
import GeneralSettings from './components/GeneralSettings';
import LearningPageSettings from './components/LearningPageSettings';
import PaymentsSettings from './components/PaymentsSettings';
import QuizSettings from './components/QuizSettings';
import SingleCourseSettings from './components/SingleCourseSettings';

const Settings = () => {
	const settingsApi = new API(urls.settings);
	const methods = useForm<SetttingsMap>({
		reValidateMode: 'onChange',
		mode: 'onChange',
	});
	const toast = useToast();
	const queryClient = useQueryClient();

	const [_, dispatchColor] = useContext(ThemeContext);

	const tabStyles = {
		fontWeight: 'medium',
		py: ['2', '4'],
		fontSize: ['xs', null, 'sm'],
		px: ['1', '2', '4'],
	};

	const tabPanelStyles = {
		px: '0',
		py: 8,
	};

	const settingsQuery = useQuery<SetttingsMap>('settings', () =>
		settingsApi.list()
	);

	const updateSettings = useMutation(
		(data: SetttingsMap) => settingsApi.store(data),
		{
			onSuccess: (data) => {
				toast({
					title: __('Settings Updated.', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				dispatchColor({
					type: 'CHANGE_COLOR',
					payload: data?.general?.styling?.primary_color,
				});

				queryClient.invalidateQueries(`settings`);
			},
		}
	);

	const onSubmit = (data: SetttingsMap) => {
		try {
			updateSettings.mutate(deepClean(data));
		} catch (err) {}
	};

	if (settingsQuery.isSuccess) {
		return (
			<FormProvider {...methods}>
				<Stack direction="column" spacing="8" width="full" alignItems="center">
					<Header>
						<List d="flex">
							<ListItem mb="0">
								<Link
									as={NavLink}
									sx={navLinkStyles}
									_activeLink={navActiveStyles}
									_hover={{ color: 'primary.500' }}
									to={routes.settings}>
									<ListIcon as={BiCog} />
									{__('Settings', 'masteriyo')}
								</Link>
							</ListItem>
						</List>
					</Header>

					<Container maxW="container.xl">
						<Box bg="white" p={['4', null, '10']} shadow="box">
							<Tabs>
								<TabList
									justifyContent="center"
									borderBottom="1px"
									flexWrap="wrap">
									<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>{__('Courses Page', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>
										{__('Single Course Page', 'masteriyo')}
									</Tab>
									<Tab sx={tabStyles}>{__('Learn Page', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>{__('Payments', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>{__('Quiz', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>{__('Emails', 'masteriyo')}</Tab>
									<Tab sx={tabStyles}>{__('Advanced', 'masteriyo')}</Tab>
								</TabList>

								<form onSubmit={methods.handleSubmit(onSubmit)}>
									<TabPanels>
										<TabPanel sx={tabPanelStyles}>
											<GeneralSettings
												generalData={settingsQuery?.data?.general}
											/>
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<CourseArchiveSettings
												courseArchiveData={settingsQuery?.data?.course_archive}
											/>
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<SingleCourseSettings
												singleCourseData={settingsQuery?.data?.single_course}
											/>
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<LearningPageSettings
												learningPageData={settingsQuery?.data?.learn_page}
											/>
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<PaymentsSettings
												paymentsData={settingsQuery?.data?.payments}
											/>
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<QuizSettings quizData={settingsQuery?.data?.quiz} />
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<EmailSetttings emailData={settingsQuery?.data?.emails} />
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<AdvancedSettings
												advanceData={settingsQuery?.data?.advance}
											/>
										</TabPanel>
									</TabPanels>
									<ButtonGroup>
										<Button type="submit" isLoading={updateSettings.isLoading}>
											{__('Save Settings', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</form>
							</Tabs>
						</Box>
					</Container>
				</Stack>
			</FormProvider>
		);
	}
	return <FullScreenLoader />;
};

export default Settings;
