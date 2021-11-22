import {
	Avatar,
	AvatarBadge,
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
import { Ellipse8 } from '../../constants/images';
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
				<Tabs>
					<Stack>
						<Stack>
							<TabList>
								<Tab sx={tabStyles}>{__('Edit Profile', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>{__('Notifications', 'masteriyo')}</Tab>
								<Tab sx={tabStyles}>
									{__('Password  & Security', 'masteriyo')}
								</Tab>
							</TabList>

							<TabPanels>
								<TabPanel>
									<Stack mt="10">
										<Center>
											<Avatar
												size="xl"
												name="Rose Pett"
												src={Ellipse8}
												background="none">
												<AvatarBadge
													boxSize="1.08em"
													bg="blue.500"
													top="-17px"
												/>
											</Avatar>
										</Center>
									</Stack>
									<form onSubmit={onSubmit}>
										<Stack spacing="8" mt="8">
											<Stack direction="row" spacing="8">
												<FormControl isInvalid={!!errors?.firstName}>
													<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
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
													<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
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
														placeholder="9855666555"
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
										</Stack>

										<Stack py="10">
											<ButtonGroup>
												<Button
													colorScheme="blue"
													rounded="full"
													type="submit"
													px="18">
													{__('SAVE', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</form>
								</TabPanel>
								<TabPanel>Notifications</TabPanel>
								<TabPanel>
									<PasswordSecurity />
								</TabPanel>
							</TabPanels>
						</Stack>
					</Stack>
				</Tabs>
			</Container>
		</Box>
	);
};

export default EditProfile;
