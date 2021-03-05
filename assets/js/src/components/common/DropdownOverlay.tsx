import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLDivElement> {}

const DropdownOverlay = React.forwardRef<HTMLDivElement, Props>(
	(props, ref) => {
		const { className, children, ...other } = props;

		const baseStyle =
			'mto-bg-white mto-shadow-sm mto-border mto-border-solid mto-border-gray-300 mto-mt-2 mto-shadow-md';
		const cls = classNames(baseStyle, className);

		return (
			<div className={cls} ref={ref} {...other}>
				{children}
			</div>
		);
	}
);

export default DropdownOverlay;
