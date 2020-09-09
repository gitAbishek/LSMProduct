import { Component } from '@wordpress/element';
import { Link } from 'react-router-dom';
import { Button, Layout, Menu, Icon } from 'antd';

const { Header, Content, Footer } = Layout;
export default class Dashboard extends Component {
	render() {
		return (
			<Layout>
				<Header style={{ padding: 0 }}>
					<Icon type="expand" />
					<Menu mode="horizontal">
						<Menu.Item key="course">{Course.lyang}</Menu.Item>
						<Menu.Item key="course-builder">
							Course Builder
						</Menu.Item>
						<Menu.Item key="settings">Ok</Menu.Item>
					</Menu>
				</Header>
				<Content></Content>
				<Footer></Footer>
			</Layout>
		);
	}
}
