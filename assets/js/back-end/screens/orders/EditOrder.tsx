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
	IconButton,
	Input,
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
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React, { useRef, useState } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import ReactSelect from 'react-select';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';

const orderStatusList = [
	{
		label: __('Pending', 'masteriyo'),
		value: 'pending',
	},
	{
		label: __('Processing', 'masteriyo'),
		value: 'processing',
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
		label: __('Paypal', 'masteriyo'),
		value: 'paypal',
	},
];
//@ts-ignore
const states = window._MASTERIYO_.states;
//@ts-ignore
const countries = window._MASTERIYO_.countries;
const countryOptions = Object.entries(countries).map(([code, name]) => ({
	value: code,
	label: name,
}));
const hasStates = (countryCode: string) => {
	return !!states[countryCode];
};
const getStateOptions = (countryCode: string) => {
	return Object.entries(states[countryCode]).map(([code, name]) => ({
		value: code,
		label: name,
	}));
};

const EditOrder = () => {
	const { orderId }: any = useParams();
	const history = useHistory();
	const {
		handleSubmit,
		register,
		formState: { errors },
		control,
		watch,
	} = useForm();
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

	const billingCountry = watch('billing[country]');
	const countryCode = billingCountry
		? billingCountry.value
		: orderQuery.data?.billing.country;
	const isStatesAvailable = hasStates(countryCode);
	const billingState = watch('billing[state]');
	const stateCode = billingState
		? billingState.value
		: orderQuery.data?.billing.state;

	const updateOrder = useMutation(
		(data: object) => orderAPI.update(orderId, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Order updated successfully', 'masteriyo'),
					description: `#${data.id} ${__(' has been updated successfully.')}`,
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
			onSuccess: (data: any) => {
				toast({
					title: __('Order Deleted Successfully', 'masteriyo'),
					description: `#${data.id} ${__(' has been deleted successfully.')}`,
					isClosable: true,
					status: 'error',
				});
				history.push(routes.orders.list);
				queryClient.invalidateQueries('ordersList');
			},
		}
	);

	const onSubmit = (data: any) => {
		if (data.billing) {
			data.billing.country = data.billing.country.value;

			if (typeof data.billing.state !== 'string') {
				data.billing.state = data.billing.state.value;
			}
		}
		updateOrder.mutate(data);
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

	if (orderQuery.isLoading || orderItemsQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Container maxW="container.xl" marginTop="6">
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

					<form onSubmit={handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="6">
							<Stack direction="row" spacing="6">
								<Box flexGrow={1} py="3">
									<Heading as="h2" fontSize="medium">
										{__('General', 'masteriyo')}
									</Heading>

									{/* Date Created */}
									<FormControl py="3">
										<FormLabel>{__('Date created', 'masteriyo')}</FormLabel>
										<Input
											defaultValue={orderQuery.data.date_created.date}
											disabled
										/>
									</FormControl>

									{/* Order Status */}
									<FormControl isInvalid={errors?.status} py="3">
										<FormLabel>{__('Status', 'masteriyo')}</FormLabel>
										<Select
											defaultValue={orderQuery.data.status}
											{...register('status', {
												required: __('Please select a status', 'masteriyo'),
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

									{/* Payment Method */}
									<FormControl isInvalid={errors?.payment_method} py="3">
										<FormLabel>{__('Payment method', 'masteriyo')}</FormLabel>
										<Select
											placeholder={__('Select a payment method', 'masteriyo')}
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

									{/* Transaction ID */}
									<FormControl isInvalid={!!errors?.transaction_id} py="3">
										<FormLabel>{__('Transaction ID', 'masteriyo')}</FormLabel>
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

									{/* First Name & Last Name */}
									<Stack direction="row" spacing="8" py="3">
										<FormControl isInvalid={!!errors?.first_name}>
											<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.first_name}
												{...register('billing[first_name]')}
											/>
											<FormErrorMessage>
												{errors?.first_name && errors?.first_name?.message}
											</FormErrorMessage>
										</FormControl>
										<FormControl isInvalid={!!errors?.last_name}>
											<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.last_name}
												{...register('billing[last_name]')}
											/>
											<FormErrorMessage>
												{errors?.last_name && errors?.last_name?.message}
											</FormErrorMessage>
										</FormControl>
									</Stack>

									{/* Company */}
									<FormControl isInvalid={!!errors?.company} py="3">
										<FormLabel>{__('Company', 'masteriyo')}</FormLabel>
										<Input
											defaultValue={orderQuery.data?.billing.company}
											{...register('billing[company]')}
										/>
										<FormErrorMessage>
											{errors?.company && errors?.company?.message}
										</FormErrorMessage>
									</FormControl>

									{/* Country & State */}
									<Stack direction="row" spacing="8" py="3">
										<FormControl
											isInvalid={errors && errors['billing[country]']}>
											<FormLabel>
												{__('Country / Region', 'masteriyo')}
											</FormLabel>
											<Controller
												defaultValue={
													orderQuery.data?.billing.country && {
														label: countries[orderQuery.data?.billing.country],
														value: orderQuery.data?.billing.country,
													}
												}
												render={(args) => {
													const { field } = args;
													return (
														<ReactSelect
															{...field}
															isSearchable
															options={countryOptions}
														/>
													);
												}}
												control={control}
												name="billing[country]"
											/>
											<FormErrorMessage>
												{errors &&
													errors['billing[country]'] &&
													errors['billing[country]'].message}
											</FormErrorMessage>
										</FormControl>
										<FormControl isInvalid={errors && errors['billing[state]']}>
											<FormLabel>{__('State / County', 'masteriyo')}</FormLabel>
											<Controller
												defaultValue={
													stateCode && {
														label: states[stateCode],
														value: stateCode,
													}
												}
												render={({ field }) => {
													if (isStatesAvailable) {
														return (
															<ReactSelect
																{...field}
																defaultValue={
																	field.value.value && {
																		label: states[field.value.value],
																		value: field.value.value,
																	}
																}
																isSearchable
																options={getStateOptions(countryCode)}
															/>
														);
													}
													return <Input {...field} value={field.value.value} />;
												}}
												control={control}
												name="billing[state]"
											/>
											<FormErrorMessage>
												{errors &&
													errors['billing[state]'] &&
													errors['billing[state]'].message}
											</FormErrorMessage>
										</FormControl>
									</Stack>

									{/* Address Lines */}
									<Stack direction="row" spacing="8" py="3">
										<FormControl isInvalid={!!errors?.address_1}>
											<FormLabel>{__('Address 1', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.address_1}
												{...register('billing[address_1]')}
											/>
											<FormErrorMessage>
												{errors?.address_1 && errors?.address_1?.message}
											</FormErrorMessage>
										</FormControl>
										<FormControl isInvalid={!!errors?.address_2}>
											<FormLabel>{__('Address 2', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.address_2}
												{...register('billing[address_2]')}
											/>
											<FormErrorMessage>
												{errors?.address_2 && errors?.address_2?.message}
											</FormErrorMessage>
										</FormControl>
									</Stack>

									{/* City & Postcode */}
									<Stack direction="row" spacing="8" py="3">
										<FormControl isInvalid={errors && errors['billing[city]']}>
											<FormLabel>{__('City', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.city}
												{...register('billing[city]')}
											/>
											<FormErrorMessage>
												{errors &&
													errors['billing[city]'] &&
													errors['billing[city]'].message}
											</FormErrorMessage>
										</FormControl>
										<FormControl
											isInvalid={errors && errors['billing[postcode]']}>
											<FormLabel>{__('Postcode / ZIP', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.postcode}
												{...register('billing[postcode]')}
											/>
											<FormErrorMessage>
												{errors &&
													errors['billing[postcode]'] &&
													errors['billing[postcode]'].message}
											</FormErrorMessage>
										</FormControl>
									</Stack>

									{/* Email & Phone */}
									<Stack direction="row" spacing="8" py="3">
										<FormControl isInvalid={errors && errors['billing[email]']}>
											<FormLabel>{__('Email address', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.email}
												{...register('billing[email]')}
												type="email"
											/>
											<FormErrorMessage>
												{errors &&
													errors['billing[email]'] &&
													errors['billing[email]'].message}
											</FormErrorMessage>
										</FormControl>
										<FormControl isInvalid={errors && errors['billing[phone]']}>
											<FormLabel>{__('Phone', 'masteriyo')}</FormLabel>
											<Input
												defaultValue={orderQuery.data?.billing.phone}
												{...register('billing[phone]')}
											/>
											<FormErrorMessage>
												{errors &&
													errors['billing[phone]'] &&
													errors['billing[phone]'].message}
											</FormErrorMessage>
										</FormControl>
									</Stack>
								</Box>
							</Stack>

							<Box py="3">
								<Divider />
							</Box>

							<Heading as="h2" fontSize="medium">
								{__('Items', 'masteriyo')}
							</Heading>
							{orderItemsQuery.isSuccess &&
								orderItemsQuery.data.map((orderItem: any) => (
									<Stack key={orderItem.id} direction="row" spacing="6">
										<Text flexGrow={1} fontWeight="semibold">
											{orderItem.name}
										</Text>
										<Text fontSize="sm" fontWeight="medium" color="gray.600">
											x {orderItem.quantity}
										</Text>
										<Text fontSize="sm" fontWeight="medium" color="gray.600">
											{orderQuery.data?.currency} {orderItem.total}
										</Text>
									</Stack>
								))}

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
							{__('Delete Order')} {name}
						</AlertDialogHeader>

						<AlertDialogBody>
							{__("Are you sure? You can't restore this order", 'masteriyo')}
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
		</Container>
	);
};

export default EditOrder;
