import { BaseLine } from 'Config/defaultStyle';
import Button from 'Components/common/Button';
import Flex from 'Components/common/Flex';
import FlexRow from 'Components/common/FlexRow';
import React from 'react';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import styled from 'styled-components';
import { __ } from '@wordpress/i18n';

const GettingStarted = () => {
	return (
		<GettingStartedContainer>
			<GettingStartedTitle>{__( 'Add your content', 'masteriyo' )}</GettingStartedTitle>
			<GettingStartedInfo>
				{__( 'Upload your content, quiz, assignment', 'masteriyo' )}
			</GettingStartedInfo>
			<ActionButtons>
				<Button appearance="primary">{__( 'Add Section', 'masteriyo' )}</Button>
				<Button appearance="primary">{__( 'Add Assignment', 'masteriyo' )}</Button>
				<Button appearance="primary">{__( 'Add Quiz', 'masteriyo' )}</Button>
			</ActionButtons>
			<GettingStartedInfo>
				{__( 'Not sure how to get started?', 'masteriyo' )} <br />
				{__( 'Learn about it in our', 'masteriyo' )}{' '}
				<a href="themegrill.com/masteriyo/getting-started">{__( 'Documentation', 'masteriyo' )}</a>
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

	.masteriyo-button {
		margin-left: 8px;
		margin-right: 8px;
	}
`;

const GettingStartedTitle = styled.h1`
	font-size: ${fontSize.HUGE};
	color: ${colors.HEADING};
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
