import { Fragment, React, useState } from '@wordpress/element';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from '../layouts/MainToolbar';

import GettingStarted from './components/GettingStarted';
const Dashboard = () => {
	const [courseMode, setCourseMode] = useState('gettingStarted');
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				{courseMode === 'gettingStarted' && <GettingStarted />}
				{/* <SortableSection /> */}
			</MainLayout>
		</Fragment>
	);
};

export default Dashboard;
