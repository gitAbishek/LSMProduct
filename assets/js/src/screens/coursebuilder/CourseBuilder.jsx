import { Fragment, React, useState } from '@wordpress/element';

import MainToolbar from '../layouts/MainToolbar';
import SortableSections from './components/SortableSections';
import GettingStarted from './components/GettingStarted';
const Dashboard = () => {
	const [courseMode, setCourseMode] = useState('courseBuilder');
	return (
		<Fragment>
			<MainToolbar />
			{courseMode === 'gettingStarted' && <GettingStarted />}
			{courseMode === 'courseBuilder' && <SortableSections />}
		</Fragment>
	);
};

export default Dashboard;
