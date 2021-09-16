import {
	Box,
	FormControl,
	FormLabel,
	Icon,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { EmailsSetttingsMap } from '../../../types';

interface Props {
	emailData?: EmailsSetttingsMap;
}

const EmailSetttings: React.FC<Props> = (props) => {
	const { emailData } = props;

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('New Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Completed Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Onhold Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Cancelled Order', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
										<Tooltip
											label={__(
												'New order emails are sent to students when a new order is received.',
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
										name="emails.new_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.new_order?.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
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
												'Order complete emails are sent to students when their orders are marked completed.',
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
										name="emails.completed_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.completed_order?.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
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
												'This is an order notification sent to students containing order details after an order is placed on-hold.',
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
										name="emails.onhold_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.onhold_order?.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
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
												'Cancelled order emails are sent to students when orders have been marked cancelled (if they were previously processing or on-hold).',
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
										name="emails.cancelled_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.cancelled_order?.enable}
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

export default EmailSetttings;
