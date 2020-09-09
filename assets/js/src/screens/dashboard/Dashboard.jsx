import { Component } from '@wordpress/element';
import { Button, Layout, Menu, Row, Col } from 'antd';
import {
	BookOutlined,
	EditOutlined,
	SettingOutlined,
	EyeOutlined,
} from '@ant-design/icons';

const { Header, Content, Footer } = Layout;
export default class Dashboard extends Component {
	render() {
		return (
			<div>
				<Header style={{ padding: 0 }}>
					<Row type="flex">
						<Col lg={18} span="24">
							<Menu mode="horizontal">
								<Menu.Item key="course" icon={<BookOutlined />}>
									Course
								</Menu.Item>
								<Menu.Item
									key="course-builder"
									icon={<EditOutlined />}>
									Course Builder
								</Menu.Item>
								<Menu.Item
									key="settings"
									icon={<SettingOutlined />}>
									Settings
								</Menu.Item>
							</Menu>
						</Col>
						<Col lg={6} span="24">
							<Button type="default" icon={<EyeOutlined />}>
								Preview
							</Button>
						</Col>
					</Row>
				</Header>
				<Content></Content>
				<Footer></Footer>
			</div>
		);
	}
}
