import { Component } from '@wordpress/element';
import { Link } from 'react-router-dom';
import { Button, Layout, Menu, Icon } from 'antd';
import testComp from '../../mycomponents';

const { Header, Content, Footer } = Layout;
export default class Dashboard extends Component {
	render() {
		return (
			<div>
				<Header style={{ padding: 0 }}>
					<testComp />
					<Icon
						type="expand"
						theme="outline"
						style={{ backgroundColor: 'red' }}
					/>
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
