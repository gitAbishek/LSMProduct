import { Box, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../../../components/common/EmptyInfo';
import MasteriyoPagination from '../../../../components/common/MasteriyoPagination';
import { SkeletonUsersList } from '../../../../skeleton';
import StudentList from './StudentList';
import StudentsFilter from './StudentsFilter';

interface Props {
	usersQuery: any;
	setFilterParams: any;
}

const Students: React.FC<Props> = (props) => {
	const { usersQuery, setFilterParams } = props;

	return (
		<>
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
							{usersQuery.isLoading && <SkeletonUsersList />}
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
		</>
	);
};

export default Students;
