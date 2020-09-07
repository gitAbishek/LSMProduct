import { Component } from '@wordpress/element';
import { Link } from 'react-router-dom';
import { Button, Layout, Menu } from 'antd';

const { Header, Content, Footer } = Layout;
export default class Dashboard extends Component {
	render() {
		return (
			<Layout>
				<Header style={{ padding: 0 }}>
					<Menu mode="horizontal">
						<Menu.Item key="course">Course</Menu.Item>
						<Menu.Item key="course-builder">
							Course Builder
						</Menu.Item>
						<Menu.Item key="settings">Settings</Menu.Item>
					</Menu>
				</Header>
				<Content></Content>
				<Footer></Footer>
			</Layout>
		);
	}
}
