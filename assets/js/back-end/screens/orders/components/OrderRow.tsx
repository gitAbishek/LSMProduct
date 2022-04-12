import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Badge,
	Button,
	ButtonGroup,
	Icon,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import {
	BiCalendar,
	BiDotsVerticalRounded,
	BiEdit,
	BiShow,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import PriceWithSymbol from '../../../components/common/PriceWithSymbol';
import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import { OrderStatus } from '../../../enums/Enum';
import API from '../../../utils/api';
import { getLocalTime } from '../../../utils/utils';
const makeOrderNumberLabel = (order: any) => {
	if (order.billing.first_name || order.billing.last_name) {
		return `#${order.id} ${order.billing.first_name} ${order.billing.last_name}`.trim();
	} else if (order.billing.company) {
		return `#${order.id} ${order.billing.company}`.trim();
	}
	return `#${order.id}`;
};

interface BillingAddress {
	first_name: string;
	last_name: string;
	email: string;
	company: string;
	address_1: string;
	address_2: string;
	city: string;
	state: string;
	postcode: string;
	country: string;
}

interface CourseInfo {
	id: number;
	name: string;
	type: string;
	course_id: number;
	quantity: number;
	subtotal: string;
	total: string;
}

interface Order {
	id: number;
	order_number: string;
	date_created: string;
	status: any;
	total: string;
	currency_symbol: string;
	billing: BillingAddress;
	course_lines: CourseInfo[];
}
interface Props {
	data: Order;
}
const OrderRow: React.FC<Props> = (props) => {
	const { data } = props;
	const { id, status, total, currency_symbol, billing } = data;
	const order_number = makeOrderNumberLabel(data);
	const queryClient = useQueryClient();
	const toast = useToast();
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const [isOrderPreviewModalOpen, setOrderPreviewModalOpen] = useState(false);
	const ordersAPI = new API(urls.orders);
	const cancelDeleteModalRef = useRef<any>();
	const cancelOrderPreviewModalRef = useRef<any>();

	const deleteOrder = useMutation(
		(id: number) => ordersAPI.delete(id, { force: true }),
		{
			onSuccess: () => {
				setDeleteModalOpen(false);
				queryClient.invalidateQueries('ordersList');
			},
		}
	);

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};
	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};
	const onDeleteConfirm = () => {
		deleteOrder.mutate(id);
	};

	const onPreviewPress = () => {
		setOrderPreviewModalOpen(true);
	};
	const onOrderPreviewModalClose = () => {
		setOrderPreviewModalOpen(false);
	};

	const restoreOrder = useMutation((id: number) => ordersAPI.restore(id), {
		onSuccess: () => {
			toast({
				title: __('Order Restored', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			queryClient.invalidateQueries('ordersList');
		},
	});

	const trashOrder = useMutation((id: number) => ordersAPI.delete(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('ordersList');
			toast({
				title: __('Order Trashed', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
		},
	});

	const onTrashPress = () => trashOrder.mutate(id);

	const onRestorePress = () => restoreOrder.mutate(id);

	const orderStatus =
		status == OrderStatus.Completed
			? 'green'
			: status == OrderStatus.OnHold
			? 'orange'
			: status == OrderStatus.Pending
			? 'yellow'
			: status == OrderStatus.Cancelled
			? 'red'
			: status == OrderStatus.Refunded
			? 'blue'
			: status == OrderStatus.Failed
			? 'blackalpha'
			: 'gray';

	return (
		<Tr>
			<Td>
				<Stack direction="column">
					{status === 'trash' ? (
						<Text fontWeight="semibold">
							{`#${id} ${data?.billing?.first_name} ${data?.billing?.last_name}`}
						</Text>
					) : (
						<Link
							as={RouterLink}
							to={routes.orders.edit.replace(':orderId', id.toString())}
							fontWeight="semibold"
							fontSize="sm"
							_hover={{ color: 'blue.500' }}>
							{`#${id} ${data?.billing?.first_name} ${data?.billing?.last_name}`}
						</Link>
					)}
					<Text fontSize="xs" color="gray.600">
						{data?.billing?.email}
					</Text>
				</Stack>
			</Td>
			<Td>
				<Text fontSize="sm" color="gray.600">
					{data?.course_lines[0]?.name}
				</Text>
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center" color="gray.600">
					<Icon as={BiCalendar} />
					<Text fontSize="sm" fontWeight="medium">
						{getLocalTime(data?.date_created).toLocaleString()}
					</Text>
				</Stack>
			</Td>
			<Td>
				<Badge colorScheme={orderStatus}>{status}</Badge>
			</Td>
			<Td>{PriceWithSymbol(total, currency_symbol)}</Td>
			<Td>
				{status === 'trash' ? (
					<Menu placement="bottom-end">
						<MenuButton
							as={IconButton}
							icon={<BiDotsVerticalRounded />}
							variant="outline"
							rounded="sm"
							fontSize="large"
							size="xs"
						/>
						<MenuList>
							<MenuItem
								onClick={() => onRestorePress()}
								icon={<BiShow />}
								_hover={{ color: 'blue.500' }}>
								{__('Restore', 'masteriyo')}
							</MenuItem>
							<MenuItem
								onClick={() => onDeletePress()}
								icon={<BiTrash />}
								_hover={{ color: 'red.500' }}>
								{__('Delete Permanently', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				) : (
					<ButtonGroup>
						<RouterLink
							to={routes.orders.edit.replace(':orderId', id.toString())}>
							<Button leftIcon={<BiEdit />} colorScheme="blue" size="xs">
								{__('Edit', 'masteriyo')}
							</Button>
						</RouterLink>
						<Menu placement="bottom-end">
							<MenuButton
								as={IconButton}
								icon={<BiDotsVerticalRounded />}
								variant="outline"
								rounded="sm"
								fontSize="large"
								size="xs"
							/>
							<MenuList>
								<MenuItem onClick={onPreviewPress} icon={<BiShow />}>
									{__('Preview', 'masteriyo')}
								</MenuItem>
								<MenuItem
									onClick={onTrashPress}
									icon={<BiTrash />}
									_hover={{ color: 'red.500' }}>
									{__('Trash', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</ButtonGroup>
				)}

				{/* Order Preview Dialog */}
				<AlertDialog
					isOpen={isOrderPreviewModalOpen}
					onClose={onOrderPreviewModalClose}
					isCentered
					leastDestructiveRef={cancelOrderPreviewModalRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Order', 'masteriyo')} {order_number}
							</AlertDialogHeader>
							<AlertDialogBody>
								<div>
									<strong>{__('Billing details', 'masteriyo')}</strong>
								</div>
								<div>
									{billing.first_name} {billing.last_name}
								</div>
								<div>{billing.company}</div>
								<div>{billing.address_1}</div>
								<div>{billing.address_2}</div>
								<div>{billing.city}</div>
								<div>{billing.state}</div>
								<div>{billing.postcode}</div>
								<div>{billing.country}</div>
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button
										ref={cancelOrderPreviewModalRef}
										onClick={onOrderPreviewModalClose}
										variant="outline">
										{__('OK', 'masteriyo')}
									</Button>
									<Link
										as={RouterLink}
										to={routes.orders.edit.replace(':orderId', id.toString())}>
										<Button colorScheme="blue">
											{__('Edit', 'masteriyo')}
										</Button>
									</Link>
								</ButtonGroup>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>

				{/* Delete Order Dialog */}
				<AlertDialog
					isOpen={isDeleteModalOpen}
					onClose={onDeleteModalClose}
					isCentered
					leastDestructiveRef={cancelDeleteModalRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Delete Order', 'masteriyo')} {order_number}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__(
									'Are you sure? You can’t restore after deleting.',
									'masteriyo'
								)}
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button
										ref={cancelDeleteModalRef}
										onClick={onDeleteModalClose}
										variant="outline">
										{__('Cancel', 'masteriyo')}
									</Button>
									<Button
										colorScheme="red"
										onClick={onDeleteConfirm}
										isLoading={deleteOrder.isLoading}>
										{__('Delete', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>
			</Td>
		</Tr>
	);
};

export default OrderRow;
