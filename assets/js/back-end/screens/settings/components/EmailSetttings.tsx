import {
	Stack,
	FormControl,
	FormLabel,
	Select,
	Input,
	Switch,
	Tabs,
	TabList,
	Tab,
	TabPanels,
	TabPanel,
	Textarea,
	Collapse,
	Box,
	Flex,
	Heading,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';

import { EmailsSetttingsMap } from '../../../types';

interface Props {
	emailData?: EmailsSetttingsMap;
}
const EmailSetttings: React.FC<Props> = (props) => {
	const { emailData } = props;
	const { register, control } = useFormContext();

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
					<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('New Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Processing Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Completed Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Onhold Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Cancelled Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Enrolled Course', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Completed Course', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Become and Instructor', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="10">
							<Box>
								<Stack direction="column" spacing="6">
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
										<FormLabel minW="160px">
											{__('From Name', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.general?.from_name}
											{...register('emails.general.from_name')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('From Email', 'masteriyo')}
										</FormLabel>
										<Input
											type="email"
											defaultValue={emailData?.general?.from_email}
											{...register('emails.general.from_email')}
										/>
									</FormControl>
								</Stack>
							</Box>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default EmailSetttings;
