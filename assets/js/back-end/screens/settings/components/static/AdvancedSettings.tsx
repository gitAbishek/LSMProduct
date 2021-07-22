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
import PagesAPI from '../../../../utils/pages';

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

const AdvancedSettings: React.FC = () => {
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
									{...register('pages.course_list_page_id')}>
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
									{...register('pages.myaccount_page_id')}
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
									{...register('pages.checkout_page_id')}>
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
									{...register('pages.terms_conditions_page_id')}>
									{renderPagesOption()}
								</Select>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="2xs">
										{__('Single Course Permalink', 'masteriyo')}
									</FormLabel>
									<Controller
										name="courses.single_course_permalink"
										render={({ field }) => (
											<RadioGroup {...field}>
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
								<Stack direction="row">
									<FormLabel minW="2xs">
										{__('Single Section Permalink', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('courses.single_section_permalink')}
									/>
								</Stack>
							</FormControl>

							<FormControl>
								<Stack direction="row">
									<FormLabel minW="2xs">
										{__('Single Lesson Permalink', 'masteriyo')}
									</FormLabel>
									<Input type="text" {...register('single_lesson_permalink')} />
								</Stack>
							</FormControl>

							<FormControl>
								<Stack direction="row">
									<FormLabel minW="2xs">
										{__('Single Quiz Permalink', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('courses.single_quiz_permalink')}
									/>
								</Stack>
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
									{...register('pages.account_endpoints.orders')}
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
									{...register('pages.account_endpoints.view_order')}
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
									{...register('pages.account_endpoints.my_courses')}
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
									{...register('pages.account_endpoints.edit_account')}
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
									{...register('pages.account_endpoints.payment_methods')}
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
									{...register('pages.account_endpoints.lost_password')}
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
									{...register('pages.account_endpoints.logout')}
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
									{...register('pages.checkout_endpoints.pay')}
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
									{...register('pages.checkout_endpoints.order_received')}
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
									{...register('pages.checkout_endpoints.add_payment_method')}
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
									{...register(
										'pages.checkout_endpoints.delete_payment_method'
									)}
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
									{...register(
										'pages.checkout_endpoints.set_default_payment_method'
									)}
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
											name="advance.template_debug"
											render={({ field }) => (
												<Switch {...field} defaultChecked={false} />
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
											name="advance.debug"
											render={({ field }) => (
												<Switch {...field} defaultChecked={false} />
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
