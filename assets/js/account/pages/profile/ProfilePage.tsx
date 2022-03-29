import {
	Avatar,
	Button,
	CSSObject,
	Flex,
	Heading,
	Stack,
	Text,
} from '@chakra-ui/react';
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

	const tableStyles: CSSObject = {
		table: {
			tr: {
				td: {
					fontSize: 'sm',
					color: 'gray.600',

					':first-child': {
						fontWeight: 'medium',
						color: 'gray.900',
					},
				},
			},
		},
	};

	if (isSuccess) {
		return (
			<Stack direction="column" spacing="8" width="full">
				<Flex justify={'space-between'}>
					<Heading as="h4" size="md" fontWeight="bold" color="blue.900">
						{__('Profile', 'masteriyo')}
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
				<Stack direction="row" spacing="6" sx={tableStyles}>
					<Avatar src={data?.profile_image?.url} size="2xl" />

					<Stack direction="column" spacing="6" flex="1">
						<Text as="h3" fontSize="4xl" fontWeight="medium">
							{data?.first_name && data?.last_name
								? `${data?.first_name} ${data?.last_name}`
								: data?.username}
						</Text>

						<Table>
							<Tbody>
								<Tr>
									<Td>{__('First Name', 'masteriyo')}</Td>
									<Td>{data?.first_name}</Td>
								</Tr>
								<Tr>
									<Td>{__('Last Name', 'masteriyo')}</Td>
									<Td>{data?.last_name}</Td>
								</Tr>
								<Tr>
									<Td>{__('Username', 'masteriyo')}</Td>
									<Td>{data?.username}</Td>
								</Tr>
								<Tr>
									<Td>{__('Email', 'masteriyo')}</Td>
									<Td>{data?.email}</Td>
								</Tr>
							</Tbody>
						</Table>

						<Heading fontSize="lg" px="6">
							{__('Billing', 'masteriyo')}
						</Heading>

						<Table>
							<Tbody>
								<Tr>
									<Td>{__('First Name', 'masteriyo')}</Td>
									<Td>{data?.billing?.first_name}</Td>
								</Tr>
								<Tr>
									<Td>{__('Last Name', 'masteriyo')}</Td>
									<Td>{data?.billing?.last_name}</Td>
								</Tr>
								<Tr>
									<Td>{__('Contact Number', 'masteriyo')}</Td>
									<Td>{data?.billing?.phone}</Td>
								</Tr>
								<Tr>
									<Td>{__('Country', 'masteriyo')}</Td>
									<Td>{data?.billing?.country}</Td>
								</Tr>
								<Tr>
									<Td>{__('State', 'masteriyo')}</Td>
									<Td>{data?.billing?.state}</Td>
								</Tr>
								<Tr>
									<Td>{__('City', 'masteriyo')}</Td>
									<Td>{data?.billing?.city}</Td>
								</Tr>
								<Tr>
									<Td>{__('Zip Code', 'masteriyo')}</Td>
									<Td>{data?.billing?.postcode}</Td>
								</Tr>
								<Tr>
									<Td>{__('Address 1', 'masteriyo')}</Td>
									<Td>{data?.billing?.address_1}</Td>
								</Tr>
								<Tr>
									<Td>{__('Address 2', 'masteriyo')}</Td>
									<Td>{data?.billing?.address_2}</Td>
								</Tr>
								<Tr>
									<Td>{__('Company Name', 'masteriyo')}</Td>
									<Td>{data?.billing?.company_name}</Td>
								</Tr>
								<Tr>
									<Td>{__('Company VAT Number', 'masteriyo')}</Td>
									<Td>{data?.billing?.company_id}</Td>
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
