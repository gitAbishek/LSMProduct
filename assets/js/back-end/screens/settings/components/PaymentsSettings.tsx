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

	return (
		<Stack direction="column" spacing="8">
			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('PayPal', 'masteriyo')}
						</Heading>
					</Flex>

					<FormControl>
						<Stack direction="row">
							<FormLabel minW="2xs">{__('Enabled', 'masteriyo')}</FormLabel>
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
			</Box>
		</Stack>
	);
};

export default PaymentsSettings;
