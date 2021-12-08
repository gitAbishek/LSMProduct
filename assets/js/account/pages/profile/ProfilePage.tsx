import { Image } from '@chakra-ui/image';
import {
	Alert,
	AlertDescription,
	AlertTitle,
	Box,
	Button,
	Stack,
	Text,
} from '@chakra-ui/react';
import { Table, Td, Tr } from '@chakra-ui/table';
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
	// temporary userId
	const userId = 1;
	const userAPI = new API(urls.users);

	const userQuery = useQuery<UserSchema>([`userProfile${userId}`, userId], () =>
		userAPI.get(userId)
	);

	if (userQuery.isSuccess) {
		return (
			<Stack direction="column" spacing="8" width="full">
				<Alert bg="blue.500" color="white" p="6">
					<Stack direction="column" spacing="5">
						<Stack direction="column" spacing="0.5">
							<AlertTitle>{__('Hello, Jamie', 'masteriyo')}</AlertTitle>
							<AlertDescription display="block" fontSize="sm">
								{__(
									'	Welcome to your dashboard here you can edit your overview and your stats',
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

				<Stack direction="row" spacing="10">
					<Image
						src="https://bit.ly/sage-adebayo"
						alt="Segun Adebayo"
						borderRadius="full"
						boxSize="10rem"
						border="2px"
						borderColor="gray.100"
					/>
					<Stack direction="column" spacing="8">
						<Box>
							<Text as="h3" fontSize="4xl" fontWeight="medium">
								{__('Jamie Oliver', 'masteriyo')}
							</Text>
							<Text
								as="span"
								color="gray.400"
								fontSize="sm"
								fontWeight="medium">
								{__('Gold Member', 'masteriyo')}
							</Text>
						</Box>
						<Table>
							<Tr>
								<Td fontSize="sm" fontWeight="medium" borderBottom="none">
									{__('Email', 'masteriyo')}
								</Td>
								<Td fontSize="sm" color="gray" borderBottom="none">
									{__('jamie.oliver@gmail.com', 'masteriyo')}
								</Td>
							</Tr>
							<Tr>
								<Td fontSize="md" fontWeight="medium" borderBottom="none">
									{__('Contact Number', 'masteriyo')}
								</Td>
								<Td fontSize="sm" color="gray" borderBottom="none">
									{__('123-456-7980', 'masteriyo')}
								</Td>
							</Tr>
							<Tr>
								<Td fontSize="sm" fontWeight="medium" borderBottom="none">
									{__('Address', 'masteriyo')}
								</Td>
								<Td fontSize="sm" color="gray" borderBottom="none">
									{__('123 Moon Street, Mars', 'masteriyo')}
								</Td>
							</Tr>
							<Tr>
								<Td fontSize="sm" fontWeight="medium" borderBottom="none">
									{__('City', 'masteriyo')}
								</Td>
								<Td fontSize="sm" color="gray" borderBottom="none">
									{__('Nuwa', 'masteriyo')}
								</Td>
							</Tr>
							<Tr>
								<Td fontSize="sm" fontWeight="medium" borderBottom="none">
									{__('State', 'masteriyo')}
								</Td>
								<Td fontSize="sm" color="gray" borderBottom="none">
									{__('Abiboo', 'masteriyo')}
								</Td>
							</Tr>
							<Tr>
								<Td fontSize="sm" fontWeight="medium" borderBottom="none">
									{__('Zip Code', 'masteriyo')}
								</Td>
								<Td fontSize="sm" color="gray" borderBottom="none">
									{__('8899', 'masteriyo')}
								</Td>
							</Tr>
							<Tr>
								<Td fontSize="sm" fontWeight="medium" borderBottom="none">
									{__('Country', 'masteriyo')}
								</Td>
								<Td fontSize="sm" color="gray" borderBottom="none">
									{__('Sinara', 'masteriyo')}
								</Td>
							</Tr>
						</Table>
					</Stack>
				</Stack>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default ProfilePage;
