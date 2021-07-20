import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import urls from '../../constants/urls';
import { SetttingsMap } from '../../types';
import API from '../../utils/api';
import { deepClean } from '../../utils/utils';
import AdvancedSettings from './components/static/AdvancedSettings';
import CourseArchiveSettings from './components/static/CourseArchiveSettings';
import EmailSetttings from './components/static/EmailSettings';
import GeneralSettings from './components/static/GeneralSettings';
import LearningPageSettings from './components/static/LearningPageSettings';
import PaymentsSettings from './components/static/PaymentsSettings';
import QuizSettings from './components/static/QuizSettings';
import SingleCourseSettings from './components/static/SingleCourseSettings';

const Settings = () => {
	const settingsApi = new API(urls.settings);
	const methods = useForm<SetttingsMap>();
	const toast = useToast();
	const queryClient = useQueryClient();

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '4',
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
			onSuccess: () => {
				toast({
					title: __('Settings is Updated', 'masteriyo'),
					description: __('You can keep changing settings', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries(`settings`);
			},
		}
	);

	if (settingsQuery.isLoading) {
		return <FullScreenLoader />;
	}

	const onSubmit = (data: SetttingsMap) => {
		try {
			updateSettings.mutate(deepClean(data));
		} catch (err) {
			console.error(err);
		}
	};

	return (
		<FormProvider {...methods}>
			<Stack direction="column" spacing="8" width="full" alignItems="center">
				{/* <Header hideAddNewCourseBtn={true} hideCoursesMenu={true} /> */}
				<Container maxW="container.xl" pt="5">
					<Box bg="white" p="10" shadow="box">
						<Tabs>
							<TabList justifyContent="center" borderBottom="1px">
								<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Course Archive', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Single Course', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Learning Page', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Payments', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Quiz', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Emails', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Advanced', 'masteriyo')}</Tab>
							</TabList>

							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<TabPanels>
									<TabPanel sx={tabPanelStyles}>
										<GeneralSettings />
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<CourseArchiveSettings />
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<SingleCourseSettings />
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<LearningPageSettings />
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<PaymentsSettings />
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<QuizSettings />
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<EmailSetttings />
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<AdvancedSettings />
									</TabPanel>
								</TabPanels>
								<ButtonGroup>
									<Button
										colorScheme="blue"
										type="submit"
										isLoading={updateSettings.isLoading}>
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
};

export default Settings;
