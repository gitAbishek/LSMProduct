import { Box, Container, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useQuery } from 'react-query';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../../../components/common/EmptyInfo';
import MasteriyoPagination from '../../../../components/common/MasteriyoPagination';
import urls from '../../../../constants/urls';
import { SkeletonStudentsList } from '../../../../skeleton';
import API from '../../../../utils/api';
import UserHeader from '../../UserHeader';
import StudentList from './StudentList';
import StudentsFilter from './StudentsFilter';

interface FilterParams {
	per_page?: number;
	page?: number;
	role?: string;
	search?: string;
}

const Students: React.FC = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({
		role: 'masteriyo_student',
	});

	const usersAPI = new API(urls.users);
	const usersQuery = useQuery(['usersList', filterParams], () =>
		usersAPI.list(filterParams)
	);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<UserHeader />
			<Container maxW="container.xl">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<StudentsFilter setFilterParams={setFilterParams} />
						<Table>
							<Thead>
								<Tr>
									<Th>{__('Name', 'masteriyo')}</Th>
									<Th>{__('Email', 'masteriyo')}</Th>
									<Th>{__('Registered On', 'masteriyo')}</Th>
									<Th>{__('Actions', 'masteriyo')}</Th>
								</Tr>
							</Thead>
							<Tbody>
								{usersQuery.isLoading && <SkeletonStudentsList />}
								{usersQuery.isSuccess && usersQuery?.data?.data.length === 0 ? (
									<EmptyInfo message={__('No students found.', 'masteriyo')} />
								) : (
									usersQuery?.data?.data.map((user: any) => (
										<StudentList key={user.id} data={user} />
									))
								)}
							</Tbody>
						</Table>
					</Stack>
				</Box>
				{usersQuery.isSuccess && usersQuery?.data?.data.length > 0 && (
					<MasteriyoPagination
						metaData={usersQuery.data.meta}
						setFilterParams={setFilterParams}
						perPageText={__('Students Per Page:', 'masteriyo')}
						extraFilterParams={{ role: 'masteriyo_student' }}
					/>
				)}
			</Container>
		</Stack>
	);
};

export default Students;