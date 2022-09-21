import {
	Box,
	Collapse,
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
import FormControlTwoCol from '../../../components/common/FormControlTwoCol';
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
								<FormControlTwoCol>
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
								</FormControlTwoCol>
								<FormControlTwoCol>
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
								</FormControlTwoCol>
								<FormControlTwoCol>
									<FormLabel>{__('City', 'masteriyo')}</FormLabel>
									<Input
										type="text"
										{...register('payments.store.city')}
										defaultValue={paymentsData?.store?.city}
									/>
								</FormControlTwoCol>
								<FormControlTwoCol>
									<FormLabel>{__('Address Line 1', 'masteriyo')}</FormLabel>
									<Input
										type="text"
										{...register('payments.store.address_line1')}
										defaultValue={paymentsData?.store?.address_line1}
									/>
								</FormControlTwoCol>
								<FormControlTwoCol>
									<FormLabel>{__('Address Line 2', 'masteriyo')}</FormLabel>
									<Input
										type="text"
										{...register('payments.store.address_line2')}
										defaultValue={paymentsData?.store?.address_line2}
									/>
								</FormControlTwoCol>
							</Stack>
						</TabPanel>
						<TabPanel>
							<Stack direction="column" spacing="6">
								<FormControlTwoCol>
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
										{currenciesQuery?.data.map((currency: CurrenciesSchema) => (
											<option value={currency.code} key={currency.code}>
												{currency.name} ({currency.symbol})
											</option>
										))}
									</Select>
								</FormControlTwoCol>
								<FormControlTwoCol>
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
								</FormControlTwoCol>
								<FormControlTwoCol>
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
								</FormControlTwoCol>

								<FormControlTwoCol>
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
												__("Decimal separator can't be a number.", 'masteriyo'),
										})}
										defaultValue={paymentsData?.currency?.decimal_separator}
									/>
									<FormErrorMessage>
										{errors?.payments?.currency?.decimal_separator &&
											errors?.payments?.currency?.decimal_separator.message}
									</FormErrorMessage>
								</FormControlTwoCol>

								<FormControlTwoCol>
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
												<SliderThumb boxSize="6" bgColor="primary.500">
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
								</FormControlTwoCol>
							</Stack>
						</TabPanel>
						<TabPanel>
							<Stack direction="column" spacing="6">
								<FormControlTwoCol>
									<FormLabel>
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
												w="100%"
												{...field}
												defaultChecked={paymentsData?.paypal?.enable}
											/>
										)}
									/>
								</FormControlTwoCol>

								<Collapse in={showPayPalOptions} animateOpacity>
									<Stack direction="column" spacing="6">
										<FormControlTwoCol>
											<FormLabel>
												{__('Title', 'masteriyo')}
												<Tooltip
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Description', 'masteriyo')}
												<Tooltip
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('IPN Email Notification', 'masteriyo')}
												<Tooltip
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
														w="100%"
														{...field}
														defaultChecked={
															paymentsData?.paypal?.ipn_email_notifications
														}
													/>
												)}
											/>
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Paypal Email', 'masteriyo')}
												<Tooltip
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Receiver Email', 'masteriyo')}
												<Tooltip
													label={__(
														'If your main PayPal email differs from the PayPal email entered above, input your main receiver email for your PayPal account here. This is used to validate IPN requests.',
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Identity Token', 'masteriyo')}
												<Tooltip
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Invoice Prefix', 'masteriyo')}
												<Tooltip
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Payment Actions', 'masteriyo')}
												<Tooltip
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Image URL', 'masteriyo')}
												<Tooltip
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
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
												{__('Debug Log', 'masteriyo')}
												<Tooltip
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
														w="100%"
														{...field}
														defaultChecked={paymentsData?.paypal?.debug}
													/>
												)}
											/>
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>
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
														w="100%"
														{...field}
														defaultChecked={paymentsData?.paypal?.sandbox}
													/>
												)}
											/>
										</FormControlTwoCol>

										<Collapse in={showPayPalSandBoxOptions}>
											<Stack direction="column" spacing="6">
												<FormControlTwoCol>
													<FormLabel>
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
												</FormControlTwoCol>
												<FormControlTwoCol>
													<FormLabel>
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
												</FormControlTwoCol>
												<FormControlTwoCol>
													<FormLabel>
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
												</FormControlTwoCol>
											</Stack>
										</Collapse>

										<Collapse in={!showPayPalSandBoxOptions}>
											<Stack direction="column" spacing="6">
												<FormControlTwoCol>
													<FormLabel>
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
												</FormControlTwoCol>
												<FormControlTwoCol>
													<FormLabel>
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
												</FormControlTwoCol>
												<FormControlTwoCol>
													<FormLabel>
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
												</FormControlTwoCol>
											</Stack>
										</Collapse>
									</Stack>
								</Collapse>
							</Stack>
						</TabPanel>
						<TabPanel>
							<Stack direction="column" spacing="6">
								<FormControlTwoCol>
									<FormLabel>
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
												w="100%"
												{...field}
												defaultChecked={paymentsData?.offline?.enable}
											/>
										)}
									/>
								</FormControlTwoCol>

								<Collapse in={showOfflineOptions} animateOpacity>
									<Stack direction="column" spacing="6">
										<FormControlTwoCol>
											<FormLabel>{__('Title', 'masteriyo')}</FormLabel>
											<Input
												type="text"
												{...register('payments.offline.title')}
												defaultValue={paymentsData?.offline?.title}
											/>
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>{__('Description', 'masteriyo')}</FormLabel>
											<Textarea
												{...register('payments.offline.description')}
												defaultValue={paymentsData?.offline?.description}
											/>
										</FormControlTwoCol>
										<FormControlTwoCol>
											<FormLabel>{__('Instructions', 'masteriyo')}</FormLabel>
											<Textarea
												{...register('payments.offline.instructions')}
												defaultValue={paymentsData?.offline?.instructions}
											/>
										</FormControlTwoCol>
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
