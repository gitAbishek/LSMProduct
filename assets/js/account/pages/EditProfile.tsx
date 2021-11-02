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

type IFormInputs = {
	firstName: string;
	lastName: string;
	email: string;
	contact: number;
	address: string;
	city: string;
	state: string;
	zipcode: string;
	country: string;
};

const EditProfile: React.FC = () => {
	const {
		register,
		handleSubmit,
		formState: { errors },
	} = useForm<IFormInputs>();
	const onSubmit = handleSubmit((data) => console.log(data));

	return (
		<Box bg="white" width="full">
			<Container maxWidth="container.lg">
				<Tabs>
					<Stack py="10">
						<TabList>
							<Tab style={{ fontWeight: 600 }}>
								{__('Edit Profile', 'masteriyo')}
							</Tab>
							<Tab style={{ fontWeight: 600 }}>
								{__('Notifications', 'masteriyo')}
							</Tab>
							<Tab style={{ fontWeight: 600 }}>
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
											src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8dXNlcnxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
											style={{
												filter: 'drop-shadow(0px 2px 8px rgba(0, 0, 0, 0.2))',
											}}
										/>{' '}
									</Center>
								</Stack>
								<form onSubmit={onSubmit}>
									<Stack spacing="8" mt="8">
										<Stack direction="row" spacing="8">
											<FormControl isInvalid={!!errors?.firstName}>
												<FormLabel> {__('First Name', 'masteriyo')}</FormLabel>
												<Input
													type="text"
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
												fontSize="small"
												type="submit"
												px="18">
												{__('SAVE', 'masteriyo')}
											</Button>
										</ButtonGroup>
									</Stack>
								</form>
							</TabPanel>
						</TabPanels>
					</Stack>
				</Tabs>
			</Container>
		</Box>
	);
};

export default EditProfile;
