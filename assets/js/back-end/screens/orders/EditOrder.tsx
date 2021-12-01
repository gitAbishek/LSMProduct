import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Icon,
	IconButton,
	Input,
	Link,
	List,
	ListItem,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Select,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiChevronLeft, BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import Header from '../../components/common/Header';
import PriceWithSymbol from '../../components/common/PriceWithSymbol';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { OrderItemSchema, OrderSchema } from '../../schemas';
import API from '../../utils/api';
import { deepClean, getLocalTime } from '../../utils/utils';

const orderStatusList = [
	{
		label: __('Pending', 'masteriyo'),
		value: 'pending',
	},
	{
		label: __('On Hold', 'masteriyo'),
		value: 'on-hold',
	},
	{
		label: __('Completed', 'masteriyo'),
		value: 'completed',
	},
	{
		label: __('Cancelled', 'masteriyo'),
		value: 'cancelled',
	},
	{
		label: __('Refunded', 'masteriyo'),
		value: 'refunded',
	},
	{
		label: __('Failed', 'masteriyo'),
		value: 'failed',
	},
];
const paymentMethods = [
	{
		label: __('Standard Paypal', 'masteriyo'),
		value: 'paypal',
	},
	{
		label: __('Offline', 'masteriyo'),
		value: 'offline',
	},
];

const EditOrder = () => {
	const { orderId }: any = useParams();
	const history = useHistory();
	const formMethods = useForm<OrderSchema>();
	const {
		handleSubmit,
		register,
		formState: { errors },
	} = formMethods;
	const toast = useToast();
	const cancelRef = useRef<any>();
	const orderAPI = new API(urls.orders);
	const orderItemsAPI = new API(urls.order_items);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();

	const orderQuery = useQuery(
		[`order${orderId}`, orderId],
		() => orderAPI.get(orderId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);
	const orderItemsQuery = useQuery(
		'orderItemsList',
		() =>
			orderItemsAPI.list({
				order_id: orderId,
			}),
		{
			onError: (error: any) => {
				toast({
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const updateOrder = useMutation(
		(data: object) => orderAPI.update(orderId, data),
		{
			onSuccess: () => {
				toast({
					title: __('Order updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
			onError: (error: any) => {
				toast({
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const deleteOrder = useMutation(
		(orderId: number) => orderAPI.delete(orderId),
		{
			onSuccess: () => {
				toast({
					title: __('Order Deleted', 'masteriyo'),
					isClosable: true,
					status: 'error',
				});
				history.push(routes.orders.list);
				queryClient.invalidateQueries('ordersList');
			},
		}
	);

	const onSubmit = (data: any) => {
		updateOrder.mutate(deepClean(data));
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		deleteOrder.mutate(orderId);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	if (orderQuery.isSuccess && orderItemsQuery.isSuccess) {
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
								{__('Edit Order', 'masteriyo')}
							</Link>
						</ListItem>
					</List>
				</Header>
				<Container maxW="container.xl" marginTop="6">
					<Stack direction="column" spacing="6">
						<ButtonGroup>
							<RouterLink to={routes.orders.list}>
								<Button
									variant="link"
									_hover={{ color: 'blue.500' }}
									leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
									{__('Back to Orders', 'masteriyo')}
								</Button>
							</RouterLink>
						</ButtonGroup>
						<Box bg="white" p="10" shadow="box">
							<Stack direction="column" spacing="8">
								<Flex aling="center" justify="space-between">
									<Heading as="h1" fontSize="x-large">
										{__('Edit Order', 'masteriyo')}
									</Heading>
									<Menu placement="bottom-end">
										<MenuButton
											as={IconButton}
											icon={<BiDotsVerticalRounded />}
											variant="outline"
											rounded="sm"
											fontSize="large"
										/>
										<MenuList>
											<MenuItem icon={<BiTrash />} onClick={onDeletePress}>
												{__('Delete', 'masteriyo')}
											</MenuItem>
										</MenuList>
									</Menu>
								</Flex>

								<FormProvider {...formMethods}>
									<form onSubmit={handleSubmit(onSubmit)}>
										<Stack direction="column" spacing="6">
											<Stack direction="row" spacing="6">
												<Box flexGrow={1} py="3">
													<Heading as="h2" fontSize="medium">
														{__('General', 'masteriyo')}
													</Heading>

													<FormControl py="3">
														<FormLabel>
															{__('Date created', 'masteriyo')}
														</FormLabel>
														<Input
															defaultValue={getLocalTime(
																orderQuery.data.date_created
															).toString()}
															disabled
														/>
													</FormControl>

													<FormControl isInvalid={!!errors?.status} py="3">
														<FormLabel>{__('Status', 'masteriyo')}</FormLabel>
														<Select
															defaultValue={orderQuery.data.status}
															{...register('status', {
																required: __(
																	'Please select a status',
																	'masteriyo'
																),
															})}>
															{orderStatusList.map((option) => (
																<option key={option.value} value={option.value}>
																	{option.label}
																</option>
															))}
														</Select>

														<FormErrorMessage>
															{errors?.status && errors?.status?.message}
														</FormErrorMessage>
													</FormControl>

													<FormControl
														isInvalid={!!errors?.payment_method}
														py="3">
														<FormLabel>
															{__('Payment method', 'masteriyo')}
														</FormLabel>
														<Select
															placeholder={__(
																'Select a payment method',
																'masteriyo'
															)}
															defaultValue={orderQuery.data.payment_method}
															{...register('payment_method', {
																required: __(
																	'Please select a payment method',
																	'masteriyo'
																),
															})}>
															{paymentMethods.map((option) => (
																<option key={option.value} value={option.value}>
																	{option.label}
																</option>
															))}
														</Select>

														<FormErrorMessage>
															{errors?.payment_method &&
																errors?.payment_method?.message}
														</FormErrorMessage>
													</FormControl>

													<FormControl
														isInvalid={!!errors?.transaction_id}
														py="3">
														<FormLabel>
															{__('Transaction ID', 'masteriyo')}
														</FormLabel>
														<Input
															defaultValue={orderQuery.data.transaction_id}
															{...register('transaction_id')}
														/>
														<FormErrorMessage>
															{errors?.transaction_id &&
																errors?.transaction_id?.message}
														</FormErrorMessage>
													</FormControl>
												</Box>
												<Box flexGrow={1} py="3">
													<Heading as="h2" fontSize="medium">
														{__('Billing', 'masteriyo')}
													</Heading>

													<Stack direction="row" spacing="8" py="3">
														<FormControl
															isInvalid={!!errors?.billing?.first_name}>
															<FormLabel>
																{__('First Name', 'masteriyo')}
															</FormLabel>
															<Input
																defaultValue={
																	orderQuery.data?.billing.first_name
																}
																{...register('billing.first_name')}
															/>
															<FormErrorMessage>
																{errors?.billing?.first_name &&
																	errors?.billing?.first_name?.message}
															</FormErrorMessage>
														</FormControl>
														<FormControl
															isInvalid={!!errors?.billing?.last_name}>
															<FormLabel>
																{__('Last Name', 'masteriyo')}
															</FormLabel>
															<Input
																defaultValue={
																	orderQuery.data?.billing?.last_name
																}
																{...register('billing.last_name')}
															/>
															<FormErrorMessage>
																{errors?.billing?.last_name &&
																	errors?.billing?.last_name?.message}
															</FormErrorMessage>
														</FormControl>
													</Stack>

													<FormControl isInvalid={!!errors?.billing?.email}>
														<FormLabel>
															{__('Email address', 'masteriyo')}
														</FormLabel>
														<Input
															type="email"
															defaultValue={orderQuery.data?.billing.email}
															{...register('billing.email')}
														/>
														<FormErrorMessage>
															{errors?.billing?.email &&
																errors?.billing?.email?.message}
														</FormErrorMessage>
													</FormControl>
												</Box>
											</Stack>

											<Box py="3">
												<Divider />
											</Box>

											<Heading as="h2" fontSize="medium">
												{__('Items', 'masteriyo')}
											</Heading>
											{orderItemsQuery.isSuccess &&
												orderItemsQuery.data.map(
													(orderItem: OrderItemSchema) => (
														<Stack
															key={orderItem.id}
															direction="row"
															spacing="6">
															<Text flexGrow={1} fontWeight="semibold">
																{orderItem.name}
															</Text>
															<Text
																fontSize="sm"
																fontWeight="medium"
																color="gray.600">
																x {orderItem.quantity}
															</Text>
															<Text
																fontSize="sm"
																fontWeight="medium"
																color="gray.600">
																{PriceWithSymbol(
																	orderItem.total,
																	orderQuery.data?.currency_symbol
																)}
															</Text>
														</Stack>
													)
												)}
											<Box py="3">
												<Divider />
											</Box>

											<ButtonGroup>
												<Button
													colorScheme="blue"
													type="submit"
													isLoading={updateOrder.isLoading}>
													{__('Update Order', 'masteriyo')}
												</Button>
												<Button
													variant="outline"
													onClick={() => history.push(routes.orders.list)}>
													{__('Cancel', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</form>
								</FormProvider>
							</Stack>
						</Box>
						<AlertDialog
							isOpen={isDeleteModalOpen}
							onClose={onDeleteModalClose}
							isCentered
							leastDestructiveRef={cancelRef}>
							<AlertDialogOverlay>
								<AlertDialogContent>
									<AlertDialogHeader>
										{__('Delete Order', 'masteriyo')} {name}
									</AlertDialogHeader>

									<AlertDialogBody>
										{__(
											"Are you sure? You can't restore this order",
											'masteriyo'
										)}
									</AlertDialogBody>
									<AlertDialogFooter>
										<ButtonGroup>
											<Button
												ref={cancelRef}
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
					</Stack>
				</Container>
			</Stack>
		);
	}
	return <FullScreenLoader />;
};

export default EditOrder;
