import { Image } from '@chakra-ui/image';
import {
	Alert,
	AlertDescription,
	AlertTitle,
	Button,
	Heading,
	Stack,
	Text,
} from '@chakra-ui/react';
import { Table, Tbody, Td, Tr } from '@chakra-ui/table';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { IoIosArrowForward } from 'react-icons/io';
import { useQuery } from 'react-query';
import { Link } from 'react-router-dom';
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
				<Alert bg="blue.500" color="white" p="6">
					<Stack direction="column" spacing="5">
						<Stack direction="column" spacing="0.5">
							<AlertTitle>
								{__('Hello, ', 'masteriyo')} {data?.first_name}
							</AlertTitle>
							<AlertDescription display="block" fontSize="sm">
								{__(
									'You can edit your profile here. Would you like to edit your profile?',
									'masteriyo'
								)}
							</AlertDescription>
						</Stack>
						<Link to={routes.editProfile}>
							<Button
								rightIcon={<IoIosArrowForward size={15} color={'gray.500'} />}
								color="gray.500"
								bg="white"
								px="6"
								textTransform="uppercase"
								rounded="full">
								{__('Edit Profile', 'masteriyo')}
							</Button>
						</Link>
					</Stack>
				</Alert>

				<Stack direction="row" spacing="6">
					<Image
						src={data?.avatar_url}
						alt={data?.first_name}
						borderRadius="full"
						boxSize="10rem"
						border="2px"
						borderColor="gray.100"
					/>
					<Stack direction="column" spacing="6">
						<Text as="h3" fontSize="4xl" fontWeight="medium" px="6">
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
