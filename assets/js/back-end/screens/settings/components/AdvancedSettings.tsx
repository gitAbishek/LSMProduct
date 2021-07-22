import {
	Box,
	FormControl,
	FormHelperText,
	FormLabel,
	Icon,
	Input,
	Radio,
	RadioGroup,
	Select,
	Spinner,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Text,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { infoIconStyles } from 'Config/styles';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { AdvancedSettingsMap } from '../../../types';
import PagesAPI from '../../../utils/pages';

interface Props {
	advanceData?: AdvancedSettingsMap;
}

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

const AdvancedSettings: React.FC<Props> = (props) => {
	const { advanceData } = props;
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

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Pages Setup', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Permalinks', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Account Endpoints', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Checkout Endpoints', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Debug', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<FormLabel minW="2xs">
									{__('Course List Page', 'masteriyo')}
									<Tooltip
										label={__(
											'This set course listing page where the courses are display',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Select
									placeholder={__('Select a Page', 'masteriyo')}
									{...register('advance.pages.course_list_page_id')}
									defaultValue={advanceData?.pages?.course_list_page_id}>
									{renderPagesOption()}
								</Select>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('My Account Page', 'masteriyo')}
									<Tooltip
										label={__(
											'Page contents: [masteriyo_my_account]',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Select
									{...register('advance.pages.myaccount_page_id')}
									defaultValue={advanceData?.pages?.myaccount_page_id}
									placeholder={__('Select a Page', 'masteriyo')}>
									{renderPagesOption()}
								</Select>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Checkout Page', 'masteriyo')}
									<Tooltip
										label={__(
											'Page contents: [masteriyo_checkout]',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Select
									placeholder={__('Select a Page', 'masteriyo')}
									defaultValue={advanceData?.pages?.checkout_page_id}
									{...register('advance.pages.checkout_page_id')}>
									{renderPagesOption()}
								</Select>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Terms and Coditions Page', 'masteriyo')}
									<Tooltip
										label={__(
											'If you define a "Terms" page the customer will be asked if they accept them when checking out.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Select
									placeholder={__('Select a Page', 'masteriyo')}
									defaultValue={advanceData?.pages?.terms_conditions_page_id}
									{...register('advance.pages.terms_conditions_page_id')}>
									{renderPagesOption()}
								</Select>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<FormLabel minW="2xs">
									{__('Course Category Base', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={advanceData?.permalinks?.category_base}
									{...register('advance.permalinks.category_base')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Couses Tag Base', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									{...register('advance.permalinks.tag_base')}
									defaultValue={advanceData?.permalinks?.tag_base}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Course Difficulty Base', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									{...register('advance.permalinks.difficulty_base')}
									defaultValue={advanceData?.permalinks?.difficulty_base}
								/>
							</FormControl>

							<FormControl>
								<Stack direction="row">
									<FormLabel minW="2xs">
										{__('Single Course Permalink', 'masteriyo')}
									</FormLabel>
									<Controller
										name="advance.permalinks.single_course_permalink"
										render={({ field }) => (
											<RadioGroup
												{...field}
												defaultValue={
													advanceData?.permalinks?.single_course_permalink
												}>
												<Stack spacing={3} direction="column">
													<Radio value="default">
														<FormHelperText mt="0">
															http://example.com?masteriyo_course=sample-course
														</FormHelperText>
													</Radio>
													<Radio value="pretty">
														<FormHelperText mt="0">
															http://example.com/course/sample-course
														</FormHelperText>
													</Radio>
												</Stack>
											</RadioGroup>
										)}
									/>
								</Stack>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Single Section Permalink', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={
										advanceData?.permalinks?.single_section_permalink
									}
									{...register('advance.permalinks.single_section_permalink')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Single Lesson Permalink', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									{...register('advance.permalinks.single_lesson_permalink')}
									defaultValue={
										advanceData?.permalinks?.single_lesson_permalink
									}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Single Quiz Permalink', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									{...register('advance.permalinks.single_quiz_permalink')}
									defaultValue={advanceData?.permalinks?.single_quiz_permalink}
								/>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
							<Text fontSize="sm" color="gray.600">
								{__(
									'Endpoints are appended to your page URLs to handle specific actions on the accounts pages. They should be unique and can be left blank to disable the endpoint.',
									'masteriyo'
								)}
							</Text>
							<FormControl>
								<FormLabel minW="2xs">
									{__('Orders', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "My account - Orders" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.account.orders')}
									defaultValue={advanceData?.account?.orders}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('View Order', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "My account - View order" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.account.view_order')}
									defaultValue={advanceData?.account?.view_order}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('My Courses', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "My account - My courses" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.account.my_courses')}
									defaultValue={advanceData?.account?.my_courses}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Edit Account', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "My account - Edit Account" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.account.edit_account')}
									defaultValue={advanceData?.account?.edit_account}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Payment Methods', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "My account - Payment methods" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.account.payment_methods')}
									defaultValue={advanceData?.account?.payment_methods}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Lost Password', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "My account - Lost password" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.account.lost_password')}
									defaultValue={advanceData?.account?.lost_password}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Logout', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the triggering logout',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.account.logout')}
									defaultValue={advanceData?.account?.logout}
								/>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
							<Text fontSize="sm" color="gray.600">
								{__(
									'Endpoints are appended to your page URLs to handle specific actions during the checkout process. They should be unique.',
									'masteriyo'
								)}
							</Text>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Pay', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Checkout - Pay" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.checkout.pay')}
									defaultValue={advanceData?.checkout?.pay}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Order Recieved', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Checkout - Order received" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.checkout.order_received')}
									defaultValue={advanceData?.checkout?.order_received}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Add Payment Method', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Checkout - Add payment method" page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.checkout.add_payment_method')}
									defaultValue={advanceData?.checkout?.add_payment_method}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Delete Payment Method', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the delete payment method page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.checkout.delete_payment_method')}
									defaultValue={advanceData?.checkout?.delete_payment_method}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Set Default Payment Method', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the setting a default payment method page',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Input
									type="text"
									{...register('advance.checkout.set_default_payment_method')}
									defaultValue={
										advanceData?.checkout?.set_default_payment_method
									}
								/>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="column" spacing="8">
								<FormControl>
									<Stack direction="row">
										<FormLabel minW="3xs">
											{__('Template Debug', 'masteriyo')}
											<Tooltip
												label={__('Use for template debugging', 'masteriyo')}
												hasArrow
												fontSize="xs">
												<Box as="span" sx={infoIconStyles}>
													<Icon as={BiInfoCircle} />
												</Box>
											</Tooltip>
										</FormLabel>

										<Controller
											name="advance.debug.template_debug"
											render={({ field }) => (
												<Switch
													{...field}
													defaultChecked={advanceData?.debug?.template_debug}
												/>
											)}
										/>
									</Stack>
								</FormControl>

								<FormControl>
									<Stack direction="row">
										<FormLabel minW="3xs">
											{__('Debug', 'masteriyo')}
											<Tooltip
												label={__('Use for plugin debugging', 'masteriyo')}
												hasArrow
												fontSize="xs">
												<Box as="span" sx={infoIconStyles}>
													<Icon as={BiInfoCircle} />
												</Box>
											</Tooltip>
										</FormLabel>
										<Controller
											name="advance.debug.debug"
											render={({ field }) => (
												<Switch
													{...field}
													defaultChecked={advanceData?.debug?.debug}
												/>
											)}
										/>
									</Stack>
								</FormControl>
							</Stack>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default AdvancedSettings;
