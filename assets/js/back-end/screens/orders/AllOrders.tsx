import {
	Box,
	Container,
	Heading,
	Link,
	List,
	ListItem,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import { NavLink } from 'react-router-dom';
import Header from '../../components/common/Header';
import {
	navActiveStyles,
	navLinkStyles,
	tableStyles,
} from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonOrdersList } from '../../skeleton';
import API from '../../utils/api';
import OrderRow from './components/OrderRow';

const AllOrders = () => {
	const ordersAPI = new API(urls.orders);
	const ordersQuery = useQuery('ordersList', () => ordersAPI.list());

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
							{__('Orders', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" marginTop="6">
				<Box bg="white" py="12" shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<Box px="12">
							<Heading as="h1" size="lg">
								{__('Orders', 'masteriyo')}
							</Heading>
						</Box>
						<Stack direction="column" spacing="8">
							<Table size="sm" sx={tableStyles}>
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
					</Stack>
				</Box>
			</Container>
		</Stack>
	);
};

export default AllOrders;
