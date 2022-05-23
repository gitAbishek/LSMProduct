import {
	Box,
	Container,
	Icon,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiCart, BiPlus } from 'react-icons/bi';
import { MdOutlineArrowDropDown, MdOutlineArrowDropUp } from 'react-icons/md';
import { useQuery } from 'react-query';
import { NavLink, useHistory } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonOrdersList } from '../../skeleton';
import API from '../../utils/api';
import { deepMerge, isEmpty } from '../../utils/utils';
import NoOrdersNotice from './components/NoOrdersNotice';
import OrderRow from './components/OrderRow';
import OrdersFilter from './components/OrdersFilter';

interface FilterParams {
	per_page?: number;
	page?: number;
	status?: string;
	after?: string;
	before?: string;
	orderby: string;
	order: 'asc' | 'desc';
}

const AllOrders = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({
		order: 'asc',
		orderby: 'title',
	});
	const ordersAPI = new API(urls.orders);
	const ordersQuery = useQuery(
		['ordersList', filterParams],
		() => ordersAPI.list(filterParams),
		{
			keepPreviousData: true,
		}
	);
	const history = useHistory();

	const filterOrderBy = (order: 'asc' | 'desc', orderBy: string) =>
		setFilterParams(
			deepMerge({
				filterParams,
				order: order,
				orderby: orderBy,
			})
		);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header
				showLinks
				thirdBtn={{
					label: __('Create New Order', 'masteriyo'),
					action: () => history.push(routes.orders.add),
					icon: <Icon as={BiPlus} fontSize="md" />,
				}}>
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
										<Th>
											<Stack direction="row" alignItems="center">
												<Text>{__('Order', 'masteriyo')}</Text>
												<Stack direction="column">
													{filterParams?.order === 'desc' ? (
														<Icon
															as={MdOutlineArrowDropUp}
															h={6}
															w={6}
															cursor="pointer"
															color="lightgray"
															transition="1s"
															_hover={{ color: 'black' }}
															onClick={() => filterOrderBy('asc', 'id')}
														/>
													) : (
														<Icon
															as={MdOutlineArrowDropDown}
															h={6}
															w={6}
															cursor="pointer"
															color="lightgray"
															transition="1s"
															_hover={{ color: 'black' }}
															onClick={() => filterOrderBy('desc', 'id')}
														/>
													)}
												</Stack>
											</Stack>
										</Th>
										<Th>{__('Course', 'masteriyo')}</Th>
										<Th>{__('Date', 'masteriyo')}</Th>
										<Th>{__('Status', 'masteriyo')}</Th>
										<Th>{__('Total', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{ordersQuery.isLoading && <SkeletonOrdersList />}
									{ordersQuery.isSuccess && isEmpty(ordersQuery?.data?.data) ? (
										<NoOrdersNotice />
									) : (
										ordersQuery?.data?.data?.map((order: any) => (
											<OrderRow key={order?.id} data={order} />
										))
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{ordersQuery.isSuccess && !isEmpty(ordersQuery?.data?.data) && (
					<MasteriyoPagination
						extraFilterParams={{
							order: filterParams?.order,
							orderby: filterParams?.orderby,
						}}
						metaData={ordersQuery?.data?.meta}
						setFilterParams={setFilterParams}
						perPageText={__('Orders Per Page:', 'masteriyo')}
					/>
				)}
			</Container>
		</Stack>
	);
};

export default AllOrders;
