import { Heading, Stack } from '@chakra-ui/react';
import { sprintf, __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import { Table, Tbody, Td, Th, Thead, Tr } from 'react-super-responsive-table';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { OrderSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';

const OrderDetails: React.FC = () => {
	const ordersAPI = new API(urls.orders);
	const { orderId }: any = useParams();

	const orderQuery = useQuery<OrderSchema>([`myOrder${orderId}`, orderId], () =>
		ordersAPI.get(orderId)
	);

	console.log(orderQuery?.data);
	if (orderQuery.isSuccess) {
		return (
			<Stack direction="column" spacing="8" width="full">
				<Heading as="h4" size="md" fontWeight="bold" color="blue.900" px="8">
					{sprintf(__('Order #%s', 'masteriyo'), orderQuery?.data?.id)}
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
						<Tr key={orderQuery?.data?.id}>
							<Td>{orderQuery?.data?.date_created}</Td>
							<Td>
								{orderQuery?.data?.billing?.first_name}{' '}
								{orderQuery?.data?.billing?.last_name}
							</Td>
							<Td>{orderQuery?.data?.billing?.email}</Td>
							<Td>{orderQuery?.data?.payment_method}</Td>
							<Td>{orderQuery?.data?.transaction_id}</Td>
							<Td>{orderQuery?.data?.status}</Td>
						</Tr>
					</Tbody>
				</Table>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default OrderDetails;
