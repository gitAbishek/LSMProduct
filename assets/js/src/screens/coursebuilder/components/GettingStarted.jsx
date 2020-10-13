import { React } from '@wordpress/element';
import Button from '../../../components/common/Button';
import Flex from '../../../components/common/Flex';
import FlexRow from '../../../components/common/FlexRow';
import styled from 'styled-components';
import fontSize from '../../../config/fontSize';
import colors from '../../../config/colors';
import { BaseLine } from '../../../config/defaultStyle';

const GettingStarted = () => {
	return (
		<GettingStartedContainer>
			<GettingStartedTitle>Add your content</GettingStartedTitle>
			<GettingStartedInfo>
				Upload your content, quiz, assignment
			</GettingStartedInfo>
			<ActionButtons>
				<Button primary>Add Section</Button>
				<Button primary>Add Assignment</Button>
				<Button primary>Add Quiz</Button>
			</ActionButtons>
			<GettingStartedInfo>
				Not sure how to get started? <br />
				Learn about it in our{' '}
				<a href="themegrill.com/masteriyo/getting-started">Documentation</a>
			</GettingStartedInfo>
		</GettingStartedContainer>
	);
};

const GettingStartedContainer = styled(Flex)`
	min-height: 360px;
	align-items: center;
	justify-content: center;
	text-align: center;
`;

const ActionButtons = styled(FlexRow)`
	margin-left: -8px;
	margin-right: -8px;
	margin-bottom: ${BaseLine * 3}px;

	Button {
		margin-left: 8px;
		margin-right: 8px;
	}
`;

const GettingStartedTitle = styled.h1`
	font-size: ${fontSize.HUGE};
	colors: ${colors.HEADING};
	font-weight: 500;
	margin: 0;
	margin-bottom: ${BaseLine}px;
`;

const GettingStartedInfo = styled.p`
	color: ${colors.LIGHT_TEXT};
	margin-bottom: ${BaseLine * 4}px;
	line-height: 24px;

	a {
		color: ${colors.HEADING};
		text-decoration: none;
		font-weight: bold;
	}
`;

export default GettingStarted;
