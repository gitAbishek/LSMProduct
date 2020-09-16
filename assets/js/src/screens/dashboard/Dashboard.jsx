import { Fragment, React } from '@wordpress/element';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from './../layouts/MainToolbar';
import SortableSection from './components/SortableSection';
const Dashboard = () => {
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<SortableSection />
			</MainLayout>
		</Fragment>
	);
};

export default Dashboard;
