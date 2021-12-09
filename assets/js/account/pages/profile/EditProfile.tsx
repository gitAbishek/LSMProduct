import {
	Avatar,
	Button,
	ButtonGroup,
	Center,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	SimpleGrid,
	Spacer,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useForm } from 'react-hook-form';
import { useQuery } from 'react-query';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { UserSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import PasswordSecurity from './PasswordSecurity';

const EditProfile: React.FC = () => {
	// temporary userId
	const userId = 1;
	const userAPI = new API(urls.users);
	const { data, isSuccess } = useQuery<UserSchema>(
		[`userProfile${userId}`, userId],
		() => userAPI.get(userId)
	);

	const {
		register,
		handleSubmit,
		formState: { errors },
	} = useForm<UserSchema>();

	const onSubmit = handleSubmit((data) => console.log(data));

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

	if (isSuccess) {
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
								<form onSubmit={onSubmit}>
									<Stack direction="column" spacing="6">
										<Center>
											<Avatar
												size="xl"
												name={data?.first_name}
												src="https://api.lorem.space/image/fashion?w=150&h=150"
												background="none"></Avatar>
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
											<FormControl isInvalid={!!errors?.billing?.state}>
												<FormLabel>{__('State', 'masteriyo')}</FormLabel>
												<Input
													type="text"
													defaultValue={data?.billing?.state}
													{...register('billing.state')}
												/>
												{errors?.billing?.state && (
													<FormErrorMessage>
														{errors?.billing.state.message}
													</FormErrorMessage>
												)}
											</FormControl>
										</Stack>

										<Stack direction="row" spacing="8">
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
											<FormControl isInvalid={!!errors?.billing?.country}>
												<FormLabel>{__('Country', 'masteriyo')}</FormLabel>
												<Input
													type="text"
													defaultValue={data?.billing?.country}
													{...register('billing.country')}
												/>
												{errors?.billing?.country && (
													<FormErrorMessage>
														{errors?.billing?.country.message}
													</FormErrorMessage>
												)}
											</FormControl>
										</Stack>
										<Spacer />
										<ButtonGroup>
											<Button
												colorScheme="blue"
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
