import {
	Alert,
	AlertIcon,
	Box,
	Center,
	Code,
	FormControl,
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
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { useQuery } from 'react-query';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { AdvancedSettingsMap, SetttingsMap } from '../../../types';
import PagesAPI from '../../../utils/pages';

interface Props {
	advanceData?: AdvancedSettingsMap;
}

//@ts-ignore
const coursesSlug = window._MASTERIYO_.pageSlugs.courses;

//@ts-ignore
const homeURL = window._MASTERIYO_.home_url;

const AdvancedSettings: React.FC<Props> = (props) => {
	const { advanceData } = props;
	const { register } = useFormContext();
	const pageAPI = new PagesAPI();
	const pagesQuery = useQuery('pages', () => pageAPI.list());

	const watchPermalinkData = useWatch<SetttingsMap>({
		name: 'advance.permalinks',
		defaultValue: {
			category_base: advanceData?.permalinks?.category_base,
			difficulty_base: advanceData?.permalinks?.difficulty_base,
			single_section_permalink:
				advanceData?.permalinks?.single_section_permalink,
			single_lesson_permalink: advanceData?.permalinks?.single_lesson_permalink,
			single_quiz_permalink: advanceData?.permalinks?.single_quiz_permalink,
		},
	});

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
						{pagesQuery.isLoading ? (
							<Center h="20">
								<Spinner />
							</Center>
						) : (
							<Stack direction="column" spacing="8">
								<FormControl>
									<FormLabel>
										{__('Courses Page', 'masteriyo')}
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
										{...register('advance.pages.courses_page_id')}
										defaultValue={advanceData?.pages?.courses_page_id}>
										{renderPagesOption()}
									</Select>
								</FormControl>

								<FormControl>
									<FormLabel>
										{__('Learn Page', 'masteriyo')}
										<Tooltip
											label={__(
												'This set learn page where the enroll courses can be learned.',
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
										{...register('advance.pages.learn_page_id')}
										defaultValue={advanceData?.pages?.learn_page_id}>
										{renderPagesOption()}
									</Select>
								</FormControl>

								<FormControl>
									<FormLabel>
										{__('Account Page', 'masteriyo')}
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
										{...register('advance.pages.account_page_id')}
										defaultValue={advanceData?.pages?.account_page_id}
										placeholder={__('Select a Page', 'masteriyo')}>
										{renderPagesOption()}
									</Select>
								</FormControl>

								<FormControl>
									<FormLabel>
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
							</Stack>
						)}
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<FormLabel>
									{__('Course Category Base', 'masteriyo')}
									<Tooltip
										label={__(
											'Enter custom structures for your category URLs here.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Stack direction="column">
									<Input
										type="text"
										defaultValue={advanceData?.permalinks?.category_base}
										{...register('advance.permalinks.category_base')}
									/>
									<Code>
										{homeURL}/{watchPermalinkData.category_base}/
										{__('uncategorized', 'masteriyo')}
									</Code>
								</Stack>
							</FormControl>

							<FormControl>
								<FormLabel>
									{__('Course Difficulty Base', 'masteriyo')}
									<Tooltip
										label={__(
											'Enter custom structures for your difficulty URLs here.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Stack direction="column">
									<Input
										type="text"
										{...register('advance.permalinks.difficulty_base')}
										defaultValue={advanceData?.permalinks?.difficulty_base}
									/>
									<Code>
										{homeURL}/{watchPermalinkData.difficulty_base}
										{__('/uncategorized', 'masteriyo')}
									</Code>
								</Stack>
							</FormControl>

							<FormControl>
								<Stack direction="row">
									<FormLabel>
										{__('Single Course Permalink', 'masteriyo')}
										<Tooltip
											label={__(
												'Enter custom structures for your course URLs here.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
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
													<Radio value="course">
														<Code>
															{homeURL}
															{__('?course=sample-course', 'masteriyo')}
														</Code>
													</Radio>
													{/** TS */}
													<Radio value={coursesSlug}>
														<Code>
															{homeURL}/{coursesSlug}
															{__('/sample-course', 'masteriyo')}
														</Code>
													</Radio>
												</Stack>
											</RadioGroup>
										)}
									/>
								</Stack>
							</FormControl>

							<FormControl>
								<FormLabel>
									{__('Single Section Permalink', 'masteriyo')}
									<Tooltip
										label={__(
											'Enter custom structures for your section URLs here.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Stack direction="column">
									<Input
										type="text"
										defaultValue={
											advanceData?.permalinks?.single_section_permalink
										}
										{...register('advance.permalinks.single_section_permalink')}
									/>
									<Code>
										{homeURL}/{watchPermalinkData.single_section_permalink}
										{__('/sample-section', 'masteriyo')}
									</Code>
								</Stack>
							</FormControl>

							<FormControl>
								<FormLabel>
									{__('Single Lesson Permalink', 'masteriyo')}
									<Tooltip
										label={__(
											'Enter custom structures for your lesson URLs here.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Stack direction="column">
									<Input
										type="text"
										{...register('advance.permalinks.single_lesson_permalink')}
										defaultValue={
											advanceData?.permalinks?.single_lesson_permalink
										}
									/>
									<Code>
										{homeURL}/{watchPermalinkData.single_lesson_permalink}
										{__('/sample-lesson', 'masteriyo')}
									</Code>
								</Stack>
							</FormControl>

							<FormControl>
								<FormLabel>
									{__('Single Quiz Permalink', 'masteriyo')}
									<Tooltip
										label={__(
											'Enter custom structures for your quiz URLs here.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<Stack direction="column">
									<Input
										type="text"
										{...register('advance.permalinks.single_quiz_permalink')}
										defaultValue={
											advanceData?.permalinks?.single_quiz_permalink
										}
									/>
									<Code>
										{homeURL}/{watchPermalinkData.single_quiz_permalink}
										{__('/sample-quiz', 'masteriyo')}
									</Code>
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
								<FormLabel>
									{__('Orders', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - Orders" page',
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
								<FormLabel>
									{__('View Order', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - View order" page',
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
								<FormLabel>
									{__('My Courses', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - My courses" page',
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
								<FormLabel>
									{__('Edit Account', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - Edit Account" page',
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
								<FormLabel>
									{__('Payment Methods', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - Payment methods" page',
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
								<FormLabel>
									{__('Lost Password', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - Lost password" page',
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
								<FormLabel>
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
								<FormLabel>
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
								<FormLabel>
									{__('Order Received', 'masteriyo')}
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
								<FormLabel>
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
								<FormLabel>
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
								<FormLabel>
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
								<Alert status="warning">
									<AlertIcon />
									{__(
										'This section is for the development purpose and not recommended for live site.',
										'masteriyo'
									)}
								</Alert>
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
