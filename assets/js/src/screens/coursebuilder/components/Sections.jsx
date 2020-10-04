import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';
import Content from './Content';

const Sections = (props) => {
	const { contents, section } = props;

	return (
		<Container>
			<h3>OK OK</h3>
			<h1>{section.title}</h1>
			<div>
				{contents.map((content) => (
					<Content key={content.id} title={content.title}></Content>
				))}
			</div>
		</Container>
	);
};

const Container = styled.div`
	background-color: ${colors.LIGHT_GRAY};
`;
export default Sections;
