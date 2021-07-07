import {
	Collapse,
	FormControl,
	FormLabel,
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
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { PaymentsSettingsMap } from '../../../types';

interface Props {
	paymentsData?: PaymentsSettingsMap;
}

const PaymentsSettings: React.FC<Props> = (props) => {
	const { paymentsData } = props;
	const { register, control } = useFormContext();
	const showPayPalOptions = useWatch({
		name: 'payments.paypal.enable',
		defaultValue: paymentsData?.paypal.enable,
		control,
	});

	const showPayPalSandBoxOptions = useWatch({
		name: 'payments.paypal.sandbox',
		defaultValue: paymentsData?.paypal.sandbox,
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
					<Tab sx={tabStyles}>PayPal</Tab>
					<Tab sx={tabStyles}>PayPal Express</Tab>
				</TabList>
				<TabPanels flex="1">
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
											defaultValue={paymentsData?.paypal?.title}
											{...register('payments.paypal.title')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Description', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={paymentsData?.paypal?.description}
											{...register('payments.paypal.description')}
										/>
									</FormControl>

									<FormControl>
										<Stack direction="row">
											<FormLabel minW="160px">
												{__('Ipn Email Notification', 'masteriyo')}
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
											defaultValue={paymentsData?.paypal?.email}
											{...register('payments.paypal.email')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Reciever Email', 'masteriyo')}
										</FormLabel>
										<Input
											type="email"
											defaultValue={paymentsData?.paypal?.receiver_email}
											{...register('payments.paypal.receiver_email')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Indentity Token', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={paymentsData?.paypal?.identity_token}
											{...register('payments.paypal.identity_token')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Invoice Prefix', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={paymentsData?.paypal?.invoice_prefix}
											{...register('payments.paypal.invoice_prefix')}
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
											defaultValue={paymentsData?.paypal?.image_url}
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
													defaultValue={
														paymentsData?.paypal?.sandbox_api_username
													}
													{...register('payments.paypal.sandbox_api_username')}
												/>
											</FormControl>

											<FormControl>
												<FormLabel minW="160px">
													{__('Sandbox API Password', 'masteriyo')}
												</FormLabel>
												<Input
													type="password"
													defaultValue={
														paymentsData?.paypal?.sandbox_api_password
													}
													{...register('payments.paypal.sandbox_api_password')}
												/>
											</FormControl>

											<FormControl>
												<FormLabel minW="160px">
													{__('Sandbox API Signature', 'masteriyo')}
												</FormLabel>
												<Input
													type="text"
													defaultValue={
														paymentsData?.paypal?.sandbox_api_signature
													}
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
											defaultValue={paymentsData?.paypal?.live_api_username}
											{...register('payments.paypal.live_api_username')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Live API Password', 'masteriyo')}
										</FormLabel>
										<Input
											type="password"
											defaultValue={paymentsData?.paypal?.live_api_password}
											{...register('payments.paypal.live_api_password')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Live API Signature', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={paymentsData?.paypal?.live_api_signature}
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
