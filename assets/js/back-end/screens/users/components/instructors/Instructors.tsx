import { Box, Container, Icon, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiPlus } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { useHistory } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../../../components/common/EmptyInfo';
import MasteriyoPagination from '../../../../components/common/MasteriyoPagination';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { SkeletonInstructorsList } from '../../../../skeleton/index';
import API from '../../../../utils/api';
import UserHeader from '../../UserHeader';
import InstructorList from './InstructorList';
import InstructorsFilter from './InstructorsFilter';

interface FilterParams {
	per_page?: number;
	page?: number;
	role?: string;
	search?: string;
	approved?: boolean | string;
}
const Instructors: React.FC = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({});
	const usersAPI = new API(urls.instructors);
	const usersQuery = useQuery(['instructorsList', filterParams], () =>
		usersAPI.list(filterParams)
	);
	const history = useHistory();
	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<UserHeader
				thirdBtn={{
					label: __('Add New Instructor', 'masteriyo'),
					action: () => history.push(routes.users.instructors.add),
					icon: <Icon as={BiPlus} fontSize="md" />,
				}}
			/>
			<Container maxW="container.xl">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<InstructorsFilter
							setFilterParams={setFilterParams}
							filterParams={filterParams}
						/>
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
								{usersQuery.isLoading && <SkeletonInstructorsList />}
								{usersQuery.isSuccess && usersQuery?.data?.data.length === 0 ? (
									<EmptyInfo
										message={__('No instructors found.', 'masteriyo')}
									/>
								) : (
									usersQuery?.data?.data.map((user: any) => (
										<InstructorList key={user.id} data={user} />
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
						perPageText={__('Instructors Per Page:', 'masteriyo')}
						extraFilterParams={{
							approved: filterParams.approved,
							search: filterParams.search,
						}}
					/>
				)}
			</Container>
		</Stack>
	);
};

export default Instructors;
