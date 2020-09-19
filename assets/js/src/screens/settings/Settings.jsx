import { Fragment, React } from '@wordpress/element';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from '../layouts/MainToolbar';
import SortableSection from '../course/components/SortableSection';
const Settings = () => {
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<SortableSection />
			</MainLayout>
		</Fragment>
	);
};

export default Settings;
