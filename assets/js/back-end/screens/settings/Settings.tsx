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
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import Header from 'Components/layout/Header';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useQuery } from 'react-query';
import urls from '../../constants/urls';
import { SetttingsMap } from '../../types';
import API from '../../utils/api';
import GeneralSettings from './components/GeneralSettings';

const Settings = () => {
	const courseApi = new API(urls.settings);
	const methods = useForm<SetttingsMap>();
	const [settings, setSettings] = useState();
	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '4',
	};

	const tabPanelStyles = {
		px: '0',
		py: 8,
	};

	const settingsQuery = useQuery('settings', () => courseApi.list());

	if (settingsQuery.isLoading) {
		return <FullScreenLoader />;
	}

	const onSubmit = (data: SetttingsMap) => {
		console.log(data);
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
										<GeneralSettings />
										<Button colorScheme="blue" type="submit">
											{__('Save', 'masteriyo')}
										</Button>
									</TabPanel>
								</TabPanels>
								<ButtonGroup></ButtonGroup>
							</form>
						</FormProvider>
					</Tabs>
				</Box>
			</Container>
		</Stack>
	);
};

export default Settings;
