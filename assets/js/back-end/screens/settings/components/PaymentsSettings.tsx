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
	Switch,
	Tabs,
	TabList,
	Tab,
	TabPanels,
	TabPanel,
	Textarea,
	Collapse,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';

import { PaymentsSettingsMap } from '../../../types';

interface Props {
	paymentsData?: PaymentsSettingsMap;
}

const PaymentsSettings: React.FC<Props> = (props) => {
	const { paymentsData } = props;
	const { register, watch } = useFormContext();
	const showPayPalOptions = watch('payments.paypal.enable');

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
							<Collapse in={showPayPalOptions}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Title', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={paymentsData?.paypal?.title}
											{...register('paymentsData.paypal.title')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Description', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={paymentsData?.paypal?.description}
											{...register('paymentsData.paypal.description')}
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

									<FormControl>
										<FormLabel minW="160px">
											{__('Email', 'masteriyo')}
										</FormLabel>
										<Input
											type="email"
											defaultValue={paymentsData?.paypal?.email}
											{...register('paymentsData.paypal.email')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Reciever Email', 'masteriyo')}
										</FormLabel>
										<Input
											type="email"
											defaultValue={paymentsData?.paypal?.receiver_email}
											{...register('paymentsData.paypal.receiver_email')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Indentity Token', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={paymentsData?.paypal?.identity_token}
											{...register('paymentsData.paypal.identity_token')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Invoice Prefix', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={paymentsData?.paypal?.invoice_prefix}
											{...register('paymentsData.paypal.invoice_prefix')}
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
