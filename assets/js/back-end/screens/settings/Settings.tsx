import {
	Box,
	Tabs,
	TabList,
	Tab,
	TabPanels,
	TabPanel,
	Stack,
	Container,
	ButtonGroup,
	Button,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import Header from 'Components/layout/Header';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import urls from '../../constants/urls';
import { SetttingsMap } from '../../types';
import API from '../../utils/api';
import CoursesSettings from './components/CoursesSettings';
import GeneralSettings from './components/GeneralSettings';
import PagesSettings from './components/PagesSettings';

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
		console.log(data.pages);
		updateSettings.mutate(data);
	};

	return (
		<Stack direction="column" spacing="8" width="full" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<Box bg="white" p="10" shadow="box">
					<Tabs>
						<TabList justifyContent="center" borderBottom="1px">
							<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Courses', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Pages', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Payments', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Emails', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Advanced', 'masteriyo')}</Tab>
						</TabList>
						<FormProvider {...methods}>
							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<TabPanels>
									<TabPanel sx={tabPanelStyles}>
										<GeneralSettings
											generalData={settingsQuery.data?.general}
										/>
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<CoursesSettings
											coursesData={settingsQuery.data?.courses}
										/>
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										<PagesSettings
											pageSettingsData={settingsQuery.data?.pages}
										/>
									</TabPanel>
								</TabPanels>
								<ButtonGroup>
									<Button
										colorScheme="blue"
										type="submit"
										isLoading={updateSettings.isLoading}>
										{__('Save', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</form>
						</FormProvider>
					</Tabs>
				</Box>
			</Container>
		</Stack>
	);
};

export default Settings;
