import styled from 'styled-components';
import colors from '../../config/colors';
import { BaseLine } from '../../config/defaultStyle';

const Label = styled.label`
	color: ${colors.HEADING};
	font-weight: 500;
	margin-bottom: ${BaseLine * 2}px;
`;

export default Label;
