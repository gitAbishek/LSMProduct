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
import { SkeletonInstructorsList } from '../../../../skeleton/index';
import API from '../../../../utils/api';
import { deepMerge, isEmpty } from '../../../../utils/utils';
import UserHeader from '../../UserHeader';
import InstructorList from './InstructorList';
import InstructorsFilter from './InstructorsFilter';

interface FilterParams {
	per_page?: number;
	page?: number;
	role?: string;
	search?: string;
	approved?: boolean | string;
	orderby: string;
	order: 'asc' | 'desc';
}
const Instructors: React.FC = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({
		order: 'desc',
		orderby: 'id',
	});
	const usersAPI = new API(urls.instructors);
	const usersQuery = useQuery(
		['instructorsList', filterParams],
		() => usersAPI.list(filterParams),
		{
			keepPreviousData: true,
		}
	);
	const history = useHistory();

	const filterInstructorsBy = (order: 'asc' | 'desc', orderBy: string) =>
		setFilterParams(
			deepMerge({
				filterParams,
				order: order,
				orderby: orderBy,
			})
		);

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
									<Th>
										<Stack direction="row" alignItems="center">
											<Text>{__('Name', 'masteriyo')}</Text>
											<Stack direction="column">
												{filterParams?.order === 'desc' ? (
													<Icon
														as={MdOutlineArrowDropUp}
														h={6}
														w={6}
														cursor="pointer"
														color="lightgray"
														transition="1s"
														_hover={{ color: 'black' }}
														onClick={() => filterInstructorsBy('asc', 'name')}
													/>
												) : (
													<Icon
														as={MdOutlineArrowDropDown}
														h={6}
														w={6}
														cursor="pointer"
														color="lightgray"
														transition="1s"
														_hover={{ color: 'black' }}
														onClick={() => filterInstructorsBy('desc', 'name')}
													/>
												)}
											</Stack>
										</Stack>
									</Th>
									<Th>{__('Email', 'masteriyo')}</Th>
									<Th>{__('Registered On', 'masteriyo')}</Th>
									<Th>{__('Actions', 'masteriyo')}</Th>
								</Tr>
							</Thead>
							<Tbody>
								{usersQuery.isLoading && <SkeletonInstructorsList />}
								{usersQuery.isSuccess && isEmpty(usersQuery?.data?.data) ? (
									<EmptyInfo
										message={__('No instructors found.', 'masteriyo')}
									/>
								) : (
									usersQuery?.data?.data?.map((user: any) => (
										<InstructorList key={user?.id} data={user} />
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
						perPageText={__('Instructors Per Page:', 'masteriyo')}
						extraFilterParams={{
							approved: filterParams.approved,
							search: filterParams.search,
							order: filterParams?.order,
							orderby: filterParams?.orderby,
						}}
					/>
				)}
			</Container>
		</Stack>
	);
};

export default Instructors;
