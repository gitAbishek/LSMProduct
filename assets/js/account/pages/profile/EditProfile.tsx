import {
	Avatar,
	Box,
	Button,
	ButtonGroup,
	Center,
	Container,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
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
import PasswordSecurity from './PasswordSecurity';

type IFormInputs = {
	firstName: string;
	lastName: string;
	email: string;
	contact: number;
	address: string;
	city: string;
	state: string;
	zipcode: number;
	country: string;
};

const EditProfile: React.FC = () => {
	const {
		register,
		handleSubmit,
		formState: { errors },
	} = useForm<IFormInputs>();
	const onSubmit = handleSubmit((data) => console.log(data));

	const tabStyles = {
		fontWeight: 'semibold',
	};

	return (
		<Box bg="white" width="full">
			<Container maxWidth="container.lg">
				<Stack spacing="8">
					<Tabs>
						<Stack spacing="8">
							<TabList>
								<Tab sx={tabStyles}>{__('Edit Profile', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Notifications', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>
									{__('Password  & Security', 'masteriyo')}
								</Tab>
							</TabList>

							<TabPanels>
								<TabPanel>
									<Stack spacing="8">
										<Center>
											<Avatar
												size="xl"
												name="Rose Pett"
												src="https://api.lorem.space/image/fashion?w=150&h=150"
												background="none"></Avatar>
										</Center>
									</Stack>
									<Stack p="6">
										<form onSubmit={onSubmit}>
											<Stack spacing="8">
												<Stack direction="row" spacing="8">
													<FormControl isInvalid={!!errors?.firstName}>
														<FormLabel>
															{__('First Name', 'masteriyo')}
														</FormLabel>
														<Input
															type="text"
															placeholder="Rose"
															{...register('firstName', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.firstName && (
															<FormErrorMessage>
																{errors?.firstName.message}
															</FormErrorMessage>
														)}
													</FormControl>
													<FormControl isInvalid={!!errors?.lastName}>
														<FormLabel>
															{__('Last Name', 'masteriyo')}
														</FormLabel>
														<Input
															type="text"
															placeholder="Pett"
															{...register('lastName', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.lastName && (
															<FormErrorMessage>
																{errors?.lastName.message}
															</FormErrorMessage>
														)}
													</FormControl>
												</Stack>

												<Stack>
													<FormControl isInvalid={!!errors?.email}>
														<FormLabel>{__('Email', 'masteriyo')}</FormLabel>
														<Input
															type="email"
															placeholder="rose@gmail.com"
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
													<FormControl isInvalid={!!errors?.contact}>
														<FormLabel>
															{__('Contact Number', 'masteriyo')}
														</FormLabel>
														<Input
															type="number"
															placeholder="222-222-333"
															{...register('contact', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.contact && (
															<FormErrorMessage>
																{errors?.contact.message}
															</FormErrorMessage>
														)}
													</FormControl>
												</Stack>
												<Stack>
													<FormControl isInvalid={!!errors?.address}>
														<FormLabel>{__('Address', 'masteriyo')}</FormLabel>
														<Input
															type="type"
															placeholder="Address"
															{...register('address', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.address && (
															<FormErrorMessage>
																{errors?.address.message}
															</FormErrorMessage>
														)}
													</FormControl>
												</Stack>
												<Stack direction="row" spacing="8">
													<FormControl isInvalid={!!errors?.city}>
														<FormLabel>{__('City', 'masteriyo')}</FormLabel>
														<Input
															type="text"
															placeholder="City"
															{...register('city', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.city && (
															<FormErrorMessage>
																{errors?.city.message}
															</FormErrorMessage>
														)}
													</FormControl>
													<FormControl isInvalid={!!errors?.state}>
														<FormLabel>{__('State', 'masteriyo')}</FormLabel>
														<Input
															type="text"
															placeholder="State"
															{...register('state', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.state && (
															<FormErrorMessage>
																{errors?.state.message}
															</FormErrorMessage>
														)}
													</FormControl>
												</Stack>

												<Stack direction="row" spacing="8">
													<FormControl isInvalid={!!errors?.zipcode}>
														<FormLabel>{__('Zip Code', 'masteriyo')}</FormLabel>
														<Input
															type="number"
															placeholder="Zip Code"
															{...register('zipcode', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.zipcode && (
															<FormErrorMessage>
																{errors?.zipcode.message}
															</FormErrorMessage>
														)}
													</FormControl>
													<FormControl isInvalid={!!errors?.country}>
														<FormLabel>{__('Country', 'masteriyo')}</FormLabel>
														<Input
															type="text"
															placeholder="Country"
															{...register('country', {
																required: __(
																	'This field cannot be empty',
																	'masteriyo'
																),
															})}
														/>
														{errors?.country && (
															<FormErrorMessage>
																{errors?.country.message}
															</FormErrorMessage>
														)}
													</FormControl>
												</Stack>
												<Stack py="6">
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
											</Stack>
										</form>
									</Stack>
								</TabPanel>
								<TabPanel>Notifications</TabPanel>
								<TabPanel>
									<PasswordSecurity />
								</TabPanel>
							</TabPanels>
						</Stack>
					</Tabs>
				</Stack>
			</Container>
		</Box>
	);
};

export default EditProfile;
