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
	SimpleGrid,
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
import { UserSchema } from '../../../back-end/schemas';
import PasswordSecurity from './PasswordSecurity';

const EditProfile: React.FC = () => {
	const {
		register,
		handleSubmit,
		formState: { errors },
	} = useForm<UserSchema>();
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
								<Tab sx={tabStyles}>
									{__('Password  & Security', 'masteriyo')}
								</Tab>
							</TabList>

							<TabPanels>
								<TabPanel>
									<Center>
										<Avatar
											size="xl"
											name="Rose Pett"
											src="https://api.lorem.space/image/fashion?w=150&h=150"
											background="none"></Avatar>
									</Center>

									<form onSubmit={onSubmit}>
										<Stack direction="column" spacing="6">
											<SimpleGrid columns={2} spacing="6">
												<FormControl isInvalid={!!errors?.first_name}>
													<FormLabel>{__('First Name', 'masteriyo')}</FormLabel>
													<Input
														type="text"
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
														type="number"
														{...register('billing.phone', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
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
														{...register('billing.address_1', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
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
														type="text"
														{...register('billing.city', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
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
														{...register('billing.state', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
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
														{...register('billing.postcode', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
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
														{...register('billing.country', {
															required: __(
																'This field cannot be empty',
																'masteriyo'
															),
														})}
													/>
													{errors?.billing?.country && (
														<FormErrorMessage>
															{errors?.billing?.country.message}
														</FormErrorMessage>
													)}
												</FormControl>
											</Stack>

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
