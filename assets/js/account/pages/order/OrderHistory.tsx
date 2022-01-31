import { Heading, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import urls from '../../../back-end/constants/urls';
import NoOrdersNotice from '../../../back-end/screens/orders/components/NoOrdersNotice';
import { SkeletonOrdersList } from '../../../back-end/skeleton';
import API from '../../../back-end/utils/api';
import OrderList from '../../components/OrderList';

const OrderHistory: React.FC = () => {
	const ordersAPI = new API(urls.orders);
	const ordersQuery = useQuery('ordersList', () => ordersAPI.list());

	return (
		<Stack direction="column" spacing="8" width="full">
			<Heading as="h4" size="md" fontWeight="bold" color="blue.900">
				{__('Order History', 'masteriyo')}
			</Heading>

			<Table>
				<Thead>
					<Tr>
						<Th>{__('Order', 'masteriyo')}</Th>
						<Th>{__('Course', 'masteriyo')}</Th>
						<Th>{__('Date', 'masteriyo')}</Th>
						<Th>{__('Status', 'masteriyo')}</Th>
						<Th>{__('Total', 'masteriyo')}</Th>
					</Tr>
				</Thead>
				<Tbody>
					{ordersQuery.isLoading && <SkeletonOrdersList />}
					{ordersQuery.isSuccess && ordersQuery?.data?.data.length === 0 ? (
						<NoOrdersNotice />
					) : (
						ordersQuery?.data?.data.map((order: any) => (
							<OrderList key={order.id} data={order} />
						))
					)}
				</Tbody>
			</Table>
		</Stack>
	);
};

export default OrderHistory;
