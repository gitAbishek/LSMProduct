import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Icon,
	Input,
	Link,
	List,
	ListItem,
	Stack,
	Tooltip,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiChevronLeft, BiInfoCircle } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { useHistory } from 'react-router';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import Header from '../../../../components/common/Header';
import {
	infoIconStyles,
	navActiveStyles,
	navLinkStyles,
} from '../../../../config/styles';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { UserSchema } from '../../../../schemas';
import API from '../../../../utils/api';
import { deepClean } from '../../../../utils/utils';

const AddStudent: React.FC = () => {
	const history = useHistory();
	const formMethods = useForm<UserSchema>();
	const {
		handleSubmit,
		register,
		formState: { errors },
	} = formMethods;
	const toast = useToast();
	const queryClient = useQueryClient();
	const userAPI = new API(urls.users);

	const createUser = useMutation((data: object) => userAPI.store(data), {
		onSuccess: () => {
			queryClient.invalidateQueries('usersList');
			toast({
				title: __('New student added successfully', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			history.push(routes.users.students.list);
		},
		onError: (error: any) => {
			toast({
				description: `${error?.response?.data?.message}`,
				isClosable: true,
				status: 'error',
			});
		},
	});

	const onSubmit = (data: any) => {
		createUser.mutate(deepClean({ ...data, role: 'masteriyo_student' }));
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header showLinks>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							isActive={() => location.hash.includes('/users/')}
							_activeLink={navActiveStyles}
							to={routes.users.students.add}>
							{__('Add New Student', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" mt="6">
				<Stack direction="column" spacing="6">
					<ButtonGroup>
						<RouterLink to={routes.users.students.list}>
							<Button
								variant="link"
								_hover={{ color: 'blue.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
								{__('Back to Students', 'masteriyo')}
							</Button>
						</RouterLink>
					</ButtonGroup>
					<Box bg="white" p="10" shadow="box">
						<FormProvider {...formMethods}>
							<form onSubmit={handleSubmit(onSubmit)}>
								<Stack direction="column" spacing="6">
									<Stack direction="row" spacing="6">
										<Stack py="3" spacing="3" flex="1">
											<Heading as="h2" fontSize="lg">
												{__('Name', 'masteriyo')}
											</Heading>

											<FormControl>
												<FormLabel>
													{__('Username', 'masteriyo')}
													<Tooltip
														label={__(
															'You cannot change username later.',
															'masteriyo'
														)}
														hasArrow
														fontSize="xs">
														<Box as="span" sx={infoIconStyles}>
															<Icon as={BiInfoCircle} />
														</Box>
													</Tooltip>
												</FormLabel>
												<Input {...register('username')} />
											</FormControl>

											<FormControl>
												<FormLabel>
													{__('Password', 'masteriyo')}
													<span style={{ color: 'red' }} className="required">
														*
													</span>
												</FormLabel>
												<Input
													type="password"
													{...register('password', {
														required: __('Password is required.', 'masteriyo'),
													})}
												/>
												<FormErrorMessage>
													{errors?.password && errors?.password?.message}
												</FormErrorMessage>
											</FormControl>

											<FormControl isInvalid={!!errors?.first_name}>
												<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
												<Input {...register('first_name')} />
												<FormErrorMessage>
													{errors?.first_name && errors?.first_name?.message}
												</FormErrorMessage>
											</FormControl>

											<FormControl isInvalid={!!errors?.last_name}>
												<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
												<Input {...register('last_name')} />
												<FormErrorMessage>
													{errors?.last_name && errors?.last_name?.message}
												</FormErrorMessage>
											</FormControl>

											<FormControl isInvalid={!!errors?.nickname}>
												<FormLabel>{__('Nickname', 'masteriyo')}</FormLabel>
												<Input {...register('nickname')} />
												<FormErrorMessage>
													{errors?.nickname && errors?.nickname?.message}
												</FormErrorMessage>
											</FormControl>
										</Stack>
										<Stack spacing="3" flex="1">
											<Heading as="h2" fontSize="lg">
												{__('Contact Info', 'masteriyo')}
											</Heading>

											<FormControl isInvalid={!!errors?.email}>
												<FormLabel>
													{__('Email address', 'masteriyo')}
													<span style={{ color: 'red' }} className="required">
														*
													</span>
												</FormLabel>
												<Input
													type="email"
													{...register('email', {
														required: __('Email is required.', 'masteriyo'),
													})}
												/>
												<FormErrorMessage>
													{errors?.email && errors?.email?.message}
												</FormErrorMessage>
											</FormControl>

											<FormControl isInvalid={!!errors?.url}>
												<FormLabel>{__('Website URL', 'masteriyo')}</FormLabel>
												<Input type="url" {...register('url')} />
												<FormErrorMessage>
													{errors?.url && errors?.url?.message}
												</FormErrorMessage>
											</FormControl>
										</Stack>
									</Stack>

									<Box py="3">
										<Divider />
									</Box>

									<ButtonGroup>
										<Button
											colorScheme="blue"
											type="submit"
											isLoading={createUser.isLoading}>
											{__('Add Student', 'masteriyo')}
										</Button>
										<Button
											variant="outline"
											onClick={() => history.push(routes.users.students.list)}
											isDisabled={createUser.isLoading}>
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

export default AddStudent;
