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
	const { register } = useFormContext();

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
						<Stack direction="column" spacing="8">
							<Flex
								align="center"
								justify="space-between"
								borderBottom="1px"
								borderColor="gray.100"
								pb="3">
								<Heading fontSize="lg" fontWeight="semibold">
									{__('General', 'masteriyo')}
								</Heading>
							</Flex>
							<FormControl>
								<Stack direction="row">
									<FormLabel>{__('Enabled', 'masteriyo')}</FormLabel>
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
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default PaymentsSettings;
