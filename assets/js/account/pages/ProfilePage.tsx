import Icon from '@chakra-ui/icon';
import { Image } from '@chakra-ui/image';
import { Box, Container, Stack, Text } from '@chakra-ui/layout';
import { Table, Td, Tr } from '@chakra-ui/table';
import { Tab, TabList, TabPanel, TabPanels, Tabs } from '@chakra-ui/tabs';
import { __ } from '@wordpress/i18n';
import React from 'react';
import {
	BiBook,
	BiBorderAll,
	BiCertification,
	BiHistory,
	BiUserCircle,
} from 'react-icons/bi';
import { tabListStyles, tabStyles } from '../../back-end/config/styles';

const ProfilePage = () => {
	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Container maxW="container.xl">
				<Box bg="white" p="10" shadow="box" mx="auto">
					<Tabs orientation="vertical">
						<Stack direction="row" flex="1">
							<TabList sx={tabListStyles}>
								<Tab sx={tabStyles}>
									<Icon as={BiBorderAll} fontSize="md" mr="2" />
									{__('Dashboard', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiUserCircle} fontSize="md" mr="2" />
									{__('My Profile', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiBook} fontSize="md" mr="2" />
									{__('My Courses', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiBook} fontSize="md" mr="2" />
									{__('My Grades', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiBook} fontSize="md" mr="2" />
									{__('My Memberships', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiCertification} fontSize="md" mr="2" />
									{__('My Certificates', 'masteriyo')}
								</Tab>
								<Tab sx={tabStyles}>
									<Icon as={BiHistory} fontSize="md" mr="2" />
									{__('Order History', 'masteriyo')}
								</Tab>
							</TabList>
							<TabPanels flex="1">
								<TabPanel></TabPanel>
								<TabPanel>
									<Stack direction="row" spacing="10">
										<Image
											src="https://bit.ly/sage-adebayo"
											alt="Segun Adebayo"
											borderRadius="full"
											boxSize="10rem"
											border="2px solid #f1f1f1"
											mt="5"
										/>
										<Stack direction="column" spacing="8">
											<Box>
												<Text as="h3" fontSize="2.25rem" fontWeight="500">
													{__('Jamie Oliver', 'masteriyo')}
												</Text>
												<Text
													as="span"
													color="#acacbe"
													fontSize="0.9rem"
													fontWeight="500">
													{__('Gold Member', 'masteriyo')}
												</Text>
											</Box>
											<Table>
												<Tr>
													<Td
														fontSize="0.9rem"
														fontWeight="500"
														color="#000000"
														pl="0"
														borderBottom="none">
														{__('Email', 'masteriyo')}
													</Td>
													<Td
														fontSize="0.9rem"
														color="#7C7D8F"
														borderBottom="none">
														{__('jamie.oliver@gmail.com', 'masteriyo')}
													</Td>
												</Tr>
												<Tr>
													<Td
														fontSize="0.9rem"
														fontWeight="500"
														color="#000000"
														pl="0"
														borderBottom="none">
														{__('Contact Number', 'masteriyo')}
													</Td>
													<Td
														fontSize="0.9rem"
														color="#7C7D8F"
														borderBottom="none">
														{__('+8 123-489-1236', 'masteriyo')}
													</Td>
												</Tr>
												<Tr>
													<Td
														fontSize="0.9rem"
														fontWeight="500"
														color="#000000"
														pl="0"
														borderBottom="none">
														{__('Address', 'masteriyo')}
													</Td>
													<Td
														fontSize="0.9rem"
														color="#7C7D8F"
														borderBottom="none">
														{__('123 Moon Street, Mars', 'masteriyo')}
													</Td>
												</Tr>
												<Tr>
													<Td
														fontSize="0.9rem"
														fontWeight="500"
														color="#000000"
														pl="0"
														borderBottom="none">
														{__('City', 'masteriyo')}
													</Td>
													<Td
														fontSize="0.9rem"
														color="#7C7D8F"
														borderBottom="none">
														{__('Nuwa', 'masteriyo')}
													</Td>
												</Tr>
												<Tr>
													<Td
														fontSize="0.9rem"
														fontWeight="500"
														color="#000000"
														pl="0"
														borderBottom="none">
														{__('State', 'masteriyo')}
													</Td>
													<Td
														fontSize="0.9rem"
														color="#7C7D8F"
														borderBottom="none">
														{__('Abiboo', 'masteriyo')}
													</Td>
												</Tr>
												<Tr>
													<Td
														fontSize="0.9rem"
														fontWeight="500"
														color="#000000"
														pl="0"
														borderBottom="none">
														{__('Zip Code', 'masteriyo')}
													</Td>
													<Td
														fontSize="0.9rem"
														color="#7C7D8F"
														borderBottom="none">
														{__('8899', 'masteriyo')}
													</Td>
												</Tr>
												<Tr>
													<Td
														fontSize="0.9rem"
														fontWeight="500"
														color="#000000"
														pl="0"
														borderBottom="none">
														{__('Country', 'masteriyo')}
													</Td>
													<Td
														fontSize="0.9rem"
														color="#7C7D8F"
														borderBottom="none">
														{__('Sinara', 'masteriyo')}
													</Td>
												</Tr>
											</Table>
										</Stack>
									</Stack>
								</TabPanel>
							</TabPanels>
						</Stack>
					</Tabs>
				</Box>
			</Container>
		</Stack>
	);
};

export default ProfilePage;
