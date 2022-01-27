import { Image } from '@chakra-ui/image';
import { Button, Flex, Heading, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiEdit } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { Link } from 'react-router-dom';
import { Table, Tbody, Td, Tr } from 'react-super-responsive-table';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { UserSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import routes from '../../constants/routes';

const ProfilePage = () => {
	const userAPI = new API(urls.currentUser);

	const { data, isSuccess } = useQuery<UserSchema>('userProfile', () =>
		userAPI.get()
	);

	if (isSuccess) {
		return (
			<Stack direction="column" spacing="8" width="full">
				<Flex justify={'space-between'}>
					<Heading as="h4" size="md" fontWeight="bold" color="blue.900" px="8">
						{__('Order History', 'masteriyo')}
					</Heading>
					<Link to={routes.user.edit}>
						<Button
							leftIcon={<BiEdit size={15} color={'gray.500'} />}
							color="gray.500"
							size="sm"
							borderRadius="full"
							borderColor="gray.400"
							variant="outline">
							{__('Edit Profile', 'masteriyo')}
						</Button>
					</Link>
				</Flex>
				<Stack direction="row" spacing="6">
					<Image
						src={data?.avatar_url}
						alt={data?.first_name}
						borderRadius="full"
						boxSize="10rem"
						border="2px"
						borderColor="gray.100"
					/>
					<Stack direction="column" spacing="6" flex="1">
						<Text as="h3" fontSize="4xl" fontWeight="medium">
							{data?.first_name} {data?.last_name}
						</Text>

						<Table>
							<Tbody>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('First Name', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.first_name}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Last Name', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.last_name}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Username', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.username}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Email', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.email}
									</Td>
								</Tr>
							</Tbody>
						</Table>
						<Heading fontSize="lg" px="6">
							{__('Billing', 'masteriyo')}
						</Heading>
						<Table>
							<Tbody>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('First Name', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.first_name}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Last Name', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.last_name}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Contact Number', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.phone}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Country', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.country}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('State', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.state}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('City', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.city}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Zip Code', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.postcode}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Address 1', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.address_1}
									</Td>
								</Tr>
								<Tr>
									<Td fontSize="sm" fontWeight="medium" borderBottom="none">
										{__('Address 2', 'masteriyo')}
									</Td>
									<Td fontSize="sm" color="gray" borderBottom="none">
										{data?.billing?.address_2}
									</Td>
								</Tr>
							</Tbody>
						</Table>
					</Stack>
				</Stack>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default ProfilePage;
