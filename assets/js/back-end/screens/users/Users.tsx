import {
	Container,
	Icon,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { FaUserFriends } from 'react-icons/fa';
import { useQuery } from 'react-query';
import Header from '../../components/common/Header';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Students from './components/students/Students';

interface FilterParams {
	per_page?: number;
	page?: number;
	role?: string;
	search?: string;
}

const tabStyles = {
	fontWeight: 'medium',
	fontSize: 'sm',
	py: '6',
	px: 0,
	mx: 4,
};

const tabPanelStyles = {
	px: '0',
};

const iconStyles = {
	mr: '2',
};

const Users: React.FC = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({
		role: 'masteriyo_student',
	});
	const usersAPI = new API(urls.users);
	const usersQuery = useQuery(['usersList', filterParams], () =>
		usersAPI.list(filterParams)
	);

	return (
		<Tabs>
			<Stack direction="column" spacing="8" alignItems="center">
				<Header showLinks>
					<TabList borderBottom="none" bg="white">
						<Tab
							onClick={() => setFilterParams({ role: 'masteriyo_student' })}
							sx={tabStyles}>
							<Icon as={FaUserFriends} sx={iconStyles} />
							{__('Students', 'masteriyo')}
						</Tab>
					</TabList>
				</Header>
				<Container maxW="container.xl">
					<TabPanels>
						<TabPanel sx={tabPanelStyles}>
							<Students
								usersQuery={usersQuery}
								setFilterParams={setFilterParams}
							/>
						</TabPanel>
					</TabPanels>
				</Container>
			</Stack>
		</Tabs>
	);
};

export default Users;
