import { Avatar, Button, Flex, Heading, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiEdit } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { Link } from 'react-router-dom';
import urls from '../../../back-end/constants/urls';
import { UserSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import routes from '../../constants/routes';
import ProfileSkeleton from '../../skeleton/Profile';

const ProfilePage = () => {
	const userAPI = new API(urls.currentUser);

	const { data, isSuccess } = useQuery<UserSchema>('userProfile', () =>
		userAPI.get()
	);

	const userInfo = (key: string, value: string = '', bg: boolean = false) => (
		<Stack
			p="12px 40px"
			bg={bg ? '#f8f9fa' : ''}
			marginTop="0px !important"
			direction={{ base: 'column', sm: 'row', md: 'row', lg: 'row' }}
			justifyContent="space-between">
			<Text>{__(key, 'masteriyo')}</Text>
			<Text>{value}</Text>
		</Stack>
	);

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
				<Stack
					direction={{ base: 'column', sm: 'column', md: 'row', lg: 'row' }}
					spacing="6">
					<Avatar src={data?.profile_image?.url} size="2xl" />

					<Stack direction="column" spacing="8" flex="1">
						<Text as="h3" fontSize="4xl" fontWeight="medium">
							{data?.first_name && data?.last_name
								? `${data?.first_name} ${data?.last_name}`
								: data?.username}
						</Text>

						<Stack direction="column">
							{userInfo('First Name', data?.first_name, true)}
							{userInfo('Last Name', data?.last_name)}
							{userInfo('Username', data?.username, true)}
							{userInfo('Email', data?.email)}
						</Stack>

						<Heading fontSize="lg" px="6">
							{__('Billing', 'masteriyo')}
						</Heading>

						<Stack direction="column">
							{userInfo('First Name', data?.billing?.first_name, true)}
							{userInfo('Last Name', data?.billing?.last_name)}
							{userInfo('Contact Number', data?.billing?.phone, true)}
							{userInfo('Country', data?.billing?.country)}
							{userInfo('State', data?.billing?.state, true)}
							{userInfo('City', data?.billing?.city)}
							{userInfo('Zip Code', data?.billing?.postcode, true)}
							{userInfo('Address 1', data?.billing?.address_1)}
							{userInfo('Address 2', data?.billing?.address_2, true)}
							{userInfo('Company Name', data?.billing?.company_name)}
							{userInfo('Company VAT Number', data?.billing?.company_id, true)}
						</Stack>
					</Stack>
				</Stack>
			</Stack>
		);
	}

	return <ProfileSkeleton />;
};

export default ProfilePage;
