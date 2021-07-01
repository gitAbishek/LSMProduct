import {
	Box,
	Container,
	Flex,
	Heading,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Header from 'Components/layout/Header';
import React from 'react';
import { useQuery } from 'react-query';
import urls from '../../constants/urls';
import { SkeletonOrdersList } from '../../skeleton';
import API from '../../utils/api';
import OrderRow from './components/OrderRow';

const AllOrders = () => {
	const ordersAPI = new API(urls.orders);
	const ordersQuery = useQuery('ordersList', () => ordersAPI.list());

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header hideAddNewCourseBtn={true} hideCoursesMenu={true} />
			<Container maxW="container.xl">
				<Box bg="white" p="12" shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<Flex justify="space-between" aling="center">
							<Heading as="h1" size="lg">
								{__('Orders', 'masteriyo')}
							</Heading>
						</Flex>

						<Table>
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
								{ordersQuery.isLoading && <SkeletonOrdersList />}
								{ordersQuery.isSuccess &&
									ordersQuery.data.map((order: any) => (
										<OrderRow key={order.id} data={order} />
									))}
							</Tbody>
						</Table>
					</Stack>
				</Box>
			</Container>
		</Stack>
	);
};

export default AllOrders;
