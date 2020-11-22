import styled from 'styled-components';
import colors from 'Config/colors';
import { BaseLine } from 'Config/defaultStyle';
import fontSize from 'Config/fontSize';

const DropdownOverlay = styled.div`
	background-color: ${colors.WHITE};
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.04);
	border: 1px solid ${colors.BORDER};

	ul {
		margin: 0;
		padding: 0;
		list-style-type: none;

		li {
			display: flex;
			align-items: center;
			padding: ${BaseLine * 1.5}px ${BaseLine * 2}px;
			cursor: pointer;
			transition: all 0.35s ease-in-out;

			&:hover {
				background-color: ${colors.LIGHT_BLUEISH_GRAY};
			}

			i {
				font-size: ${fontSize.LARGE};
				margin-right: ${BaseLine * 0.5}px;
			}
		}
	}
`;

export default DropdownOverlay;
