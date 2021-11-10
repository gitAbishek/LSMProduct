import { Box, Container, Stack } from '@chakra-ui/react';
import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import Sidebar from '../components/Sidebar';
import routes from '../constants/routes';
import MyCourses from '../pages/courses/MyCouses';
import Dashboard from '../pages/dashboard/Dashboard';
import EditProfile from '../pages/EditProfile';
import Header from '../pages/Header';
import OrderHistory from '../pages/order-history/OrderHistory';
import ProfilePage from '../pages/ProfilePage';

const Router: React.FC = () => {
	return (
		<Box bg="white" minH="100vh">
			<HashRouter>
				<Header />
				<Container maxW="container.xl" py="16">
					<Stack direction="row" spacing="8">
						<Sidebar />
						<Switch>
							<Route path={routes.dashboard} exact>
								<Dashboard />
							</Route>
							<Route path={routes.myCourses} exact>
								<MyCourses />
							</Route>
							<Route path={routes.myOrderHistory} exact>
								<OrderHistory />
							</Route>
							<Route path={routes.myProfile} exact>
								<ProfilePage />
							</Route>
						</Switch>
						<Route path={routes.editProfile} exact>
							<EditProfile />
						</Route>
					</Stack>
				</Container>
			</HashRouter>
		</Box>
	);
};

export default Router;
