import { Fragment, React } from '@wordpress/element';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from '../layouts/MainToolbar';

import GettingStarted from './components/GettingStarted';
const Dashboard = () => {
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<GettingStarted />
				{/* <SortableSection /> */}
			</MainLayout>
		</Fragment>
	);
};

export default Dashboard;
