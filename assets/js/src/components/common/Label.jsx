import styled from 'styled-components';
import colors from 'Config/colors';
import { BaseLine } from 'Config/defaultStyle';

const Label = styled.label`
	color: ${colors.HEADING};
	font-weight: 500;
	margin-bottom: ${BaseLine * 2}px;
`;

export default Label;
