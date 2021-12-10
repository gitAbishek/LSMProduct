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
	Select,
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
import { useForm, useWatch } from 'react-hook-form';
import { BiCopy, BiEdit } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import {
	CountriesSchema,
	StatesSchema,
	UserSchema,
} from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { deepClean, isEmpty } from '../../../back-end/utils/utils';
import PasswordSecurity from './PasswordSecurity';

const EditProfile: React.FC = () => {
	const toast = useToast();
	const userAPI = new API(urls.currentUser);
	const statesAPI = new API(urls.states);
	const countriesAPI = new API(urls.countries);

	const { data, isSuccess, refetch } = useQuery<UserSchema>('userProfile', () =>
		userAPI.get()
	);
	const countriesQuery = useQuery('countries', () => countriesAPI.list());
	const statesQuery = useQuery('states', () => statesAPI.list());

	const {
		register,
		handleSubmit,
		setValue,
		getValues,
		control,
		formState: { errors },
	} = useForm<UserSchema>();

	const updateUser = useMutation((data: UserSchema) => userAPI.store(data), {
		onSuccess: () => {
			refetch();
			toast({
				title: __('Profile Updated', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
		},
	});

	const onSubmit = (data: UserSchema) => {
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

	const watchSelectedCountry = useWatch({
		name: 'billing.country',
		defaultValue: data?.billing?.country,
		control,
	});

	if (isSuccess && countriesQuery?.isSuccess && statesQuery.isSuccess) {
		const matchCountriesData = statesQuery?.data.filter(
			(statesData: StatesSchema) => statesData.country === watchSelectedCountry
		);
		return (
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
												name={data?.first_name}
												src={data?.avatar_url}
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
													defaultValue={data?.first_name}
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
													defaultValue={data?.last_name}
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
													defaultValue={data?.email}
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
													aria-label={__('Copy from the profile', 'masteriyo')}
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
													defaultValue={data?.billing?.first_name}
													{...register('billing.first_name', {
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
											<FormControl isInvalid={!!errors?.billing?.last_name}>
												<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
												<Input
													defaultValue={data?.billing?.last_name}
													type="text"
													{...register('billing.last_name', {
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
											<FormControl isInvalid={!!errors?.billing?.phone}>
												<FormLabel>
													{__('Contact Number', 'masteriyo')}
												</FormLabel>
												<Input
													defaultValue={data?.billing.phone}
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
										<Stack>
											<FormControl isInvalid={!!errors?.billing?.address_1}>
												<FormLabel>{__('Address', 'masteriyo')}</FormLabel>
												<Input
													type="type"
													defaultValue={data?.billing.address_1}
													{...register('billing.address_1')}
												/>
												{errors?.billing?.address_1 && (
													<FormErrorMessage>
														{errors?.billing?.address_1.message}
													</FormErrorMessage>
												)}
											</FormControl>
										</Stack>
										<Stack direction="row" spacing="8">
											<FormControl isInvalid={!!errors?.billing?.country}>
												<FormLabel>{__('Country', 'masteriyo')}</FormLabel>
												<Select
													{...register('billing.country')}
													defaultValue={data?.billing?.country}>
													<option value="">
														{__('Select a country', 'masteriyo')}
													</option>
													{countriesQuery?.data.map(
														(country: CountriesSchema) => (
															<option value={country.code} key={country.code}>
																{country.name}
															</option>
														)
													)}
												</Select>
												{errors?.billing?.country && (
													<FormErrorMessage>
														{errors?.billing?.country.message}
													</FormErrorMessage>
												)}
											</FormControl>

											<FormControl isInvalid={!!errors?.billing?.state}>
												<FormLabel>{__('State', 'masteriyo')}</FormLabel>
												<Select
													{...register('billing.state')}
													defaultValue={data?.billing?.state}>
													{!isEmpty(matchCountriesData) ? (
														matchCountriesData[0].states.map(
															(stateData: { code: string; name: string }) => (
																<option
																	value={stateData.code}
																	key={stateData.code}>
																	{stateData.name}
																</option>
															)
														)
													) : (
														<option>
															{__('No state founds', 'masteriyo')}
														</option>
													)}
												</Select>
												{errors?.billing?.state && (
													<FormErrorMessage>
														{errors?.billing.state.message}
													</FormErrorMessage>
												)}
											</FormControl>
										</Stack>

										<Stack direction="row" spacing="8">
											<FormControl isInvalid={!!errors?.billing?.city}>
												<FormLabel>{__('City', 'masteriyo')}</FormLabel>
												<Input
													defaultValue={data?.billing?.city}
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
													defaultValue={data?.billing?.postcode}
													{...register('billing.postcode')}
												/>
												{errors?.billing?.postcode && (
													<FormErrorMessage>
														{errors?.billing?.postcode.message}
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
		);
	}

	return <FullScreenLoader />;
};

export default EditProfile;
