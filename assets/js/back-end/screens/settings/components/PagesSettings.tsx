import {
	Stack,
	Box,
	Flex,
	Heading,
	FormControl,
	FormLabel,
	Select,
	Input,
	Spinner,
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
		return pagesQuery?.data?.map((page: { id: number; title: any }) => (
			<option value={page.id} key={page.id}>
				{page.title.rendered}
			</option>
		));
	};

	return (
		<Stack direction="column" spacing="8">
			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('Page Setup', 'masteriyo')}
						</Heading>
					</Flex>

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
						<FormLabel minW="2xs">{__('Checkout Page', 'masteriyo')}</FormLabel>
						<Select
							placeholder={__('Select a Page', 'masteriyo')}
							defaultValue={pageSettingsData?.checkout_page_id}
							{...register('pages.checkout_page_id')}>
							{renderPagesOption()}
						</Select>
					</FormControl>
				</Stack>
			</Box>

			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('Checkout Endpoints', 'masteriyo')}
						</Heading>
					</Flex>

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
							defaultValue={pageSettingsData?.checkout_endpoints.order_received}
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
							{...register('pages.checkout_endpoints.delete_payment_method')}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Set Default Payment Method', 'masteriyo')}
						</FormLabel>
						<Input
							type="text"
							defaultValue={
								pageSettingsData?.checkout_endpoints.set_default_payment_method
							}
							{...register(
								'pages.checkout_endpoints.set_default_payment_method'
							)}
						/>
					</FormControl>
				</Stack>
			</Box>

			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('Account Endpoints', 'masteriyo')}
						</Heading>
					</Flex>

					<FormControl>
						<FormLabel minW="2xs">{__('Orders', 'masteriyo')}</FormLabel>
						<Input
							type="text"
							defaultValue={pageSettingsData?.account_endpoints.orders}
							{...register('pages.account_endpoints.orders')}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">{__('View Order', 'masteriyo')}</FormLabel>
						<Input
							type="text"
							defaultValue={pageSettingsData?.account_endpoints.view_order}
							{...register('pages.account_endpoints.view_order')}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">{__('My Courses', 'masteriyo')}</FormLabel>
						<Input
							type="text"
							defaultValue={pageSettingsData?.account_endpoints.my_courses}
							{...register('pages.account_endpoints.my_courses')}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">{__('Edit Account', 'masteriyo')}</FormLabel>
						<Input
							type="text"
							defaultValue={pageSettingsData?.account_endpoints.edit_account}
							{...register('pages.account_endpoints.edit_account')}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Payment Methods', 'masteriyo')}
						</FormLabel>
						<Input
							type="text"
							defaultValue={pageSettingsData?.account_endpoints.payment_methods}
							{...register('pages.account_endpoints.payment_methods')}
						/>
					</FormControl>

					<FormControl>
						<FormLabel minW="2xs">{__('Lost Password', 'masteriyo')}</FormLabel>
						<Input
							type="text"
							defaultValue={pageSettingsData?.account_endpoints.lost_password}
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
			</Box>
		</Stack>
	);
};

export default PagesSettings;
