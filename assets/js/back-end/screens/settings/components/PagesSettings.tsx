import {
	FormControl,
	FormLabel,
	Input,
	Select,
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
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import { PagesSettingsMap } from '../../../types';
import PagesAPI from '../../../utils/pages';

interface Props {
	pageSettingsData?: PagesSettingsMap;
}

const PagesSettings: React.FC<Props> = (props) => {
	const { pageSettingsData } = props;
	const { register } = useFormContext();
	const pageAPI = new PagesAPI();
	const pagesQuery = useQuery('pages', () => pageAPI.list());

	if (pagesQuery.isLoading) {
		return <Spinner />;
	}

	const renderPagesOption = () => {
		try {
			return pagesQuery?.data?.map(
				(page: { id: number; title: { rendered: string } }) => (
					<option value={page.id} key={page.id}>
						{page.title.rendered}
					</option>
				)
			);
		} catch (error) {
			console.error(error);
			return;
		}
	};

	const tabStyles = {
		justifyContent: 'flex-start',
		w: '180px',
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'transparent',
		marginLeft: 0,
		marginRight: '-2px',
		pl: 0,
		fontSize: 'sm',
		textAlign: 'left',
	};

	const tabListStyles = {
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'gray.200',
	};

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Page Setup', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Checkout Endpoints', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Account Endpoints', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<FormLabel minW="2xs">
									{__('My Account Page', 'masteriyo')}
								</FormLabel>
								<Select
									{...register('pages.myaccount_page_id')}
									placeholder={__('Select a Page', 'masteriyo')}
									defaultValue={pageSettingsData?.myaccount_page_id}>
									{renderPagesOption()}
								</Select>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Course List Page', 'masteriyo')}
								</FormLabel>
								<Select
									placeholder={__('Select a Page', 'masteriyo')}
									defaultValue={pageSettingsData?.course_list_page_id}
									{...register('pages.course_list_page_id')}>
									{renderPagesOption()}
								</Select>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Terms and Coditions Page', 'masteriyo')}
								</FormLabel>
								<Select
									placeholder={__('Select a Page', 'masteriyo')}
									defaultValue={pageSettingsData?.terms_conditions_page_id}
									{...register('pages.terms_conditions_page_id')}>
									{renderPagesOption()}
								</Select>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Checkout Page', 'masteriyo')}
								</FormLabel>
								<Select
									placeholder={__('Select a Page', 'masteriyo')}
									defaultValue={pageSettingsData?.checkout_page_id}
									{...register('pages.checkout_page_id')}>
									{renderPagesOption()}
								</Select>
							</FormControl>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<FormLabel minW="2xs">{__('Pay', 'masteriyo')}</FormLabel>
								<Input
									type="text"
									defaultValue={pageSettingsData?.checkout_endpoints.pay}
									{...register('pages.checkout_endpoints.pay')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Order Recieved', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										pageSettingsData?.checkout_endpoints.order_received
									}
									{...register('pages.checkout_endpoints.order_received')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Add Payment Method', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										pageSettingsData?.checkout_endpoints.add_payment_method
									}
									{...register('pages.checkout_endpoints.add_payment_method')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Delete Payment Method', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										pageSettingsData?.checkout_endpoints.delete_payment_method
									}
									{...register(
										'pages.checkout_endpoints.delete_payment_method'
									)}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Set Default Payment Method', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										pageSettingsData?.checkout_endpoints
											.set_default_payment_method
									}
									{...register(
										'pages.checkout_endpoints.set_default_payment_method'
									)}
								/>
							</FormControl>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<FormLabel minW="2xs">{__('Orders', 'masteriyo')}</FormLabel>
								<Input
									type="text"
									defaultValue={pageSettingsData?.account_endpoints.orders}
									{...register('pages.account_endpoints.orders')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('View Order', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={pageSettingsData?.account_endpoints.view_order}
									{...register('pages.account_endpoints.view_order')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('My Courses', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={pageSettingsData?.account_endpoints.my_courses}
									{...register('pages.account_endpoints.my_courses')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Edit Account', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										pageSettingsData?.account_endpoints.edit_account
									}
									{...register('pages.account_endpoints.edit_account')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Payment Methods', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										pageSettingsData?.account_endpoints.payment_methods
									}
									{...register('pages.account_endpoints.payment_methods')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Lost Password', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										pageSettingsData?.account_endpoints.lost_password
									}
									{...register('pages.account_endpoints.lost_password')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">{__('Logout', 'masteriyo')}</FormLabel>
								<Input
									type="text"
									defaultValue={pageSettingsData?.account_endpoints.logout}
									{...register('pages.account_endpoints.logout')}
								/>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default PagesSettings;
