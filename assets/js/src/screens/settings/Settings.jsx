import { Fragment, React } from '@wordpress/element';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from '../layouts/MainToolbar';
const Settings = () => {
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout></MainLayout>
		</Fragment>
	);
};

export default Settings;
