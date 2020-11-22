import { Fragment, React, useState } from '@wordpress/element';

import GettingStarted from './components/GettingStarted';
import MainToolbar from '../layouts/MainToolbar';
import SectionBuilder from './section/SectionBuilder';

const Dashboard = () => {
	const [courseMode] = useState('courseBuilder');
	return (
		<Fragment>
			<MainToolbar />
			{courseMode === 'gettingStarted' && <GettingStarted />}
			{courseMode === 'courseBuilder' && <SectionBuilder />}
		</Fragment>
	);
};

export default Dashboard;
