import {
	Avatar,
	Box,
	Button,
	ButtonGroup,
	Center,
	Container,
	FormControl,
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

const EditProfile = () => {
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
								<Stack>
									<Center>
										<Avatar
											size="xl"
											name="Rose Pett"
											mt="10"
											src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8dXNlcnxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
										/>{' '}
									</Center>
								</Stack>
								<Stack spacing="8" mt="8">
									<Stack direction="row" spacing="8">
										<FormControl id="Fisrt Name">
											<FormLabel> {__('First Name', 'masteriyo')}</FormLabel>
											<Input type="text" />
										</FormControl>
										<FormControl id="Last Name">
											<FormLabel>{__('Last Name', 'masteriyo')}</FormLabel>
											<Input type="text" />
										</FormControl>
									</Stack>

									<Stack>
										<FormControl id="email">
											<FormLabel>{__('Email', 'masteriyo')}</FormLabel>
											<Input type="email" />
										</FormControl>
									</Stack>
									<Stack>
										<FormControl id="contact">
											<FormLabel>{__('Contact Number', 'masteriyo')}</FormLabel>
											<Input type="number" />
										</FormControl>
									</Stack>
									<Stack>
										<FormControl id="address">
											<FormLabel>{__('Address', 'masteriyo')}</FormLabel>
											<Input type="type" />
										</FormControl>
									</Stack>
									<Stack direction="row" spacing="8">
										<FormControl id="city">
											<FormLabel>{__('City', 'masteriyo')}</FormLabel>
											<Input type="text" />
										</FormControl>
										<FormControl id="state">
											<FormLabel>{__('State', 'masteriyo')}</FormLabel>
											<Input type="text" />
										</FormControl>
									</Stack>

									<Stack direction="row" spacing="8">
										<FormControl id="zipcode">
											<FormLabel>{__('Zip Code', 'masteriyo')}</FormLabel>
											<Input type="number" />
										</FormControl>
										<FormControl id="country">
											<FormLabel>{__('Country', 'masteriyo')}</FormLabel>
											<Input type="text" />
										</FormControl>
									</Stack>
								</Stack>
								<Stack py="10">
									<ButtonGroup>
										<Button
											colorScheme="blue"
											rounded="full"
											fontSize="small"
											px="18">
											{__('SAVE', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</Stack>
							</TabPanel>
						</TabPanels>
					</Stack>
				</Tabs>
			</Container>
		</Box>
	);
};

export default EditProfile;
