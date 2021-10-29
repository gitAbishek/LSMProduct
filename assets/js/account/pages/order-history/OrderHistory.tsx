import { Box, Container, Table, Tbody, Th, Thead, Tr } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import OrderHistoryItem from '../../components/OrderHistoryItem';
import data from '../../dummyData/OrderHistoryData';

const OrderHistory: React.FC = () => {
	return (
		<Container maxWidth="xl" py="12">
			<Box bg="white" shadow="box">
				<Table variant="simple">
					<Thead>
						<Tr>
							<Th>{__('Order', 'masteriyo')}</Th>
							<Th>{__('Date', 'masteriyo')}</Th>
							<Th>{__('Status', 'masteriyo')}</Th>
							<Th>{__('Total', 'masteriyo')}</Th>
							<Th>{__('Actions', 'masteriyo')}</Th>
						</Tr>
					</Thead>
					<Tbody>
						{data.map((itemProps, key) => {
							return <OrderHistoryItem key={key} {...itemProps} />;
						})}
					</Tbody>
				</Table>
			</Box>
		</Container>
	);
};

export default OrderHistory;
