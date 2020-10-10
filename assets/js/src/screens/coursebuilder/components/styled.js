import styled from "styled-components";
import colors from "../../../config/colors";
import defaultStyle, { BaseLine } from "../../../config/defaultStyle";
import fontSize from "../../../config/fontSize";

export const ContentContainer = styled.div`
	background-color: ${colors.WHITE};
	box-shadow: ${(props) =>
		props.isDragging ? '0 0 15px rgba(0, 0, 0, 0.1)' : 'none'};
	border: 1px solid ${colors.BORDER};
	padding: ${BaseLine * 2}px;
	border-radius: ${defaultStyle.borderRadius};
	margin-bottom: ${BaseLine * 2}px;
`;

export const ContentTitle = styled.h5`
	margin: 0;
	font-weight: 400;
	font-size: ${fontSize.LARGE};
`;