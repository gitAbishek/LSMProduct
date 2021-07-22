import {
	Box,
	Collapse,
	FormControl,
	FormLabel,
	Icon,
	Input,
	Select,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Textarea,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { infoIconStyles } from 'Config/styles';
import getSymbolFromCurrency from 'currency-symbol-map';
import React, { useEffect, useState } from 'react';
import ReactFlagsSelect from 'react-flags-select';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { PaymentsSettingsMap } from '../../../types';
import { currency } from '../../../utils/currency';

interface Props {
	paymentsData?: PaymentsSettingsMap;
}

const PaymentsSettings: React.FC<Props> = (props) => {
	const { paymentsData } = props;
	const { register, control, setValue } = useFormContext();
	const [country, setCountry] = useState(paymentsData?.store.country);

	const showPayPalOptions = useWatch({
		name: 'payments.paypal.enable',
		defaultValue: false,
		control,
	});

	const showPayPalSandBoxOptions = useWatch({
		name: 'payments.paypal.sandbox',
		defaultValue: true,
		control,
	});

	const tabStyles = {
		justifyContent: 'flex-start',
		w: '160px',
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'transparent',
		marginLeft: 0,
		marginRight: '-2px',
		pl: 0,
		fontSize: 'sm',
	};

	const tabListStyles = {
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'gray.200',
	};

	useEffect(() => {
		setValue('payments.store.country', country);
	}, [country, setValue]);

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Store', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Currency', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Standard Paypal', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel>
										{__('Country', 'masteriyo')}
										<Tooltip
											label={__('Country where you live', 'masteriyo')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>

									<input
										type="hidden"
										{...register('payments.store.country')}
										defaultValue={paymentsData?.store?.country}
									/>
									<ReactFlagsSelect
										selected={country || ''}
										onSelect={(code) => setCountry(code)}
									/>
								</FormControl>
								<FormControl>
									<FormLabel>
										{__('City', 'masteriyo')}
										<Tooltip
											label={__(
												'Your city where you are residing',
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
										{...register('payments.store.city')}
										defaultValue={paymentsData?.store?.city}
									/>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel>
										{__('Adress Line 1', 'masteriyo')}
										<Tooltip
											label={__('Your street address')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Input
										type="text"
										{...register('payments.store.address_line1')}
										defaultValue={paymentsData?.store?.address_line1}
									/>
								</FormControl>
								<FormControl>
									<FormLabel>
										{__('Adress Line 2', 'masteriyo')}
										<Tooltip
											label={__('Your street address 2')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Input
										type="text"
										{...register('payments.store.address_line2')}
										defaultValue={paymentsData?.store?.address_line2}
									/>
								</FormControl>
							</Stack>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">
										{__('Currency', 'masteriyo')}
										<Tooltip
											label={__('Select default currency', 'masteriyo')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										{...register('payments.currency.currency')}
										defaultValue={paymentsData?.currency?.currency}>
										{Object.entries(currency).map(([code, name]) => (
											<option value={code} key={code}>
												{name} ({getSymbolFromCurrency(code)})
											</option>
										))}
									</Select>
								</FormControl>
								<FormControl>
									<FormLabel minW="xs">
										{__('Currency Position', 'masteriyo')}
										<Tooltip
											label={__(
												'Specifies where the currency symbol will appear',
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
										{...register('payments.store.currency_position')}
										defaultValue={paymentsData?.currency?.currency_position}>
										<option value="left">{__('Left', 'masteriyo')}</option>
										<option value="right">{__('Right', 'masteriyo')}</option>
									</Select>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">
										{__('Thousand Separator', 'masteriyo')}
										<Tooltip
											label={__(
												"It can't be a number and same as decimal separator",
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
										{...register('payments.currency.thousand_separator')}
										defaultValue={paymentsData?.currency?.thousand_separator}
									/>
								</FormControl>
								<FormControl>
									<FormLabel minW="xs">
										{__('Decimal Separator', 'masteriyo')}
										<Tooltip
											label={__(
												"It can't be a number and same as thousand separator",
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
										{...register('payments.currency.decimal_separator')}
										defaultValue={paymentsData?.currency?.decimal_separator}
									/>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">
										{__('Number of Decimals', 'masteriyo')}
										<Tooltip
											label={__(
												'Number of digit to show on fractional part',
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
										{...register('payments.currency.number_of_decimals')}
										defaultValue={paymentsData?.currency?.number_of_decimals}
									/>
								</FormControl>
							</Stack>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
										<Tooltip
											label={__('Use standard paypal on checkout', 'masteriyo')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Controller
										name="payments.paypal.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={paymentsData?.paypal?.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showPayPalOptions} animateOpacity>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Title', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.title')}
											defaultValue={paymentsData?.paypal?.title}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Description', 'masteriyo')}
										</FormLabel>
										<Textarea
											{...register('payments.paypal.description')}
											defaultValue={paymentsData?.paypal?.description}
										/>
									</FormControl>

									<FormControl>
										<Stack direction="row">
											<FormLabel minW="160px">
												{__('Ipn Email Notification', 'masteriyo')}
												<Tooltip
													label={__(
														'Get instant email notification after payment',
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
												name="payments.paypal.ipn_email_notifications"
												render={({ field }) => (
													<Switch
														{...field}
														defaultChecked={
															paymentsData?.paypal?.ipn_email_notifications
														}
													/>
												)}
											/>
										</Stack>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Email', 'masteriyo')}
										</FormLabel>
										<Input
											type="email"
											{...register('payments.paypal.email')}
											defaultValue={paymentsData?.paypal?.email}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Reciever Email', 'masteriyo')}
										</FormLabel>
										<Input
											type="email"
											{...register('payments.paypal.receiver_email')}
											defaultValue={paymentsData?.paypal?.receiver_email}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Indentity Token', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.identity_token')}
											defaultValue={paymentsData?.paypal?.identity_token}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Invoice Prefix', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.invoice_prefix')}
											defaultValue={paymentsData?.paypal?.invoice_prefix}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Payment Actions', 'masteriyo')}
										</FormLabel>
										<Select
											placeholder={__('Select Payment Action', 'masteriyo')}
											defaultValue={paymentsData?.paypal?.payment_action}
											{...register('payments.paypal.payment_action')}>
											<option value="capture">
												{__('Capture', 'masteriyo')}
											</option>
										</Select>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Image Url', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.image_url')}
											defaultValue={paymentsData?.paypal?.image_url}
										/>
									</FormControl>

									<FormControl>
										<Stack direction="row">
											<FormLabel minW="160px">
												{__('Debug', 'masteriyo')}
											</FormLabel>
											<Controller
												name="payments.paypal.debug"
												render={({ field }) => (
													<Switch
														{...field}
														defaultChecked={paymentsData?.paypal?.debug}
													/>
												)}
											/>
										</Stack>
									</FormControl>

									<FormControl>
										<Stack direction="row">
											<FormLabel minW="160px">
												{__('Sandbox', 'masteriyo')}
												<Tooltip
													label={__(
														'Standard paypal test environment',
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
												name="payments.paypal.sandbox"
												render={({ field }) => (
													<Switch
														{...field}
														defaultChecked={paymentsData?.paypal?.sandbox}
													/>
												)}
											/>
										</Stack>
									</FormControl>
									<Collapse in={showPayPalSandBoxOptions}>
										<Stack direction="column" spacing="6">
											<FormControl>
												<FormLabel minW="160px">
													{__('Sandbox API Username', 'masteriyo')}
												</FormLabel>
												<Input
													type="text"
													{...register('payments.paypal.sandbox_api_username')}
													defaultValue={
														paymentsData?.paypal?.sandbox_api_username
													}
												/>
											</FormControl>

											<FormControl>
												<FormLabel minW="160px">
													{__('Sandbox API Password', 'masteriyo')}
												</FormLabel>
												<Input
													type="password"
													{...register('payments.paypal.sandbox_api_password')}
													defaultValue={
														paymentsData?.paypal?.sandbox_api_password
													}
												/>
											</FormControl>

											<FormControl>
												<FormLabel minW="160px">
													{__('Sandbox API Signature', 'masteriyo')}
												</FormLabel>
												<Input
													type="text"
													{...register('payments.paypal.sandbox_api_signature')}
													defaultValue={
														paymentsData?.paypal?.sandbox_api_signature
													}
												/>
											</FormControl>
										</Stack>
									</Collapse>

									<FormControl>
										<FormLabel minW="160px">
											{__('Live API Username', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.live_api_username')}
											defaultValue={paymentsData?.paypal?.live_api_username}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Live API Password', 'masteriyo')}
										</FormLabel>
										<Input
											type="password"
											{...register('payments.paypal.live_api_password')}
											defaultValue={paymentsData?.paypal?.live_api_password}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Live API Signature', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.live_api_signature')}
											defaultValue={paymentsData?.paypal?.live_api_signature}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default PaymentsSettings;
