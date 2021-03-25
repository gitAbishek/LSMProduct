import { DotsVertical } from '../../assets/icons';
import Icon from 'Components/common/Icon';
import React from 'react';
import tw from 'tailwind-styled-components';

interface Props extends React.HTMLAttributes<HTMLButtonElement> {}

const OptionButton = React.forwardRef<HTMLButtonElement, Props>(
	(props, ref) => {
		const { ...other } = props;

		return (
			<OptionButtonContainer ref={ref} {...other}>
				<Icon className="mto-text-xl" icon={<DotsVertical />} />
			</OptionButtonContainer>
		);
	}
);

const OptionButtonContainer = tw.button`
	mto-flex
	mto-justify-center
	mto-items-center
	mto-border
	mto-border-solid
	mto-font-medium
	mto-rounded-sm
	mto-transition-all
	mto-duration-300
	mto-ease-in-out
	mto-border-gray-200
	mto-text-gray-700
	hover:mto-border-primary
	hover:mto-text-primary
	mto-h-10
	mto-w-11
	mto-text-xs
`;

export default OptionButton;
