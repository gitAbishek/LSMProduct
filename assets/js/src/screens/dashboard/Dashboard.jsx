import { Component } from '@wordpress/element';
import { Link } from 'react-router-dom';
import { Button, Layout, Menu, Icon } from 'antd';
import Name from '../../components/Name';

const { Header, Content, Footer } = Layout;
export default class Dashboard extends Component {
	render() {
		return (
			<div>
				<Link></Link>
				<Button></Button>
				<Header style={{ padding: 0 }}>
					<testComp />
					<Icon
						type="expand"
						theme="outline"
						style={{ backgroundColor: 'red' }}
					/>
					<Name />
					<Menu mode="horizontal">
						<Menu.Item key="course">Course</Menu.Item>
						<Menu.Item key="course-builder">
							Course Builder
						</Menu.Item>
						<Menu.Item key="settings">Ok</Menu.Item>
					</Menu>
				</Header>
				<Content></Content>
				<Footer></Footer>
			</div>
		);
	}
}
