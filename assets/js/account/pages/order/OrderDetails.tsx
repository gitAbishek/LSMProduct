import { Heading, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import { useLocation } from 'react-router-dom';
import { Table, Tbody, Td, Th, Thead, Tr } from 'react-super-responsive-table';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { OrderSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';

const OrderDetails: React.FC = () => {
	const ordersAPI = new API(urls.orders);
	const { orderId }: any = useLocation();

	const { data, isSuccess } = useQuery([`myOrder${orderId}`, orderId], () =>
		ordersAPI.get(orderId)
	);
	console.log(orderId);

	if (isSuccess) {
		return (
			<Stack direction="column" spacing="8" width="full">
				<Heading as="h4" size="md" fontWeight="bold" color="blue.900" px="8">
					{__('Order History', 'masteriyo')}
				</Heading>
				<Table>
					<Thead>
						<Tr>
							<Th>{__('Date Created', 'masteriyo')}</Th>
							<Th>{__('Name', 'masteriyo')}</Th>
							<Th>{__('Email', 'masteriyo')}</Th>

							<Th>{__('Payment Method', 'masteriyo')}</Th>

							<Th>{__('Transaction ID', 'masteriyo')}</Th>
							<Th>{__('Status', 'masteriyo')}</Th>
						</Tr>
					</Thead>
					<Tbody>
						{data?.data?.map((order: OrderSchema) => (
							<Tr key={order.id}>
								<Td>{order?.date_created}</Td>
								<Td>
									{order?.billing?.first_name} {order?.billing?.last_name}
								</Td>
								<Td>{order?.billing?.email}</Td>
								<Td>{order?.payment_method}</Td>
								<Td>{order?.transaction_id}</Td>
								<Td>{order?.status}</Td>
							</Tr>
						))}
					</Tbody>
				</Table>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default OrderDetails;
