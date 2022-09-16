import {
	Alert,
	AlertIcon,
	Box,
	Code,
	Flex,
	FormLabel,
	Icon,
	Input,
	Radio,
	RadioGroup,
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
import FormControlTwoCol from '../../../components/common/FormControlTwoCol';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { AdvancedSettingsMap, SetttingsMap } from '../../../types';
import localized from '../../../utils/global';

interface Props {
	advanceData?: AdvancedSettingsMap;
}

const AdvancedSettings: React.FC<Props> = (props) => {
	const { advanceData } = props;
	const { register } = useFormContext();

	const watchPermalinkData = useWatch<SetttingsMap>({
		name: 'advance.permalinks',
		defaultValue: {
			category_base: advanceData?.permalinks?.category_base,
			difficulty_base: advanceData?.permalinks?.difficulty_base,
		},
	});

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Permalinks', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Account Endpoints', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Checkout Endpoints', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Debug', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControlTwoCol>
								<FormLabel>{__('Course Category Base', 'masteriyo')}</FormLabel>
								<Stack direction="column">
									<Input
										type="text"
										defaultValue={advanceData?.permalinks?.category_base}
										{...register('advance.permalinks.category_base')}
									/>
									<Code>
										{localized.home_url}/{watchPermalinkData.category_base}/
										{__('uncategorized', 'masteriyo')}
									</Code>
								</Stack>
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Course Difficulty Base', 'masteriyo')}
								</FormLabel>
								<Stack direction="column">
									<Input
										type="text"
										{...register('advance.permalinks.difficulty_base')}
										defaultValue={advanceData?.permalinks?.difficulty_base}
									/>
									<Code>
										{localized.home_url}/{watchPermalinkData.difficulty_base}
										{__('/uncategorized', 'masteriyo')}
									</Code>
								</Stack>
							</FormControlTwoCol>

							<FormControlTwoCol>
								<Stack direction="column">
									<FormLabel>
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
													<Radio value="course" w="100%">
														<Flex flexDirection="column">
															<Text fontSize="sm">
																{__('Default', 'masteriyo')}
															</Text>
															<Code flex="1">
																{localized.home_url}
																{__('?course=sample-course', 'masteriyo')}
															</Code>
														</Flex>
													</Radio>
													{/** TS */}
													<Radio value={localized.pageSlugs.courses}>
														<Flex flexDirection="column">
															<Text fontSize="sm">
																{__('Courses page base', 'masteriyo')}
															</Text>
															<Code flex="1">
																{localized.home_url}/
																{localized.pageSlugs.courses}
																{__('/sample-course', 'masteriyo')}
															</Code>
														</Flex>
													</Radio>
													<Radio
														value={
															localized.pageSlugs.courses + '/%course_cat%/'
														}>
														<Flex flexDirection="column">
															<Text fontSize="sm">
																{__(
																	'Courses page base with category',
																	'masteriyo'
																)}
															</Text>
															<Code flex="1">
																{localized.home_url}/
																{localized.pageSlugs.courses}
																{__(
																	'/course-category/sample-course',
																	'masteriyo'
																)}
															</Code>
														</Flex>
													</Radio>
												</Stack>
											</RadioGroup>
										)}
									/>
								</Stack>
							</FormControlTwoCol>
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
							<FormControlTwoCol>
								<FormLabel>
									{__('Orders', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - Orders" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('View Order', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - View order" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('My Courses', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - My courses" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Edit Account', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - Edit Account" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Lost Password', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Account - Lost password" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Logout', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the triggering logout.',
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
							</FormControlTwoCol>
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

							<FormControlTwoCol>
								<FormLabel>
									{__('Pay', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Checkout - Pay" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Order Received', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Checkout - Order received" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Add Payment Method', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the "Checkout - Add payment method" page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Delete Payment Method', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the delete payment method page.',
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
							</FormControlTwoCol>

							<FormControlTwoCol>
								<FormLabel>
									{__('Set Default Payment Method', 'masteriyo')}
									<Tooltip
										label={__(
											'Endpoint for the setting a default payment method page.',
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
							</FormControlTwoCol>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="column" spacing="8">
								<Alert status="warning" display={['none', 'flex', 'flex']}>
									<AlertIcon />
									{__(
										"This section is for development and testing purpose only. It's not recommended to be used in a live site.",
										'masteriyo'
									)}
								</Alert>
								<FormControlTwoCol>
									<Stack direction={['column', 'row', 'row']}>
										<FormLabel minW="3xs">
											{__('Template Debug', 'masteriyo')}
											<Tooltip
												label={__(
													'Enable it for template debugging.',
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
											name="advance.debug.template_debug"
											render={({ field }) => (
												<Switch
													{...field}
													defaultChecked={advanceData?.debug?.template_debug}
												/>
											)}
										/>
									</Stack>
								</FormControlTwoCol>

								<FormControlTwoCol>
									<Stack direction={['column', 'row', 'row']}>
										<FormLabel minW="3xs">
											{__('Debug', 'masteriyo')}
											<Tooltip
												label={__(
													'Enable it for plugin debugging.',
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
											name="advance.debug.debug"
											render={({ field }) => (
												<Switch
													{...field}
													defaultChecked={advanceData?.debug?.debug}
												/>
											)}
										/>
									</Stack>
								</FormControlTwoCol>
							</Stack>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default AdvancedSettings;
