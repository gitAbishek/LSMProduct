import { React, Fragment } from '@wordpress/element';
import MainLayout from '../layouts/MainLayout';
import MainToolbar from './../layouts/MainToolbar';

const Dashboard = () => {
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<span>hello</span>
			</MainLayout>
		</Fragment>
	);
};

export default Dashboard;
