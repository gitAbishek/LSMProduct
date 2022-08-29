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
	Switch,
	Tooltip,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiChevronLeft, BiInfoCircle } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import Header from '../../../../components/common/Header';
import {
	infoIconStyles,
	navActiveStyles,
	navLinkStyles,
} from '../../../../config/styles';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { UserStatus } from '../../../../enums/Enum';
import { UserSchema } from '../../../../schemas';
import UserSkeleton from '../../../../skeleton/UserSkeleton';
import API from '../../../../utils/api';
import { deepClean, deepMerge } from '../../../../utils/utils';

const EditInstructor: React.FC = () => {
	const { userId }: any = useParams();
	const history = useHistory();
	const formMethods = useForm<UserSchema>();
	const {
		handleSubmit,
		register,
		formState: { errors },
	} = formMethods;

	const userAPI = new API(urls.instructors);
	const toast = useToast();
	const queryClient = useQueryClient();

	const userQuery = useQuery(
		[`user${userId}`, userId],
		() => userAPI.get(userId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const updateUser = useMutation(
		(data: object) => userAPI.update(userId, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('User updated successfully.', 'masteriyo'),
					description: `#${data.id} ${__(
						' has been updated successfully.',
						'masteriyo'
					)}`,
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries(`user${userId}`);
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

	const onSubmit = (data: any) => {
		updateUser.mutate(
			deepClean(
				deepMerge(data, {
					status: data.status ? UserStatus.Active : UserStatus.Inactive,
				})
			)
		);
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
							to={routes.users.instructors.list}>
							{__('Edit Instructor', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" mt="6">
				<Stack direction="column" spacing="6">
					<ButtonGroup>
						<RouterLink to={routes.users.instructors.list}>
							<Button
								variant="link"
								_hover={{ color: 'primary.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
								{__('Back to Instructors', 'masteriyo')}
							</Button>
						</RouterLink>
					</ButtonGroup>
					<Box bg="white" p="10" shadow="box">
						{userQuery.isSuccess ? (
							<FormProvider {...formMethods}>
								<form onSubmit={handleSubmit(onSubmit)}>
									<Stack direction="column" spacing="6">
										<Stack
											direction={['column', 'column', 'row', 'row']}
											spacing="6">
											<Box flexGrow={1} py={3}>
												<Heading as="h2" fontSize="lg">
													{__('Name', 'masteriyo')}
												</Heading>

												<FormControl py="3">
													<FormLabel>
														{__('Username', 'masteriyo')}
														<Tooltip
															label={__(
																'Username cannot be changed.',
																'masteriyo'
															)}
															hasArrow
															fontSize="xs">
															<Box as="span" sx={infoIconStyles}>
																<Icon as={BiInfoCircle} />
															</Box>
														</Tooltip>
													</FormLabel>
													<Input
														defaultValue={userQuery?.data?.username}
														disabled
													/>
												</FormControl>

												<FormControl isInvalid={!!errors?.first_name} py="3">
													<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
													<Input
														defaultValue={userQuery?.data?.first_name}
														{...register('first_name')}
													/>
													<FormErrorMessage>
														{errors?.first_name && errors?.first_name?.message}
													</FormErrorMessage>
												</FormControl>

												<FormControl isInvalid={!!errors?.last_name} py="3">
													<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
													<Input
														defaultValue={userQuery?.data?.last_name}
														{...register('last_name')}
													/>
													<FormErrorMessage>
														{errors?.last_name && errors?.last_name?.message}
													</FormErrorMessage>
												</FormControl>

												<FormControl isInvalid={!!errors?.nickname} py="3">
													<FormLabel>{__('Nickname', 'masteriyo')}</FormLabel>
													<Input
														defaultValue={userQuery?.data?.nickname}
														{...register('nickname')}
													/>
													<FormErrorMessage>
														{errors?.nickname && errors?.nickname?.message}
													</FormErrorMessage>
												</FormControl>
											</Box>
											<Box flexGrow={1} py={3}>
												<Heading as="h2" fontSize="lg">
													{__('Contact Info', 'masteriyo')}
												</Heading>

												<FormControl isInvalid={!!errors?.email} py="3">
													<FormLabel>
														{__('Email address', 'masteriyo')}
														<span style={{ color: 'red' }} className="required">
															*
														</span>
													</FormLabel>
													<Input
														type="email"
														defaultValue={userQuery?.data?.email}
														{...register('email', {
															required: __('Email is required.', 'masteriyo'),
														})}
													/>
													<FormErrorMessage>
														{errors?.email && errors?.email?.message}
													</FormErrorMessage>
												</FormControl>

												<FormControl isInvalid={!!errors?.url} py="3">
													<FormLabel>
														{__('Website URL', 'masteriyo')}
													</FormLabel>
													<Input
														type="url"
														defaultValue={userQuery?.data?.url}
														{...register('url')}
													/>
													<FormErrorMessage>
														{errors?.url && errors?.url?.message}
													</FormErrorMessage>
												</FormControl>

												<FormControl py="3">
													<Stack direction="row" align="flex-start">
														<FormLabel>{__('Approved', 'masteriyo')}</FormLabel>
														<Tooltip
															label={__(
																'Approve or unapprove the user as instructor.',
																'masteriyo'
															)}
															hasArrow
															fontSize="xs">
															<Box as="span" sx={infoIconStyles}>
																<Switch
																	colorScheme="green"
																	{...register('status')}
																	defaultChecked={
																		UserStatus.Active ===
																		userQuery?.data?.status
																	}
																/>
															</Box>
														</Tooltip>
													</Stack>
												</FormControl>
											</Box>
										</Stack>

										<Box py="3">
											<Divider />
										</Box>

										<ButtonGroup>
											<Button type="submit" isLoading={updateUser.isLoading}>
												{__('Update', 'masteriyo')}
											</Button>
											<Button
												variant="outline"
												onClick={() =>
													history.push(routes.users.instructors.list)
												}
												isDisabled={updateUser.isLoading}>
												{__('Cancel', 'masteriyo')}
											</Button>
										</ButtonGroup>
									</Stack>
								</form>
							</FormProvider>
						) : (
							<UserSkeleton />
						)}
					</Box>
				</Stack>
			</Container>
		</Stack>
	);
};

export default EditInstructor;
