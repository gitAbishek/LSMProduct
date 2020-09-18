import { Fragment, React } from '@wordpress/element';
import FlexRow from '../../components/common/FlexRow';
import Flex from '../../components/common/Flex';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from './../layouts/MainToolbar';
import Input from '../../components/common/Input';
import FormGroup from '../../components/common/FormGroup';
const Dashboard = () => {
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<FlexRow>
					<Flex>
						<form action="">
							<FormGroup>
								<label htmlFor="">Course Title</label>
								<Input placeholder="Your Course Title"></Input>
							</FormGroup>
						</form>
					</Flex>
				</FlexRow>
			</MainLayout>
		</Fragment>
	);
};

export default Dashboard;
