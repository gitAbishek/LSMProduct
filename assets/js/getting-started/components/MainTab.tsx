import {
	Container,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';

import * as screens from '../screens';

const MainTab: React.FC = () => {
	const [selectedIndex, SetSelectedIndex] = useState<number>(0);

	const methods = useForm();

	const handleSelect = (index?: number) => {
		SetSelectedIndex(index);
	};

	const onSubmit = (data?: any) => {
		console.log(data);
	};

	return (
		<FormProvider {...methods}>
			<form onSubmit={methods.handleSubmit(onSubmit)}>
				<Container maxW="container.md">
					<Tabs index={selectedIndex} onChange={handleSelect}>
						<TabList justifyContent="space-between">
							<Tab>{__('Welcome', 'masteriyo')}</Tab>
							<Tab>{__('Currency', 'masteriyo')}</Tab>
							<Tab>{__('Course', 'masteriyo')}</Tab>
							<Tab>{__('Quiz', 'masteriyo')}</Tab>
							<Tab>{__('Pages', 'masteriyo')}</Tab>
							<Tab>{__('Finish', 'masteriyo')}</Tab>
						</TabList>

						<TabPanels>
							<TabPanel>
								<screens.Welcome setTabIndex={SetSelectedIndex} />
							</TabPanel>
							<TabPanel>
								<screens.Currency setTabIndex={SetSelectedIndex} />
							</TabPanel>
							<TabPanel>
								<screens.Course setTabIndex={SetSelectedIndex} />
							</TabPanel>
							<TabPanel>
								<screens.Quiz setTabIndex={SetSelectedIndex} />
							</TabPanel>
							<TabPanel>
								<screens.Pages setTabIndex={SetSelectedIndex} />
							</TabPanel>
							<TabPanel>
								<screens.Finish />
							</TabPanel>
						</TabPanels>
					</Tabs>
				</Container>
			</form>
		</FormProvider>
	);
};

export default MainTab;
