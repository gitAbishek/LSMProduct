import { BaseLine } from 'Config/defaultStyle';
import Icon from 'Components/common/Icon';
import { Plus } from '../../assets/icons';
import React from 'react';
import classNames from 'classnames';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import { lighten } from 'polished';
import styled from 'styled-components';

interface Props {}

const AddNewButton: React.FC<Props> = (props) => {
	return (
		<button
			{...props}
			className={classNames(
				'mto-mt-8 mto-flex mto-items-center mto-cursor-pointer mto-transition-all'
			)}>
			<Icon
				icon={<Plus />}
				className="mto-w-8 mto-h-8 mto-bg-primary mto-rounded-full mto-flex mto-justify-center mto-items-center mto-text-white"
			/>
			{props.children}
		</button>
	);
};

const StyledButton = styled.button`
	margin-top: ${BaseLine * 3}px;
	border: none;
	background: transparent;
	display: flex;
	align-items: center;
	cursor: pointer;
	transition: all 0.35s ease-in-out;

	i {
		width: ${BaseLine * 4}px;
		transition: all 0.35s ease-in-out;
		height: ${BaseLine * 4}px;
		justify-content: center;
		align-items: center;
		font-size: ${fontSize.HUGE};
		background-color: ${colors.PRIMARY};
		border-radius: 100%;
		color: ${colors.WHITE};
		margin-right: ${BaseLine}px;
	}

	a {
		text-decoration: none;
		color: ${colors.HEADING};
	}

	&:hover {
		color: ${colors.PRIMARY};
		i {
			background-color: ${lighten(0.06, colors.PRIMARY)};
		}
	}
`;

export default AddNewButton;
