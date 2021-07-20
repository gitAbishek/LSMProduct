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
import React, { useState } from 'react';
import ReactFlagsSelect from 'react-flags-select';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { currency } from '../../../../utils/currency';

const PaymentsSettings: React.FC = () => {
	const { register, control } = useFormContext();
	const [country, setCountry] = useState('Nepal');

	const showPayPalOptions = useWatch({
		name: 'payments.paypal.enable',
		defaultValue: true,
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

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Store', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Currency', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Payment Methods', 'masteriyo')}</Tab>
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

									<input type="hidden" {...register('general.country')} />
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

									<Input type="text" {...register('general.city')} />
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
									<Input type="text" {...register('general.address_line1')} />
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
									<Input type="text" {...register('general.address_line2')} />
								</FormControl>
							</Stack>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">{__('Currency', 'masteriyo')}</FormLabel>
									<Select {...register('general.currency')}>
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
									</FormLabel>
									<Select {...register('general.currency_position')}>
										<option value="left">{__('Left', 'masteriyo')}</option>
										<option value="right">{__('Right', 'masteriyo')}</option>
									</Select>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">
										{__('Thousand Separator', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('general.thousand_separator')}
									/>
								</FormControl>
								<FormControl>
									<FormLabel minW="xs">
										{__('Decimal Separator', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('general.decimal_separator')}
									/>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">
										{__('Number of Decimals', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('general.number_of_decimals')}
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
										{__('Enabled', 'masteriyo')}
									</FormLabel>
									<Controller
										name="payments.paypal.enable"
										render={({ field }) => (
											<Switch {...field} defaultChecked={true} />
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
										<Input type="text" {...register('payments.paypal.title')} />
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Description', 'masteriyo')}
										</FormLabel>
										<Textarea {...register('payments.paypal.description')} />
									</FormControl>

									<FormControl>
										<Stack direction="row">
											<FormLabel minW="160px">
												{__('Ipn Email Notification', 'masteriyo')}
											</FormLabel>
											<Controller
												name="payments.paypal.ipn_email_notifications"
												render={({ field }) => (
													<Switch {...field} defaultChecked={true} />
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
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Reciever Email', 'masteriyo')}
										</FormLabel>
										<Input
											type="email"
											{...register('payments.paypal.receiver_email')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Indentity Token', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.identity_token')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Invoice Prefix', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.invoice_prefix')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Payment Actions', 'masteriyo')}
										</FormLabel>
										<Select
											placeholder={__('Select Payment Action', 'masteriyo')}
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
													<Switch {...field} defaultChecked={true} />
												)}
											/>
										</Stack>
									</FormControl>

									<FormControl>
										<Stack direction="row">
											<FormLabel minW="160px">
												{__('Sandbox', 'masteriyo')}
											</FormLabel>
											<Controller
												name="payments.paypal.sandbox"
												render={({ field }) => (
													<Switch {...field} defaultChecked={true} />
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
												/>
											</FormControl>

											<FormControl>
												<FormLabel minW="160px">
													{__('Sandbox API Password', 'masteriyo')}
												</FormLabel>
												<Input
													type="password"
													{...register('payments.paypal.sandbox_api_password')}
												/>
											</FormControl>

											<FormControl>
												<FormLabel minW="160px">
													{__('Sandbox API Signature', 'masteriyo')}
												</FormLabel>
												<Input
													type="text"
													{...register('payments.paypal.sandbox_api_signature')}
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
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Live API Password', 'masteriyo')}
										</FormLabel>
										<Input
											type="password"
											{...register('payments.paypal.live_api_password')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Live API Signature', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											{...register('payments.paypal.live_api_signature')}
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
