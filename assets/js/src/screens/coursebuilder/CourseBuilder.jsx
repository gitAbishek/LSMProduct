import { Fragment, React, useState } from '@wordpress/element';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from '../layouts/MainToolbar';
import SortableSection from './components/SortableSection';
import GettingStarted from './components/GettingStarted';
const Dashboard = () => {
	const [courseMode, setCourseMode] = useState('courseBuilder');
	return (
		<Fragment>
			<MainToolbar />
			{courseMode === 'gettingStarted' && <GettingStarted />}
			{courseMode === 'courseBuilder' && <SortableSection />}
		</Fragment>
	);
};

export default Dashboard;
