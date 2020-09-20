import styled from 'styled-components';
import colors from '../../config/colors';
import defaultStyle, { BaseLine } from '../../config/defaultStyle';

const Textarea = styled.textarea`
	min-height: ${BaseLine * 6}px;
	border-radius: ${defaultStyle.borderRadius};
	padding: ${BaseLine * 2}px;
	border: 1px solid ${colors.BORDER};
	box-shadow: 0 1px 0 ${colors.SHADOW};
	transition: all 0.35s ease-in-out;
	background-color: ${colors.WHITE};

	&:hover {
		border-color: ${colors.PRIMARY};
	}

	&:focus {
		background-color: ${colors.LIGHT_BLUEISH_GRAY};
	}

	&::placeholder {
		color: ${colors.PLACEHOLDER};
	}
`;

export default Textarea;
