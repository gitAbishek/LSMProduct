import {
	Badge,
	Box,
	Button,
	Container,
	Grid,
	GridItem,
	Icon,
	IconButton,
	List,
	ListIcon,
	ListItem,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	SkeletonCircle,
	Stack,
	Text,
	useMediaQuery,
} from '@chakra-ui/react';

import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { IconType } from 'react-icons';
import {
	BiBook,
	BiCheckCircle,
	BiDollarCircle,
	BiDonateBlood,
	BiDotsHorizontalRounded,
	BiLoaderCircle,
	BiMessageSquareX,
	BiPlus,
	BiTrash,
	BiXCircle,
} from 'react-icons/bi';
import { MdOutlineArrowDropDown, MdOutlineArrowDropUp } from 'react-icons/md';
import { useQuery } from 'react-query';
import { useHistory } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { OrderStatus } from '../../enums/Enum';
import { SkeletonOrdersList } from '../../skeleton';
import API from '../../utils/api';
import { deepMerge, isEmpty } from '../../utils/utils';
import NoOrdersNotice from './components/NoOrdersNotice';
import OrderRow from './components/OrderRow';
import OrdersFilter from './components/OrdersFilter';

interface Status {
	status: string;
	icon: IconType;
	name: string;
}

const statusList: Status[] = [
	{
		status: 'any',
		icon: BiBook,
		name: __('All Orders', 'masteriyo'),
	},
	{
		status: OrderStatus.Completed,
		icon: BiCheckCircle,
		name: __('Completed', 'masteriyo'),
	},
	{
		status: OrderStatus.Pending,
		icon: BiLoaderCircle,
		name: __('Pending', 'masteriyo'),
	},
	{
		status: OrderStatus.OnHold,
		icon: BiDonateBlood,
		name: __('On Hold', 'masteriyo'),
	},

	{
		status: OrderStatus.Cancelled,
		icon: BiXCircle,
		name: __('Cancelled', 'masteriyo'),
	},
	{
		status: OrderStatus.Refunded,
		icon: BiDollarCircle,
		name: __('Refunded', 'masteriyo'),
	},
	{
		status: OrderStatus.Failed,
		icon: BiMessageSquareX,
		name: __('Failed', 'masteriyo'),
	},
	{
		status: OrderStatus.Trash,
		icon: BiTrash,
		name: __('Trash', 'masteriyo'),
	},
];

interface FilterParams {
	per_page?: number;
	page?: number;
	status: string;
	after?: string;
	before?: string;
	orderby: string;
	order: 'asc' | 'desc';
}

type NumberOrUndefined = number | undefined;
interface OrderCount {
	any: NumberOrUndefined;
	pending: NumberOrUndefined;
	hold: NumberOrUndefined;
	completed: NumberOrUndefined;
	cancelled: NumberOrUndefined;
	refunded: NumberOrUndefined;
	failed: NumberOrUndefined;
	trash: NumberOrUndefined;
}

const AllOrders = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({
		status: 'any',
		order: 'desc',
		orderby: 'date',
	});
	const [orderStatus, setOrderStatus] = useState<string>('any');

	const [orderStatusCount, setOrderStatusCount] = useState<OrderCount>({
		any: undefined,
		pending: undefined,
		hold: undefined,
		completed: undefined,
		cancelled: undefined,
		refunded: undefined,
		failed: undefined,
		trash: undefined,
	});
	const [isLargerThan1028] = useMediaQuery('(min-width: 1028px)');

	const ordersAPI = new API(urls.orders);
	const ordersQuery = useQuery(
		['ordersList', filterParams],
		() => ordersAPI.list(filterParams),
		{
			keepPreviousData: true,
			// Provide Total Data Count ... here !!!
			onSuccess: (data: any) => {
				const orderCount = data?.meta?.orders_count;
				setOrderStatusCount({
					any: orderCount?.['any'],
					pending: orderCount?.[OrderStatus.Pending],
					hold: orderCount?.[OrderStatus.OnHold],
					completed: orderCount?.[OrderStatus.Completed],
					cancelled: orderCount?.[OrderStatus.Cancelled],
					refunded: orderCount?.[OrderStatus.Refunded],
					failed: orderCount?.[OrderStatus.Failed],
					trash: orderCount?.[OrderStatus.Trash],
				});
			},
		}
	);

	const history = useHistory();

	const filterOrderBy = (order: 'asc' | 'desc', orderBy: string) =>
		setFilterParams(
			deepMerge({
				...filterParams,
				order: order,
				orderby: orderBy,
			})
		);

	const onOrderStatusChange = (status: string) => {
		setOrderStatus(status);
		setFilterParams(
			deepMerge(filterParams, {
				status,
			})
		);
	};

	const orderStatusButtonStyles = {
		mr: '10',
		py: '6',
		d: 'flex',
		gap: 1,
		justifyContent: 'flex-start',
		alignItems: 'center',
		fontWeight: 'medium',
		fontSize: ['xs', null, 'sm'],
	};

	const hiddenStatus = statusList.slice(4);
	const selectedHiddenStatus = hiddenStatus.find(
		(x) => x.status === orderStatus
	);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header
				showLinks
				direction={['column', 'column', 'column', 'row', 'row']}
				align={['left', 'left', 'left', 'center', 'center']}
				thirdBtn={{
					label: __('Create New Order', 'masteriyo'),
					action: () => history.push(routes.orders.add),
					icon: <Icon as={BiPlus} fontSize="md" />,
				}}>
				<Grid
					templateColumns="repeat(5, 1fr)"
					alignItems="center"
					display={['none', 'none', 'flex', 'flex']}>
					{statusList.slice(0, 4).map((button: Status) => (
						<GridItem key={button.status} mb="0">
							<Button
								color="gray.600"
								variant="link"
								sx={orderStatusButtonStyles}
								_active={navActiveStyles}
								isActive={button.status === orderStatus}
								_hover={{ color: 'primary.500' }}
								onClick={() => onOrderStatusChange(button.status)}>
								<Icon as={button.icon} />
								{button.name}
								{Object.values(orderStatusCount).every(
									(x) => x === undefined
								) ? (
									<SkeletonCircle
										size="3"
										w="17px"
										ml="1"
										mb="1"
										rounded="sm"
									/>
								) : (
									<Badge color="inherit">
										{button.status === OrderStatus.OnHold
											? orderStatusCount.hold
											: orderStatusCount[button.status]}
									</Badge>
								)}
							</Button>
						</GridItem>
					))}
					{selectedHiddenStatus ? (
						<List>
							<ListItem mb="0">
								<Button
									color="gray.600"
									variant="link"
									sx={orderStatusButtonStyles}
									_active={navActiveStyles}
									isActive={true}
									_hover={{ color: 'primary.500' }}>
									<ListIcon as={selectedHiddenStatus.icon} />
									{selectedHiddenStatus.name}
									<Badge color="inherit">
										{orderStatusCount[selectedHiddenStatus.status]}
									</Badge>
								</Button>
							</ListItem>
						</List>
					) : null}
					<Menu>
						<MenuButton
							as={IconButton}
							aria-label="Options"
							icon={<BiDotsHorizontalRounded style={{ fontSize: 25 }} />}
							style={{ background: '#FFFFFF', boxShadow: 'none' }}
						/>
						<MenuList>
							{hiddenStatus.map((button: Status) => (
								<MenuItem
									key={button.status}
									onClick={() => onOrderStatusChange(button.status)}
									icon={<button.icon />}>
									{button.name}{' '}
									<Badge color="inherit">
										{orderStatusCount[button.status]}
									</Badge>
								</MenuItem>
							))}
						</MenuList>
					</Menu>
				</Grid>
				{/* Start Mobile View */}
				<Stack
					gridTemplateColumns={{ base: 'repeat(2,1fr)', sm: 'repeat(3, 1fr)' }}
					display={{ base: 'grid', sm: 'grid', md: 'none' }}>
					{statusList.map((button: Status) => (
						<Stack key={button.status}>
							<Button
								color="gray.600"
								variant="link"
								sx={orderStatusButtonStyles}
								_active={navActiveStyles}
								isActive={button.status === orderStatus}
								_hover={{ color: 'primary.500' }}
								onClick={() => onOrderStatusChange(button.status)}>
								<Icon as={button.icon} />
								{button.name}
								{Object.values(orderStatusCount).every(
									(x) => x === undefined
								) ? (
									<SkeletonCircle
										size="3"
										w="17px"
										ml="1"
										mb="1"
										rounded="sm"
									/>
								) : (
									<Badge color="inherit">
										{button.status === OrderStatus.OnHold
											? orderStatusCount.hold
											: orderStatusCount[button.status]}
									</Badge>
								)}
							</Button>
						</Stack>
					))}
				</Stack>
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
												<Text fontSize="xs">{__('Order', 'masteriyo')}</Text>
												<Stack direction="column">
													<Icon
														as={
															filterParams?.order === 'desc'
																? MdOutlineArrowDropDown
																: MdOutlineArrowDropUp
														}
														h={6}
														w={6}
														cursor="pointer"
														color={
															filterParams?.orderby === 'id'
																? 'black'
																: 'lightgray'
														}
														transition="1s"
														_hover={{ color: 'black' }}
														onClick={() =>
															filterOrderBy(
																filterParams?.order === 'desc' ? 'asc' : 'desc',
																'id'
															)
														}
													/>
												</Stack>
											</Stack>
										</Th>
										<Th>{__('Course', 'masteriyo')}</Th>
										<Th>
											<Stack direction="row" alignItems="center">
												<Text fontSize="xs">{__('Date', 'masteriyo')}</Text>
												<Stack direction="column">
													<Icon
														as={
															filterParams?.order === 'desc'
																? MdOutlineArrowDropDown
																: MdOutlineArrowDropUp
														}
														h={6}
														w={6}
														cursor="pointer"
														color={
															filterParams?.orderby === 'date'
																? 'black'
																: 'lightgray'
														}
														transition="1s"
														_hover={{ color: 'black' }}
														onClick={() =>
															filterOrderBy(
																filterParams?.order === 'desc' ? 'asc' : 'desc',
																'date'
															)
														}
													/>
												</Stack>
											</Stack>
										</Th>
										<Th>{__('Status', 'masteriyo')}</Th>
										<Th>{__('Total', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{ordersQuery.isLoading || !ordersQuery.isFetched ? (
										<SkeletonOrdersList />
									) : ordersQuery.isSuccess &&
									  isEmpty(ordersQuery?.data?.data) ? (
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
							status: filterParams?.status,
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
