import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	FormErrorMessage,
	FormLabel,
	Heading,
	Icon,
	Link,
	List,
	ListItem,
	Select,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiChevronLeft } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { Link as RouterLink, NavLink, useHistory } from 'react-router-dom';
import AsyncSelect from 'react-select/async';
import FormControlTwoCol from '../../components/common/FormControlTwoCol';
import Header from '../../components/common/Header';
import {
	navActiveStyles,
	navLinkStyles,
	reactSelectStyles,
} from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { OrderSchema } from '../../schemas';
import { CoursesApiResponse } from '../../types/course';
import { UsersApiResponse } from '../../types/users';
import API from '../../utils/api';
import { deepClean, isEmpty } from '../../utils/utils';

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

const CreateNewOrder: React.FC = () => {
	const formMethods = useForm<OrderSchema>();
	const {
		handleSubmit,
		setValue,
		register,
		formState: { errors },
	} = formMethods;
	const history = useHistory();
	const usersAPI = new API(urls.users);
	const coursesAPI = new API(urls.courses);
	const ordersAPI = new API(urls.orders);
	const toast = useToast();

	const usersQuery = useQuery<UsersApiResponse>('users', () =>
		usersAPI.list({
			orderby: 'display_name',
			order: 'asc',
			per_page: 10,
		})
	);

	const coursesQuery = useQuery<CoursesApiResponse>('courses', () =>
		coursesAPI.list({
			per_page: 10,
		})
	);

	const addOrder = useMutation((data: OrderSchema) => ordersAPI.store(data), {
		onSuccess: () => {
			toast({
				title: __('New Order Added.', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			history.push(routes.orders.list);
		},
	});

	const onSubmit = (data: OrderSchema) => {
		addOrder.mutate(deepClean(data));
	};

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
							{__('Create Order', 'masteriyo')}
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
								_hover={{ color: 'primary.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
								{__('Back to Orders', 'masteriyo')}
							</Button>
						</RouterLink>
					</ButtonGroup>
					<Box bg="white" p="10" shadow="box">
						<FormProvider {...formMethods}>
							<form onSubmit={handleSubmit(onSubmit)}>
								<Stack direction="column" spacing="6">
									<Stack direction="column" spacing="6">
										<Box flex="1" py="2">
											<Heading as="h2" fontSize="medium">
												{__('General', 'masteriyo')}
											</Heading>

											<FormControlTwoCol>
												<FormLabel>{__('Status', 'masteriyo')}</FormLabel>
												<Select defaultValue="pending" {...register('status')}>
													{orderStatusList.map((option) => (
														<option key={option.value} value={option.value}>
															{option.label}
														</option>
													))}
												</Select>
											</FormControlTwoCol>
										</Box>
										<Box flex="1" py="2">
											<Heading as="h2" fontSize="medium">
												{__('Billing', 'masteriyo')}
											</Heading>

											<FormControlTwoCol
												sx={{
													'.css-2b097c-container': {
														width: '100%',
													},
												}}
												isInvalid={!!errors?.customer_id}
												py="3">
												<FormLabel>{__('Customer', 'masteriyo')}</FormLabel>
												<AsyncSelect
													{...register('customer_id', {
														required: 'Customer must be selected',
													})}
													styles={reactSelectStyles}
													cacheOptions={true}
													loadingMessage={() =>
														__('Searching customer...', 'masteriyo')
													}
													noOptionsMessage={({ inputValue }) =>
														!isEmpty(inputValue)
															? __('Users not found.', 'masteriyo')
															: usersQuery.isLoading
															? __('Loading...', 'masteriyo')
															: __(
																	'Please enter one or more characters.',
																	'masteriyo'
															  )
													}
													isClearable={true}
													placeholder={__(
														'Search customer by username or email',
														'masteriyo'
													)}
													onChange={(selectedOption: any) => {
														setValue(
															'customer_id',
															selectedOption?.value.toString()
														);
													}}
													defaultOptions={
														usersQuery.isSuccess
															? usersQuery.data?.data?.map((user) => {
																	return {
																		value: user.id,
																		label: `${user.display_name} (#${user.id} - ${user.email})`,
																		avatar_url: user.avatar_url,
																	};
															  })
															: []
													}
													loadOptions={(searchValue, callback) => {
														if (isEmpty(searchValue)) {
															return callback([]);
														}
														usersAPI
															.list({ search: searchValue })
															.then((data) => {
																callback(
																	data.data.map((user: any) => {
																		return {
																			value: user.id,
																			label: `${user.display_name} (#${user.id} - ${user.email})`,
																		};
																	})
																);
															});
													}}
												/>

												<FormErrorMessage>
													{errors?.customer_id && errors?.customer_id?.message}
												</FormErrorMessage>
											</FormControlTwoCol>
										</Box>
									</Stack>

									<Box py="2">
										<Divider />
									</Box>

									<FormControlTwoCol
										isInvalid={!!errors?.course_lines?.[0]?.course_id}
										py="3">
										<FormLabel>{__('Course', 'masteriyo')}</FormLabel>
										<AsyncSelect
											{...register('course_lines.0.course_id', {
												required: 'Course must be selected',
											})}
											styles={reactSelectStyles}
											cacheOptions={true}
											loadingMessage={() =>
												__('Searching course...', 'masteriyo')
											}
											noOptionsMessage={({ inputValue }) =>
												!isEmpty(inputValue)
													? __('Course not found.', 'masteriyo')
													: coursesQuery.isLoading
													? __('Loading...', 'masteriyo')
													: __(
															'Please enter one or more characters.',
															'masteriyo'
													  )
											}
											isClearable={true}
											placeholder={__('Search courses', 'masteriyo')}
											onChange={(selectedOption: any) => {
												setValue(
													'course_lines.0.course_id',
													selectedOption?.value
												);
											}}
											defaultOptions={
												coursesQuery.isSuccess
													? coursesQuery.data?.data?.map((course) => {
															return {
																value: course.id,
																label: `#${course.id} ${course.name}`,
															};
													  })
													: []
											}
											loadOptions={(searchValue, callback) => {
												if (isEmpty(searchValue)) {
													return callback([]);
												}
												coursesAPI
													.list({ search: searchValue })
													.then((data) => {
														callback(
															data.data.map((course: any) => {
																return {
																	value: course.id,
																	label: `#${course.id} ${course.name}`,
																};
															})
														);
													});
											}}
										/>
										<FormErrorMessage>
											{errors?.course_lines?.[0]?.course_id &&
												errors?.course_lines?.[0]?.course_id?.message}
										</FormErrorMessage>
									</FormControlTwoCol>

									<Box py="2">
										<Divider />
									</Box>

									<ButtonGroup>
										<Button
											colorScheme="primary"
											type="submit"
											isLoading={addOrder.isLoading}>
											{__('Create Order', 'masteriyo')}
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
					</Box>
				</Stack>
			</Container>
		</Stack>
	);
};

export default CreateNewOrder;
