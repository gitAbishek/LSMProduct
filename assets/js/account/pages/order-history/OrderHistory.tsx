import { Heading, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import OrderHistoryItem from '../../components/OrderHistoryItem';
import data from '../../dummyData/OrderHistoryData';

const OrderHistory: React.FC = () => {
	return (
		<Stack direction="column" spacing="8" width="full">
			<Heading as="h4" size="md" fontWeight="bold" color="blue.900" px="8">
				{__('Order History', 'masteriyo')}
			</Heading>
			<Table>
				<Thead>
					<Tr>
						<Th style={{ color: '#878F9D' }}>{__('Order', 'masteriyo')}</Th>
						<Th style={{ color: '#878F9D' }}>{__('Date', 'masteriyo')}</Th>
						<Th style={{ color: '#878F9D' }}>{__('Status', 'masteriyo')}</Th>
						<Th style={{ color: '#878F9D' }}>{__('Total', 'masteriyo')}</Th>
						<Th style={{ color: '#878F9D' }}>{__('Actions', 'masteriyo')}</Th>
					</Tr>
				</Thead>
				<Tbody>
					{data.map((itemProps, key) => {
						return <OrderHistoryItem key={key} {...itemProps} />;
					})}
				</Tbody>
			</Table>
		</Stack>
	);
};

export default OrderHistory;
