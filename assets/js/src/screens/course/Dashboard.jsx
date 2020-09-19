import { Fragment, React } from '@wordpress/element';
import FlexRow from '../../components/common/FlexRow';
import Flex from '../../components/common/Flex';

import MainLayout from '../layouts/MainLayout';
import MainToolbar from './../layouts/MainToolbar';
import Input from '../../components/common/Input';
import FormGroup from '../../components/common/FormGroup';
import Label from '../../components/common/Label';
import Textarea from '../../components/common/Textarea';
import Select from '../../components/common/Select';
import DropPlace from '../../components/common/DropPlace';

const Dashboard = () => {
	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<FlexRow align="flex-start">
					<Flex style={{ width: 'calc(60% - 16px)' }}>
						<form action="">
							<FormGroup>
								<Label htmlFor="">Course Title</Label>
								<Input placeholder="Your Course Title"></Input>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Course Description</Label>
								<Textarea placeholder="Your Course Title"></Textarea>
							</FormGroup>
							<FormGroup>
								<Label htmlFor="">Categories</Label>
								<DropPlace />
							</FormGroup>
						</form>
					</Flex>

					<Flex style={{ width: 'calc(40% - 16px)', marginLeft: 32 }}>
						<form action="">
							<FormGroup>
								<Label htmlFor="">Course Title</Label>
								<Input placeholder="Your Course Title"></Input>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Categories</Label>
								<Select
									isDisabled
									options={[
										{ value: 'chocolate', label: 'Chocolate' },
										{ value: 'strawberry', label: 'Strawberry' },
										{ value: 'vanilla', label: 'Vanilla' },
									]}
								/>
							</FormGroup>
						</form>
					</Flex>
				</FlexRow>
			</MainLayout>
		</Fragment>
	);
};

export default Dashboard;
