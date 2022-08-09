import { Box, Container, Icon, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiPlus } from 'react-icons/bi';
import { MdOutlineArrowDropDown, MdOutlineArrowDropUp } from 'react-icons/md';
import { useQuery } from 'react-query';
import { useHistory } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../../../components/common/EmptyInfo';
import MasteriyoPagination from '../../../../components/common/MasteriyoPagination';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { SkeletonStudentsList } from '../../../../skeleton';
import API from '../../../../utils/api';
import { deepMerge, isEmpty } from '../../../../utils/utils';
import UserHeader from '../../UserHeader';
import StudentList from './StudentList';
import StudentsFilter from './StudentsFilter';

interface FilterParams {
	per_page?: number;
	page?: number;
	role?: string;
	search?: string;
	orderby: string;
	order: 'asc' | 'desc';
}

const Students: React.FC = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({
		role: 'masteriyo_student',
		order: 'desc',
		orderby: 'registered',
	});

	const usersAPI = new API(urls.users);
	const usersQuery = useQuery(
		['usersList', filterParams],
		() => usersAPI.list(filterParams),
		{
			keepPreviousData: true,
		}
	);
	const history = useHistory();

	const filterStudentsBy = (order: 'asc' | 'desc', orderBy: string) =>
		setFilterParams(
			deepMerge({
				...filterParams,
				order: order,
				orderby: orderBy,
			})
		);

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<UserHeader
				thirdBtn={{
					label: __('Add New Student', 'masteriyo'),
					action: () => history.push(routes.users.students.add),
					icon: <Icon as={BiPlus} fontSize="md" />,
				}}
			/>
			<Container maxW="container.xl">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<StudentsFilter setFilterParams={setFilterParams} />
						<Table>
							<Thead>
								<Tr>
									<Th>
										<Stack direction="row" alignItems="center">
											<Text fontSize="xs">{__('Name', 'masteriyo')}</Text>
											<Stack direction="column">
												<Icon
													as={
														filterParams?.order === 'desc'
															? MdOutlineArrowDropDown
															: MdOutlineArrowDropUp
													}
													h={6}
													w={6}
													cursor="pointer"
													color={
														filterParams?.orderby === 'name'
															? 'black'
															: 'lightgray'
													}
													transition="1s"
													_hover={{ color: 'black' }}
													onClick={() =>
														filterStudentsBy(
															filterParams?.order === 'desc' ? 'asc' : 'desc',
															'name'
														)
													}
												/>
											</Stack>
										</Stack>
									</Th>
									<Th>{__('Email', 'masteriyo')}</Th>
									<Th>
										<Stack direction="row" alignItems="center">
											<Text fontSize="xs">
												{__('Registered On', 'masteriyo')}
											</Text>
											<Stack direction="column">
												<Icon
													as={
														filterParams?.order === 'desc'
															? MdOutlineArrowDropDown
															: MdOutlineArrowDropUp
													}
													h={6}
													w={6}
													cursor="pointer"
													color={
														filterParams?.orderby === 'registered'
															? 'black'
															: 'lightgray'
													}
													transition="1s"
													_hover={{ color: 'black' }}
													onClick={() =>
														filterStudentsBy(
															filterParams?.order === 'desc' ? 'asc' : 'desc',
															'registered'
														)
													}
												/>
											</Stack>
										</Stack>
									</Th>
									<Th>{__('Actions', 'masteriyo')}</Th>
								</Tr>
							</Thead>
							<Tbody>
								{usersQuery.isLoading && <SkeletonStudentsList />}
								{usersQuery.isSuccess && isEmpty(usersQuery?.data?.data) ? (
									<EmptyInfo message={__('No students found.', 'masteriyo')} />
								) : (
									usersQuery?.data?.data?.map((user: any) => (
										<StudentList key={user?.id} data={user} />
									))
								)}
							</Tbody>
						</Table>
					</Stack>
				</Box>
				{usersQuery.isSuccess && !isEmpty(usersQuery?.data?.data) && (
					<MasteriyoPagination
						metaData={usersQuery?.data?.meta}
						setFilterParams={setFilterParams}
						perPageText={__('Students Per Page:', 'masteriyo')}
						extraFilterParams={{
							role: 'masteriyo_student',
							order: filterParams?.order,
							orderby: filterParams?.orderby,
						}}
					/>
				)}
			</Container>
		</Stack>
	);
};

export default Students;
