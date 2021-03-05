import { DotsVertical } from '../../assets/icons';
import Icon from 'Components/common/Icon';
import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLButtonElement> {}

const OptionButton = React.forwardRef<HTMLButtonElement, Props>(
	(props, ref) => {
		const { className, ...other } = props;

		const baseStyle =
			'mto-flex mto-justify-center mto-items-center mto-border mto-border-solid mto-font-medium mto-rounded-sm mto-transition-all mto-duration-300 mto-ease-in-out mto-border-gray-300 mto-text-gray-700 hover:mto-border-primary hover:mto-text-primary mto-h-9 mto-w-10 mto-text-xs';
		const cls = classNames(baseStyle, className);

		return (
			<button className={cls} ref={ref} {...other}>
				<Icon className="mto-text-xl" icon={<DotsVertical />} />
			</button>
		);
	}
);

export default OptionButton;
