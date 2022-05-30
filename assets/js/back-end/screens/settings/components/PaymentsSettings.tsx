import {
	Box,
	Collapse,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Icon,
	Input,
	Select,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Text,
	Textarea,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { useQuery } from 'react-query';
import FullScreenLoader from '../../../components/layout/FullScreenLoader';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import urls from '../../../constants/urls';
import {
	CountriesSchema,
	CurrenciesSchema,
	StatesSchema,
} from '../../../schemas';
import { PaymentsSettingsMap } from '../../../types';
import API from '../../../utils/api';
import { hasNumber, isEmpty } from '../../../utils/utils';

interface Props {
	paymentsData?: PaymentsSettingsMap;
}

const PaymentsSettings: React.FC<Props> = (props) => {
	const { paymentsData } = props;

	const countriesAPI = new API(urls.countries);
	const currenciesAPI = new API(urls.currencies);
	const statesAPI = new API(urls.states);
	const countriesQuery = useQuery('countries', () => countriesAPI.list());
	const currenciesQuery = useQuery('currencies', () => currenciesAPI.list());
	const statesQuery = useQuery('states', () => statesAPI.list());

	const {
		register,
		control,
		formState: { errors },
	} = useFormContext();

	const watchSelectedCountry = useWatch({
		name: 'payments.store.country',
		defaultValue: paymentsData?.store.country,
		control,
	});

	const watchNoOfDecimals = useWatch({
		name: 'payments.currency.number_of_decimals',
		defaultValue: paymentsData?.currency.number_of_decimals,
		control,
	});

	const showPayPalOptions = useWatch({
		name: 'payments.paypal.enable',
		defaultValue: paymentsData?.paypal?.enable,
		control,
	});

	const showPayPalSandBoxOptions = useWatch({
		name: 'payments.paypal.sandbox',
		defaultValue: paymentsData?.paypal?.sandbox,
		control,
	});

	const showOfflineOptions = useWatch({
		name: 'payments.offline.enable',
		defaultValue: paymentsData?.offline?.enable,
		control,
	});

	if (
		countriesQuery?.isSuccess &&
		currenciesQuery?.isSuccess &&
		statesQuery?.isSuccess
	) {
		const matchCountriesData = statesQuery?.data.filter(
			(statesData: StatesSchema) => statesData.country === watchSelectedCountry
		);

		return (
			<Tabs orientation="vertical">
				<Stack direction="row" flex="1">
					<TabList sx={tabListStyles}>
						<Tab sx={tabStyles}>{__('Store', 'masteriyo')}</Tab>
						<Tab sx={tabStyles}>{__('Currency', 'masteriyo')}</Tab>
						<Tab sx={tabStyles}>{__('Standard Paypal', 'masteriyo')}</Tab>
						<Tab sx={tabStyles}>{__('Offline Payment', 'masteriyo')}</Tab>
					</TabList>
					<TabPanels flex="1">
						<TabPanel>
							<Stack direction="column" spacing="6">
								<Stack direction="row" spacing="8">
									<FormControl>
										<FormLabel>{__('Country', 'masteriyo')}</FormLabel>

										<Select
											{...register('payments.store.country')}
											defaultValue={paymentsData?.store?.country}>
											{countriesQuery?.data.map((country: CountriesSchema) => (
												<option value={country.code} key={country.code}>
													{country.name}
												</option>
											))}
										</Select>
									</FormControl>
									<FormControl>
										<FormLabel>{__('State', 'masteriyo')}</FormLabel>

										<Select
											{...register('payments.store.state')}
											defaultValue={paymentsData?.store?.state}>
											{!isEmpty(matchCountriesData) ? (
												matchCountriesData[0].states.map(
													(stateData: { code: string; name: string }) => (
														<option value={stateData.code} key={stateData.code}>
															{stateData.name}
														</option>
													)
												)
											) : (
												<option>{__('No state found', 'masteriyo')}</option>
											)}
										</Select>
									</FormControl>
								</Stack>
								<Stack direction="row" spacing="8">
									<FormControl>
										<FormLabel>{__('City', 'masteriyo')}</FormLabel>

										<Input
											type="text"
											{...register('payments.store.city')}
											defaultValue={paymentsData?.store?.city}
										/>
									</FormControl>
									<FormControl>
										<FormLabel>{__('Address Line 1', 'masteriyo')}</FormLabel>
										<Input
											type="text"
											{...register('payments.store.address_line1')}
											defaultValue={paymentsData?.store?.address_line1}
										/>
									</FormControl>
								</Stack>
								<FormControl>
									<FormLabel>{__('Address Line 2', 'masteriyo')}</FormLabel>
									<Input
										type="text"
										{...register('payments.store.address_line2')}
										defaultValue={paymentsData?.store?.address_line2}
									/>
								</FormControl>
							</Stack>
						</TabPanel>
						<TabPanel>
							<Stack direction="column" spacing="6">
								<Stack direction="row" spacing="8">
									<FormControl>
										<FormLabel>
											{__('Currency', 'masteriyo')}
											<Tooltip
												label={__('Select default currency.', 'masteriyo')}
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
											{currenciesQuery?.data.map(
												(currency: CurrenciesSchema) => (
													<option value={currency.code} key={currency.code}>
														{currency.name} ({currency.symbol})
													</option>
												)
											)}
										</Select>
									</FormControl>
									<FormControl>
										<FormLabel>
											{__('Currency Position', 'masteriyo')}
											<Tooltip
												label={__(
													'Specifies where the currency symbol will appear.',
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
											{...register('payments.currency.currency_position')}
											defaultValue={paymentsData?.currency?.currency_position}>
											<option value="left">
												{__('Left ($99.99)', 'masteriyo')}
											</option>
											<option value="right">
												{__('Right (99.99$)', 'masteriyo')}
											</option>
											<option value="left_space">
												{__('Left Space ($ 99.99)', 'masteriyo')}
											</option>
											<option value="right_space">
												{__('Right Space  (99.99 $)', 'masteriyo')}
											</option>
										</Select>
									</FormControl>
								</Stack>
								<Stack direction="row" spacing="8">
									<FormControl
										isInvalid={
											!!errors?.payments?.currency?.thousand_separator
										}>
										<FormLabel>
											{__('Thousand Separator', 'masteriyo')}
											<Tooltip
												label={__(
													"It can't be a number and same as decimal separator.",
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
											{...register('payments.currency.thousand_separator', {
												maxLength: {
													value: 1,
													message: __(
														'Thousand separator should be 1 character only.',
														'masteriyo'
													),
												},
												required: __(
													'Thousand separator is required.',
													'masteriyo'
												),
												validate: (value) =>
													hasNumber(value) ||
													__(
														"Thousand separator can't be a number.",
														'masteriyo'
													),
											})}
											defaultValue={paymentsData?.currency?.thousand_separator}
										/>
										<FormErrorMessage>
											{errors?.payments?.currency?.thousand_separator &&
												errors?.payments?.currency?.thousand_separator?.message}
										</FormErrorMessage>
									</FormControl>
									<FormControl
										isInvalid={!!errors?.payments?.currency?.decimal_separator}>
										<FormLabel>
											{__('Decimal Separator', 'masteriyo')}
											<Tooltip
												label={__(
													"It can't be a number and same as thousand separator.",
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
											{...register('payments.currency.decimal_separator', {
												required: __(
													'Decimal separator is required.',
													'masteriyo'
												),
												maxLength: {
													value: 1,
													message: __(
														'Decimal separator should be 1 character only.',
														'masteriyo'
													),
												},
												validate: (value) =>
													hasNumber(value) ||
													__(
														"Decimal separator can't be a number.",
														'masteriyo'
													),
											})}
											defaultValue={paymentsData?.currency?.decimal_separator}
										/>
										<FormErrorMessage>
											{errors?.payments?.currency?.decimal_separator &&
												errors?.payments?.currency?.decimal_separator.message}
										</FormErrorMessage>
									</FormControl>
								</Stack>
								<Stack direction="row" spacing="8">
									<FormControl
										isInvalid={
											!!errors?.payments?.currency?.number_of_decimals
										}>
										<FormLabel>
											{__('Number of Decimals', 'masteriyo')}
											<Tooltip
												label={__(
													'Number of digits to show on fractional part. Maximum limit is 10.',
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
											name="payments.currency.number_of_decimals"
											defaultValue={paymentsData?.currency?.number_of_decimals}
											rules={{
												required: __(
													'Number of decimals is required.',
													'masteriyo'
												),
											}}
											render={({ field }) => (
												<Slider
													{...field}
													aria-label="course-per-page"
													defaultValue={watchNoOfDecimals}
													max={5}
													min={0}>
													<SliderTrack>
														<SliderFilledTrack />
													</SliderTrack>
													<SliderThumb boxSize="6" bgColor="blue.500">
														<Text
															fontSize="xs"
															fontWeight="semibold"
															color="white">
															{watchNoOfDecimals}
														</Text>
													</SliderThumb>
												</Slider>
											)}
										/>
										<FormErrorMessage>
											{errors?.payments?.currency?.number_of_decimals &&
												errors?.payments?.currency?.number_of_decimals.message}
										</FormErrorMessage>
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
												label={__(
													'Use Standard PayPal on checkout.',
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
												<Tooltip
													textAlign="center"
													label={__(
														'The title of payment method which the user sees during checkout.',
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
												{...register('payments.paypal.title')}
												defaultValue={paymentsData?.paypal?.title}
											/>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Description', 'masteriyo')}
												<Tooltip
													textAlign="center"
													label={__(
														'The description of payment method which the user sees during checkout.',
														'masteriyo'
													)}
													hasArrow
													fontSize="xs">
													<Box as="span" sx={infoIconStyles}>
														<Icon as={BiInfoCircle} />
													</Box>
												</Tooltip>
											</FormLabel>
											<Textarea
												{...register('payments.paypal.description')}
												defaultValue={paymentsData?.paypal?.description}
											/>
										</FormControl>

										<FormControl>
											<Stack direction="row">
												<FormLabel minW="160px">
													{__('IPN Email Notification', 'masteriyo')}
													<Tooltip
														textAlign="center"
														label={__(
															'Send notifications when an IPN is received from PayPal indicating refunds, charge-backs, and cancellations.',
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
												{__('Paypal Email', 'masteriyo')}
												<Tooltip
													textAlign="center"
													label={__(
														'Please enter your PayPal email address; this is needed in order to take payment.',
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
												type="email"
												{...register('payments.paypal.email')}
												defaultValue={paymentsData?.paypal?.email}
											/>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Receiver Email', 'masteriyo')}
												<Tooltip
													textAlign="center"
													label={__(
														'If your main PayPal email differs from the PayPal email entered above, input your main receiver email for your PayPal account here. This is used to validate IPN requests. If your main PayPal email differs from the PayPal email entered above, input your main receiver email for your PayPal account here. This is used to validate IPN requests.',
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
												type="email"
												{...register('payments.paypal.receiver_email')}
												defaultValue={paymentsData?.paypal?.receiver_email}
											/>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Identity Token', 'masteriyo')}
												<Tooltip
													textAlign="center"
													label={__(
														'Optionally enable "Payment Data Transfer" (Profile > Profile and Settings > My Selling Tools > Website Preferences) and then copy your identity token here. This will allow payments to be verified without the need for PayPal IPN.',
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
												{...register('payments.paypal.identity_token')}
												defaultValue={paymentsData?.paypal?.identity_token}
											/>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Invoice Prefix', 'masteriyo')}
												<Tooltip
													textAlign="center"
													label={__(
														'If you use your PayPal account with more than one installation, please use a distinct prefix to separate those installations. Please do not use numbers in your prefix.',
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
												{...register('payments.paypal.invoice_prefix')}
												defaultValue={paymentsData?.paypal?.invoice_prefix}
											/>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Payment Actions', 'masteriyo')}
												<Tooltip
													textAlign="center"
													label={__(
														`The intent to either capture payment immediately or authorize a payment for an order after order creation.`,
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
												placeholder={__('Select Payment Action', 'masteriyo')}
												defaultValue={paymentsData?.paypal?.payment_action}
												{...register('payments.paypal.payment_action')}>
												<option value="capture">
													{__('Capture', 'masteriyo')}
												</option>
												<option value="authorize">
													{__('Authorize', 'masteriyo')}
												</option>
											</Select>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Image URL', 'masteriyo')}
												<Tooltip
													textAlign="center"
													label={__(
														`Optionally enter the URL to a 150x50px image displayed as your logo in the upper left corner of the PayPal checkout pages.`,
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
												placeholder="Optional"
												type="text"
												{...register('payments.paypal.image_url')}
												defaultValue={paymentsData?.paypal?.image_url}
											/>
										</FormControl>

										<FormControl>
											<Stack direction="row">
												<FormLabel minW="160px">
													{__('Debug Log', 'masteriyo')}
													<Tooltip
														textAlign="center"
														label={__(
															'Note: This may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.',
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
															'PayPal sandbox can be used to test payments.',
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
														<Tooltip
															label={__(
																'Get your API credentials from PayPal.',
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
														placeholder="Optional"
														type="text"
														{...register(
															'payments.paypal.sandbox_api_username'
														)}
														defaultValue={
															paymentsData?.paypal?.sandbox_api_username
														}
													/>
												</FormControl>

												<FormControl>
													<FormLabel minW="160px">
														{__('Sandbox API Password', 'masteriyo')}
														<Tooltip
															label={__(
																'Get your API credentials from PayPal.',
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
														placeholder="Optional"
														type="password"
														{...register(
															'payments.paypal.sandbox_api_password'
														)}
														defaultValue={
															paymentsData?.paypal?.sandbox_api_password
														}
													/>
												</FormControl>

												<FormControl>
													<FormLabel minW="160px">
														{__('Sandbox API Signature', 'masteriyo')}
														<Tooltip
															label={__(
																'Get your API credentials from PayPal.',
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
														placeholder="Optional"
														type="text"
														{...register(
															'payments.paypal.sandbox_api_signature'
														)}
														defaultValue={
															paymentsData?.paypal?.sandbox_api_signature
														}
													/>
												</FormControl>
											</Stack>
										</Collapse>

										<Collapse in={!showPayPalSandBoxOptions}>
											<Stack direction="column" spacing="6">
												<FormControl>
													<FormLabel minW="160px">
														{__('Live API Username', 'masteriyo')}
														<Tooltip
															label={__(
																'Get your API credentials from PayPal.',
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
														placeholder="Optional"
														type="text"
														{...register('payments.paypal.live_api_username')}
														defaultValue={
															paymentsData?.paypal?.live_api_username
														}
													/>
												</FormControl>

												<FormControl>
													<FormLabel minW="160px">
														{__('Live API Password', 'masteriyo')}
														<Tooltip
															label={__(
																'Get your API credentials from PayPal.',
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
														placeholder="Optional"
														type="password"
														{...register('payments.paypal.live_api_password')}
														defaultValue={
															paymentsData?.paypal?.live_api_password
														}
													/>
												</FormControl>

												<FormControl>
													<FormLabel minW="160px">
														{__('Live API Signature', 'masteriyo')}
														<Tooltip
															label={__(
																'Get your API credentials from PayPal.',
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
														placeholder="Optional"
														type="text"
														{...register('payments.paypal.live_api_signature')}
														defaultValue={
															paymentsData?.paypal?.live_api_signature
														}
													/>
												</FormControl>
											</Stack>
										</Collapse>
									</Stack>
								</Collapse>
							</Stack>
						</TabPanel>
						<TabPanel>
							<Stack direction="column" spacing="6">
								<FormControl>
									<Stack direction="row">
										<FormLabel minW="160px">
											{__('Enable', 'masteriyo')}
											<Tooltip
												label={__(
													'Use offline payment on checkout.',
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
											name="payments.offline.enable"
											render={({ field }) => (
												<Switch
													{...field}
													defaultChecked={paymentsData?.offline?.enable}
												/>
											)}
										/>
									</Stack>
								</FormControl>

								<Collapse in={showOfflineOptions} animateOpacity>
									<Stack direction="column" spacing="6">
										<FormControl>
											<FormLabel minW="160px">
												{__('Title', 'masteriyo')}
											</FormLabel>
											<Input
												type="text"
												{...register('payments.offline.title')}
												defaultValue={paymentsData?.offline?.title}
											/>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Description', 'masteriyo')}
											</FormLabel>
											<Textarea
												{...register('payments.offline.description')}
												defaultValue={paymentsData?.offline?.description}
											/>
										</FormControl>

										<FormControl>
											<FormLabel minW="160px">
												{__('Instructions', 'masteriyo')}
											</FormLabel>
											<Textarea
												{...register('payments.offline.instructions')}
												defaultValue={paymentsData?.offline?.instructions}
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
	}

	return <FullScreenLoader />;
};

export default PaymentsSettings;
