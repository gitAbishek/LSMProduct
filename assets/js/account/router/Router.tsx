import { Box, Container, Stack } from '@chakra-ui/react';
import React from 'react';
import { HashRouter, Redirect, Route, Switch } from 'react-router-dom';
import Sidebar from '../components/Sidebar';
import routes from '../constants/routes';
import MyCourses from '../pages/courses/MyCouses';
import Dashboard from '../pages/dashboard/Dashboard';
import OrderDetails from '../pages/order/OrderDetails';
import OrderHistory from '../pages/order/OrderHistory';
import EditProfile from '../pages/profile/EditProfile';
import ProfilePage from '../pages/profile/ProfilePage';

const Router: React.FC = () => {
	return (
		<Box bg="white" minH="100vh">
			<HashRouter>
				<Container maxW="container.xl" py="16">
					<Stack direction="row" spacing="8">
						<Sidebar />
						<Box flex="1">
							<Switch>
								<Route path={routes.dashboard} exact>
									<Dashboard />
								</Route>
								<Route path={routes.courses} exact>
									<MyCourses />
								</Route>
								<Route path={routes.order.list} exact>
									<OrderHistory />
								</Route>
								<Route path={routes.order.view} exact>
									<OrderDetails />
								</Route>
								<Route path={routes.user.profile} exact>
									<ProfilePage />
								</Route>
								<Route path={routes.user.edit} exact>
									<EditProfile />
								</Route>
								<Route>
									<Redirect to={routes.dashboard} />
								</Route>
							</Switch>
						</Box>
					</Stack>
				</Container>
			</HashRouter>
		</Box>
	);
};

export default Router;
