import {
	Avatar,
	AvatarBadge,
	Box,
	Button,
	ButtonGroup,
	Center,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Icon,
	IconButton,
	Input,
	SimpleGrid,
	Spacer,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Tooltip,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiCopy, BiEdit } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { UserSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { deepClean } from '../../../back-end/utils/utils';
import CountryState from '../../components/CountryState';
import PasswordSecurity from './PasswordSecurity';

const EditProfile: React.FC = () => {
	const toast = useToast();
	const userAPI = new API(urls.currentUser);
	const statesAPI = new API(urls.states);
	const countriesAPI = new API(urls.countries);

	const userDataQuery = useQuery<UserSchema>('userProfile', () =>
		userAPI.get()
	);

	const countriesQuery = useQuery('countries', () => countriesAPI.list());
	const statesQuery = useQuery('states', () => statesAPI.list());

	const methods = useForm<UserSchema>({
		reValidateMode: 'onChange',
		mode: 'onChange',
	});

	const {
		register,
		handleSubmit,
		formState: { errors },
		setValue,
		getValues,
	} = methods;

	const updateUser = useMutation((data: UserSchema) => userAPI.store(data), {
		onSuccess: () => {
			userDataQuery.refetch();
			toast({
				title: __('Profile Updated', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
		},
	});

	const onSubmit = (data: UserSchema) => {
		console.log(data);
		updateUser.mutate(deepClean(data));
	};

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mr: 4,
		_hover: {
			color: 'blue.500',
		},
	};

	const tabPanelStyles = {
		px: '0',
	};

	const copyToBilling = () => {
		setValue('billing.first_name', getValues('first_name'));
		setValue('billing.last_name', getValues('last_name'));
	};

	console.log(userDataQuery?.data?.billing);

	if (
		userDataQuery?.isSuccess &&
		countriesQuery.isSuccess &&
		statesQuery.isSuccess
	) {
		return (
			<FormProvider {...methods}>
				<Stack spacing="8">
					<Tabs>
						<Stack spacing="8">
							<TabList>
								<Tab sx={tabStyles}>{__('Edit Profile', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>
									{__('Password  & Security', 'masteriyo')}
								</Tab>
							</TabList>

							<TabPanels>
								<TabPanel sx={tabPanelStyles}>
									<form onSubmit={handleSubmit(onSubmit)}>
										<Stack direction="column" spacing="6">
											<Center pos="relative">
												<Avatar
													size="xl"
													fontSize="3xl"
													name={userDataQuery?.data?.first_name}
													src={userDataQuery?.data?.avatar_url}
													background="none">
													<AvatarBadge boxSize="1.25em" bg="gray.400">
														<Tooltip
															label={__(
																'Avatar should be changed from gravatar',
																'masteriyo'
															)}>
															<Box pb="1">
																<Icon as={BiEdit} fontSize="sm" />
															</Box>
														</Tooltip>
													</AvatarBadge>
												</Avatar>
											</Center>
											<SimpleGrid columns={2} spacing="6">
												<FormControl isInvalid={!!errors?.first_name}>
													<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
													<Input
														type="text"
														defaultValue={userDataQuery?.data?.first_name}
														{...register('first_name', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
													/>
													{errors?.first_name && (
														<FormErrorMessage>
															{errors?.first_name.message}
														</FormErrorMessage>
													)}
												</FormControl>
												<FormControl isInvalid={!!errors?.last_name}>
													<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
													<Input
														defaultValue={userDataQuery?.data?.last_name}
														type="text"
														{...register('last_name', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
													/>
													{errors?.last_name && (
														<FormErrorMessage>
															{errors?.last_name.message}
														</FormErrorMessage>
													)}
												</FormControl>
											</SimpleGrid>

											<Stack>
												<FormControl isInvalid={!!errors?.email}>
													<FormLabel>{__('Email', 'masteriyo')}</FormLabel>
													<Input
														defaultValue={userDataQuery?.data?.email}
														type="email"
														{...register('email', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
													/>
													{errors?.email && (
														<FormErrorMessage>
															{errors?.email.message}
														</FormErrorMessage>
													)}
												</FormControl>
											</Stack>

											<Heading
												fontSize="lg"
												borderBottom="1px"
												borderColor="gray.100"
												py="6">
												{__('Billing', 'masteriyo')}
												<Tooltip label={__('Copy from the profile')}>
													<IconButton
														fontSize="md"
														variant="link"
														aria-label={__(
															'Copy from the profile',
															'masteriyo'
														)}
														icon={<BiCopy />}
														onClick={copyToBilling}
													/>
												</Tooltip>
											</Heading>
											<SimpleGrid columns={2} spacing="6">
												<FormControl isInvalid={!!errors?.billing?.first_name}>
													<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
													<Input
														type="text"
														defaultValue={
															userDataQuery?.data?.billing?.first_name
														}
														{...register('billing.first_name')}
													/>
													{errors?.first_name && (
														<FormErrorMessage>
															{errors?.first_name.message}
														</FormErrorMessage>
													)}
												</FormControl>
												<FormControl isInvalid={!!errors?.billing?.last_name}>
													<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
													<Input
														defaultValue={
															userDataQuery?.data?.billing?.last_name
														}
														type="text"
														{...register('billing.last_name')}
													/>
													{errors?.last_name && (
														<FormErrorMessage>
															{errors?.last_name.message}
														</FormErrorMessage>
													)}
												</FormControl>
											</SimpleGrid>
											<Stack>
												<FormControl isInvalid={!!errors?.billing?.phone}>
													<FormLabel>
														{__('Contact Number', 'masteriyo')}
													</FormLabel>
													<Input
														defaultValue={userDataQuery?.data?.billing.phone}
														type="number"
														{...register('billing.phone')}
													/>
													{errors?.billing?.phone && (
														<FormErrorMessage>
															{errors?.billing?.phone.message}
														</FormErrorMessage>
													)}
												</FormControl>
											</Stack>
											<CountryState
												country={userDataQuery?.data?.billing?.country}
												state={userDataQuery?.data?.billing?.state}
												countriesQuery={countriesQuery}
												statesQuery={statesQuery}
											/>
											<Stack direction="row" spacing="8">
												<FormControl isInvalid={!!errors?.billing?.city}>
													<FormLabel>{__('City', 'masteriyo')}</FormLabel>
													<Input
														defaultValue={userDataQuery?.data?.billing?.city}
														type="text"
														{...register('billing.city')}
													/>
													{errors?.billing?.city && (
														<FormErrorMessage>
															{errors?.billing.city.message}
														</FormErrorMessage>
													)}
												</FormControl>
												<FormControl isInvalid={!!errors?.billing?.postcode}>
													<FormLabel>{__('Zip Code', 'masteriyo')}</FormLabel>
													<Input
														type="number"
														defaultValue={
															userDataQuery?.data?.billing?.postcode
														}
														{...register('billing.postcode')}
													/>
													{errors?.billing?.postcode && (
														<FormErrorMessage>
															{errors?.billing?.postcode.message}
														</FormErrorMessage>
													)}
												</FormControl>
											</Stack>
											<Stack direction="row" spacing="8">
												<FormControl isInvalid={!!errors?.billing?.address_1}>
													<FormLabel>{__('Address 1', 'masteriyo')}</FormLabel>
													<Input
														type="type"
														defaultValue={
															userDataQuery?.data?.billing.address_1
														}
														{...register('billing.address_1')}
													/>
													{errors?.billing?.address_1 && (
														<FormErrorMessage>
															{errors?.billing?.address_1.message}
														</FormErrorMessage>
													)}
												</FormControl>
												<FormControl isInvalid={!!errors?.billing?.address_2}>
													<FormLabel>{__('Address 2', 'masteriyo')}</FormLabel>
													<Input
														type="type"
														defaultValue={
															userDataQuery?.data?.billing.address_2
														}
														{...register('billing.address_2')}
													/>
													{errors?.billing?.address_2 && (
														<FormErrorMessage>
															{errors?.billing?.address_2.message}
														</FormErrorMessage>
													)}
												</FormControl>
											</Stack>
											<Spacer />
											<ButtonGroup>
												<Button
													colorScheme="blue"
													isLoading={updateUser?.isLoading}
													rounded="full"
													type="submit"
													px="19">
													{__('SAVE', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</form>
								</TabPanel>

								<TabPanel sx={tabPanelStyles}>
									<PasswordSecurity />
								</TabPanel>
							</TabPanels>
						</Stack>
					</Tabs>
				</Stack>
			</FormProvider>
		);
	}

	return <FullScreenLoader />;
};

export default EditProfile;
