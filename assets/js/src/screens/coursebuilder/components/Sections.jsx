import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';
import Content from './Content';
import PropTypes from 'prop-types';
import defaultStyle, { BaseLine } from '../../../config/defaultStyle';
import DragHandle from './DragHandle';
import FlexRow from '../../../components/common/FlexRow';
import fontSize from '../../../config/fontSize';

const Sections = (props) => {
	const { contents, section } = props;

	return (
		<Container>
			<SectionHeader>
				<FlexRow>
					<DragHandle />
					<SectionTitle>{section.title}</SectionTitle>
				</FlexRow>
			</SectionHeader>

			<div>
				{contents.map((content) => (
					<Content key={content.id} title={content.title}></Content>
				))}
			</div>
		</Container>
	);
};

Sections.propTypes = {
	contents: PropTypes.array,
	section: PropTypes.object,
};

const Container = styled.div`
	background-color: ${colors.WHITE};
	border-radius: ${defaultStyle.borderRadius};
	padding: ${BaseLine * 4}px;
	margin-top: ${BaseLine * 6}px;
`;

const SectionHeader = styled.header`
	display: flex;
	margin-bottom: ${BaseLine * 4}px;
`;

const SectionTitle = styled.h3`
	font-size: ${fontSize.EXTRA_LARGE};
	font-weight: 500;
	margin: 0;
`;
export default Sections;
