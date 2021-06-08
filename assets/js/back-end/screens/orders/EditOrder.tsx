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
	Heading,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	useToast,
	Link,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import { Link as RouterLink } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Header from 'Components/layout/Header';
import DateCreated from './components/DateCreated';
import OrderStatus from './components/OrderStatus';
import FirstNameLastName from './components/FirstNameLastName';
import CompanyInputControl from './components/CompanyInputControl';
import AddressLines from './components/AddressLines';
import TextInputPair from './components/TextInputPair';
import PaymentMethod from './components/PaymentMethod';
import TransactionID from './components/TransactionID';

const EditOrder = () => {
	const { orderId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const cancelRef = useRef<any>();
	const orderAPI = new API(urls.orders);
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
	console.log(orderQuery.data);

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
			onError: (error) => {
				toast({
					description: `${error}`,
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

	const onSubmit = (data: object) => {
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

	if (orderQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header hideAddNewCourseBtn={true} hideCoursesMenu={true} />
			<Container maxW="container.xl">
				<FormProvider {...methods}>
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

							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<Stack direction="column" spacing="6">
									<Stack direction="row" spacing="6">
										<Box flexGrow={1} py="3">
											<Heading as="h2" fontSize="medium">
												{__('General', 'masteriyo')}
											</Heading>
											<DateCreated
												defaultValue={orderQuery.data.date_created.date}
											/>
											<OrderStatus defaultValue={orderQuery.data.status} />
											<PaymentMethod
												defaultValue={orderQuery.data.payment_method}
											/>
											<TransactionID
												defaultValue={orderQuery.data.transaction_id}
											/>
										</Box>
										<Box flexGrow={1} py="3">
											<Heading as="h2" fontSize="medium">
												{__('Billing', 'masteriyo')}
											</Heading>
											<FirstNameLastName
												defaultFirstName={orderQuery.data.billing.first_name}
												defaultLastName={orderQuery.data.billing.last_name}
											/>
											<CompanyInputControl
												defaultValue={orderQuery.data.billing.company}
											/>
											<AddressLines
												defaultAddress1={orderQuery.data.billing.address_1}
												defaultAddress2={orderQuery.data.billing.address_2}
											/>
											<TextInputPair
												name1="billing[city]"
												name2="billing[postcode]"
												label1={__('City', 'masteriyo')}
												label2={__('Postcode / ZIP', 'masteriyo')}
												defaultValue1={orderQuery.data.billing.city}
												defaultValue2={orderQuery.data.billing.postcode}
											/>
											<TextInputPair
												name1="billing[country]"
												name2="billing[state]"
												label1={__('Country / Region', 'masteriyo')}
												label2={__('State / County', 'masteriyo')}
												defaultValue1={orderQuery.data.billing.country}
												defaultValue2={orderQuery.data.billing.state}
											/>
											<TextInputPair
												name1="billing[email]"
												name2="billing[phone]"
												label1={__('Email address', 'masteriyo')}
												label2={__('Phone', 'masteriyo')}
												defaultValue1={orderQuery.data.billing.email}
												defaultValue2={orderQuery.data.billing.phone}
											/>
										</Box>
									</Stack>

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
				</FormProvider>
			</Container>
		</Stack>
	);
};

export default EditOrder;
