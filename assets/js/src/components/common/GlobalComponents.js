import { BaseLine } from 'Config/defaultStyle';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import styled from 'styled-components';

export const SectionHeader = styled.header`
	display: flex;
	margin-bottom: ${BaseLine * 3}px;
	justify-content: space-between;
`;

export const SectionTitle = styled.h3`
	font-size: ${fontSize.EXTRA_LARGE};
	font-weight: 500;
	margin: 0;
`;

export const SectionAction = styled.div``;

export const SectionBody = styled.div``;

export const SectionFooter = styled.footer`
	padding-top: ${BaseLine * 3}px;
	border-top: 1px solid ${colors.BORDER};
`;
