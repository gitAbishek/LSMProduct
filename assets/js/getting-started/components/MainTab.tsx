import {
	Container,
	Select,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation } from 'react-query';

import urls from '../../src/constants/urls';
import API from '../../src/utils/api';
import * as screens from '../screens';

declare var masteriyo: amy;

const MainTab: React.FC = () => {
	const [selectedIndex, SetSelectedIndex] = useState<number>(0);
	const [FinishOnboard, SetFinishOnboard] = useState(false);

	const methods = useForm();
	const toast = useToast();
	const settingAPI = new API(urls.settings);

	const addMutation = useMutation((data) => settingAPI.store(data), {
		onSuccess: (data) => {
			SetSelectedIndex(5);
			SetFinishOnboard(true);

			toast({
				title: `Global setting successfully updated`,
				status: 'success',
				isClosable: true,
			});
		},
		onError: (error) => {
			toast({
				title: `${error}. Please try again!!`,
				status: 'error',
				isClosable: true,
			});
		},
	});

	const handleSelect = (index?: number) => {
		SetSelectedIndex(index);
	};

	const onSubmit = (data?: any) => {
		const formattedData: any = {
			general: {
				currency: `${data.currency}`,
				currency_position:
					'undefined' != `${data.currency_position}`
						? `${data.currency_position}`
						: 'left',
				thousand_separator:
					'undefined' != `${data.thousand_separator}`
						? `${data.thousand_separator}`
						: ',',
				decimal_separator:
					'undefined' != `${data.decimal_separator}`
						? `${data.decimal_separator}`
						: '.',
				number_of_decimals:
					'undefined' != `${data.number_of_decimals}`
						? `${data.number_of_decimals}`
						: '2',
			},
			courses: {
				per_row: `${data.course_per_row}`,
				per_page: `${data.course_per_page}`,
			},
			quizzes: {
				time_limit: `${data.time_limit}`,
				attempts_allowed: `${data.attempts_allowed}`,
			},
			pages: {
				myaccount_page_id: `${data.myaccount_page_id}`,
				course_list_page_id: `${data.course_list_page_id}`,
				checkout_page_id: `${data.checkout_page_id}`,
			},
		};
		console.log(formattedData);

		addMutation.mutate(formattedData);
	};

	const { adminURL, siteURL, pageBuilderURL } =
		'undefined' != typeof masteriyo && masteriyo;

	let sharedProps = {
		setTabIndex: SetSelectedIndex,
		dashboardURL: adminURL,
		siteURL: siteURL,
		pageBuilderURL: pageBuilderURL,
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
							<Tab isDisabled={!FinishOnboard}>{__('Finish', 'masteriyo')}</Tab>
						</TabList>

						<TabPanels>
							<TabPanel>
								<screens.Welcome {...sharedProps} />
							</TabPanel>
							<TabPanel>
								<screens.Currency {...sharedProps} />
							</TabPanel>
							<TabPanel>
								<screens.Course {...sharedProps} />
							</TabPanel>
							<TabPanel>
								<screens.Quiz {...sharedProps} />
							</TabPanel>
							<TabPanel>
								<screens.Pages
									{...sharedProps}
									mutationLoading={addMutation.isLoading}
								/>
							</TabPanel>
							<TabPanel>
								<screens.Finish {...sharedProps} />
							</TabPanel>
						</TabPanels>
					</Tabs>
				</Container>
			</form>
		</FormProvider>
	);
};

export default MainTab;
