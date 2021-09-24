import {
	Box,
	Container,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiCart } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { NavLink } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonOrdersList } from '../../skeleton';
import API from '../../utils/api';
import OrderRow from './components/OrderRow';
import OrdersFilter from './components/OrdersFilter';

interface FilterParams {
	per_page?: number;
	page?: number;
	status?: string;
	after?: string;
	before?: string;
}

const AllOrders = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({});
	const ordersAPI = new API(urls.orders);
	const ordersQuery = useQuery(['ordersList', filterParams], () =>
		ordersAPI.list(filterParams)
	);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header showLinks>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.orders.list}>
							<ListIcon as={BiCart} />
							{__('Orders', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" marginTop="6">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<OrdersFilter setFilterParams={setFilterParams} />
						<Stack direction="column" spacing="8">
							<Table>
								<Thead>
									<Tr>
										<Th>{__('Order', 'masteriyo')}</Th>
										<Th>{__('First Name', 'masteriyo')}</Th>
										<Th>{__('Last Name', 'masteriyo')}</Th>
										<Th>{__('Date', 'masteriyo')}</Th>
										<Th>{__('Status', 'masteriyo')}</Th>
										<Th>{__('Total', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{ordersQuery.isLoading && <SkeletonOrdersList />}
									{ordersQuery.isSuccess &&
									ordersQuery?.data?.data.length === 0 ? (
										<EmptyInfo message="No orders found." />
									) : (
										ordersQuery?.data?.data.map((order: any) => (
											<OrderRow key={order.id} data={order} />
										))
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{ordersQuery.isSuccess && ordersQuery?.data?.data.length > 0 && (
					<MasteriyoPagination
						metaData={ordersQuery.data.meta}
						setFilterParams={setFilterParams}
						perPageText="Orders Per Page:"
					/>
				)}
			</Container>
		</Stack>
	);
};

export default AllOrders;
